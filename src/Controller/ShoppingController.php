<?php

namespace App\Controller;

use App\Repository\UserClothingRecommendationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ShoppingController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/buy', name: 'app_shopping')]
    public function index(UserClothingRecommendationRepository $userClothingRecommendationRepository, NormalizerInterface $serializer): Response
    {
        $recommendations = $userClothingRecommendationRepository->findUserRecommendations($this->getUser());

        return $this->render('shopping/index.html.twig', [
            'recommendations' => array_map(fn($recommendation) => [
                'clothing' => $recommendation->getClothing(),
                'type' => $recommendation->getType(),
            ], $recommendations)
        ]);
    }
}
