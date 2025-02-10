<?php

namespace App\Controller;

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
        $base64Image = base64_encode(file_get_contents($image->getPathname()));

        $colors = implode(' - ', array_map(fn(Color $color) => $color->name, Color::cases()));
        $types = implode(' - ', array_map(fn(ClothingType $type) => $type->name, ClothingType::cases()));
        $clothingInfos = $this->ollama->chat(
            [
                new OllamaMessage(
                    '
                    Describe this clothing item.

                    The possible colors are:
                    ' . $colors . '

                    The possible types are:
                    ' . $types . '
                    ',
                    OllamaRole::USER,
                    ['images' => $base64Image]
                )
            ],
            [
                'type' => 'object',
                'properties' => [
                    'color' => ['type' => 'string'],
                    'type'=> ['type'=> 'string'],
                ],
                'required'=> ['color', 'type'],
            ]
        );

        $clothingInfos = json_decode($clothingInfos, true);
        if ($clothingInfos === null) {
            throw new BadRequestHttpException('Invalid response from Ollama API');
        }

        $clothings = $this->clothingRepository->findBy([
            'color' => Color::from($clothingInfos['color']),
        ]) ?? [];

        return $this->json($clothings);
    }
}
