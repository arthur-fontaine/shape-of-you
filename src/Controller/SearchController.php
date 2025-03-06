<?php

namespace App\Controller;

use App\Entity\Clothing;
use App\Enum\ClothingFit;
use App\Enum\ClothingMaterial;
use App\Enum\ClothingType;
use App\Enum\Color;
use App\Repository\ClothingRepository;
use App\Service\SearchService;
use App\Service\OpenAiApi;
use App\Service\OpenAiMessage;
use App\Service\OpenAiRole;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    public function __construct(
        private OpenAiApi $openAi,
        private ClothingRepository $clothingRepository,
        private SearchService $searchService
    ) {}

    #[Route('/search', name: 'app_search', methods: ['GET'], requirements: ['_format' => 'html'])]
    public function index(): Response
    {
        $maxPrice = $this->clothingRepository->findMaxPrice();
        return $this->render('search/index.html.twig', [
            'maxPrice' => $maxPrice
        ]);
    }

    #[Route('/search', name: 'api_search', methods: ['POST'], requirements: ['_format' => 'json'])]
    public function search(Request $request): JsonResponse
    {
        $image = $request->files->get('image');
        if ($image === null) {
            $query = $request->request->get('q');

            if ($query === null) {
                throw new BadRequestHttpException('Missing query or image');
            }

            $colorFilters = $request->request->all('colors');
            $typeFilters = $request->request->all('types');
            $excludeUsers = $request->request->has('exclude_users');

            // Get price filter parameters
            $priceMin = $request->request->get('price_min');
            $priceMax = $request->request->get('price_max');

            // Validate price parameters
            $priceMin = is_numeric($priceMin) ? (int)$priceMin : null;
            $priceMax = is_numeric($priceMax) ? (int)$priceMax : null;

            // If exclude_users flag is present, only return clothing items
            if ($excludeUsers) {
                return $this->json($this->clothingRepository->searchByText(
                    $query,
                    $colorFilters,
                    $typeFilters,
                    $priceMin,
                    $priceMax
                ));
            } else {
                return $this->json($this->searchService->textSearch(
                    $query,
                    $colorFilters,
                    $typeFilters,
                    $priceMin,
                    $priceMax
                ));
            }
        }

        if ($image->getMimeType() !== 'image/jpeg' && $image->getMimeType() !== 'image/png') {
            throw new BadRequestHttpException('Invalid image type');
        }

        return $this->json($this->searchService->imageSearch(file_get_contents($image->getPathname())));
    }

    #[Route('/search-by-ai', name: 'api_search_by_ai', methods: ['POST'], requirements: ['_format' => 'json'])]
    public function searchByAi(Request $request, OpenAiApi $openAi): JsonResponse
    {
        $query = $request->request->get('q');
        if ($query === null) {
            throw new BadRequestHttpException('Missing query');
        }

        $colors = implode(' - ', array_map(fn(Color $color) => $color->value, Color::cases()));
        $types = implode(' - ', array_map(fn(ClothingType $type) => $type->value, ClothingType::cases()));
        $fits = implode(' - ', array_map(fn(ClothingFit $fit) => $fit->value, ClothingFit::cases()));
        $materials = implode(' - ', array_map(fn(ClothingMaterial $material) => $material->value, ClothingMaterial::cases()));

        $clothingInfos = $openAi->chat(
            [
                new OpenAiMessage(
                    '
                    You are a fashion expert.
                    
                    You will be asked to suggest clothing items based on the client\'s request. Take into account the client\'s preferences, its budget, its style, and its body shape.
                    
                    The possible fits are:
                    ' . $fits . '

                    The possible materials are:
                    ' . $materials . '

                    The possible colors are:
                    ' . $colors . '

                    The possible types are:
                    ' . $types . '
                    ',
                    OpenAiRole::SYSTEM
                ),
                new OpenAiMessage(
                    $query,
                    OpenAiRole::USER
                )
            ],
            $_ENV['TEXT_MODEL'],
            [
                'type' => 'json_schema',
                'json_schema' => [
                    'name' => 'clothings',
                    'strict' => true,
                    'schema' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'color' => ['type' => 'string'],
                                'type' => ['type' => 'string'],
                                'fit' => ['type' => 'string'],
                                'materials' => ['type' => 'string'],
                            ],
                            'required' => ['color', 'type'],
                            'additionalProperties' => false,
                        ],
                    ],
                ],
            ]
        );

        $clothingInfos = json_decode($clothingInfos, true);
        if ($clothingInfos === null) {
            return new JsonResponse();
        }

        /** @var Clothing[] $clothings */
        $clothings = [];

        if (empty($clothingInfos)) {
            return $this->json($clothings);
        }

        foreach ($clothingInfos as $clothingInfo) {
            $clothings = array_merge(
                $clothings,
                $this->clothingRepository->findByFields(array_filter([
                    'color' => isset($clothingInfo['color']) ? Color::from($clothingInfo['color']) : null,
                    'type' => isset($clothingInfo['type']) ? ClothingType::from($clothingInfo['type']) : null,
                    'fit' => isset($clothingInfo['fit']) ? ClothingFit::from($clothingInfo['fit']) : null,
                    'materials' => isset($clothingInfo['materials']) ? ClothingMaterial::from($clothingInfo['materials']) : null,
                ])) ?? []
            );
        }

        return $this->json($clothings);
    }

    #[Route('/api/search/filters', name: 'api_search_filters', methods: ['GET'])]
    public function getFilters(): JsonResponse
    {
        $colors = array_map(function (Color $color) {
            return [
                'value' => $color->value,
                'label' => ucfirst($color->value) // Simple label conversion, adjust if needed
            ];
        }, Color::cases());

        $types = array_map(function (ClothingType $type) {
            return [
                'value' => $type->value,
                'label' => $this->formatTypeLabel($type->value) // We'll define this helper method
            ];
        }, ClothingType::cases());

        return $this->json([
            'colors' => $colors,
            'types' => $types
        ]);
    }

    private function formatTypeLabel(string $typeValue): string
    {
        // Convert snake_case to readable labels
        // You can customize this further or use translations
        $formatted = str_replace('_', ' ', $typeValue);
        return ucfirst($formatted);
    }
}
