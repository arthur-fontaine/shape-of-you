<?php

namespace App\Controller;

use App\Repository\ClothingRepository;
use App\Repository\SearchService;
use App\Service\OllamaApi;
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
        private ClothingRepository $clothingRepository,
        private SearchService $searchService
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
            $query = $request->query->get('q');

            if ($query === null) {
                throw new BadRequestHttpException('Missing query or image');
            }

            return $this->json($this->searchService->textSearch($query));
        }

        if ($image->getMimeType() !== 'image/jpeg' && $image->getMimeType() !== 'image/png') {
            throw new BadRequestHttpException('Invalid image type');
        }

        return $this->json($this->searchService->imageSearch(file_get_contents($image->getPathname())));
    }
}
