<?php

namespace App\Controller;

use App\Enum\ClothingType;
use App\Enum\Color;
use App\Repository\ClothingRepository;
use App\Service\SearchService;
use App\Service\OpenAiApi;
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
