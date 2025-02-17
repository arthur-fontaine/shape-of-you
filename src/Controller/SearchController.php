<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ClothingRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SearchController extends AbstractController
{
    public function __construct(private ClothingRepository $clothingRepository)
    {
    }

    #[Route('/search', name: 'app_search')]
    public function index(): Response
    {
        return $this->render('search/index.html.twig');
    }

    #[Route('/api/clothing/search', name: 'api_clothing_search', methods: ['GET'])]
    public function search(Request $request,): JsonResponse
    {
        try {
            $query = $request->query->get('q');
            
            if (empty($query) || strlen($query) < 2) {
                return $this->json([]);
            }
            
            $results = $this->clothingRepository->searchByText($query);
            
            return $this->json($results);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'An error occurred while searching',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/api/users/search', name: 'api_users_search', methods: ['GET'])]
    public function searchUsers(Request $request, UserRepository $userRepository): JsonResponse
    {
        try {
            $query = $request->query->get('q');
            
            if (empty($query) || strlen($query) < 2) {
                return $this->json([]);
            }
            
            $results = $userRepository->searchByText($query);
            
            return $this->json($results);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'An error occurred while searching',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
