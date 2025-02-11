<?php

namespace App\Controller;

use App\Entity\Clothing;
use App\Enum\ClothingType;
use App\Enum\Color;
use App\Repository\ClothingRepository;
use App\Service\OllamaApi;
use App\Service\OllamaMessage;
use App\Service\OllamaRole;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    public function __construct(
        private OllamaApi $ollama,
        private ClothingRepository $clothingRepository
    ) {}

    #[Route('/search', name: 'app_search', methods: ['GET'], requirements: ['_format' => 'html'])]
    public function index(): Response
    {
        return $this->render('search/index.html.twig');
    }

    #[Route('/search', name: 'api_search', methods: ['POST'], requirements: ['_format' => 'json'])]
    public function search(Request $request): JsonResponse
    {
        $image = $request->files->get('image');
        if ($image === null) {
            throw new BadRequestHttpException('Missing image');
        }
        if ($image->getMimeType() !== 'image/jpeg' && $image->getMimeType() !== 'image/png') {
            throw new BadRequestHttpException('Invalid image type');
        }
        $base64Image = base64_encode(file_get_contents($image->getPathname()));

        $colors = implode(' - ', array_map(fn(Color $color) => $color->value, Color::cases()));
        $types = implode(' - ', array_map(fn(ClothingType $type) => $type->value, ClothingType::cases()));
        $clothingInfos = $this->ollama->chat(
            [
                new OllamaMessage(
                    '
                    Describe all clothing items you can see.

                    The possible colors are:
                    ' . $colors . '

                    The possible types are:
                    ' . $types . '
                    ',
                    OllamaRole::USER,
                    ['images' => [$base64Image]]
                )
            ],
            'llama3.2-vision',
            [
                'type' => 'array',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'color' => ['type' => 'string'],
                        'type' => ['type' => 'string'],
                    ],
                    'required' => ['color', 'type'],
                ],
            ]
        );

        $clothingInfos = json_decode($clothingInfos, true);
        if ($clothingInfos === null) {
            throw new BadRequestHttpException('Invalid response from Ollama API');
        }

        /** @var Clothing[] $clothings */
        $clothings = [];

        foreach ($clothingInfos as $clothingInfo) {
            $clothings = array_merge(
                $clothings,
                $this->clothingRepository->findByFields([
                    'color' => Color::from($clothingInfo['color']),
                    'type' => ClothingType::from($clothingInfo['type']),
                ]) ?? []
            );
        }

        return $this->json($clothings);
    }
}
