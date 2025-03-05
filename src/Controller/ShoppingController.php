<?php

namespace App\Controller;

use App\Entity\Clothing;
use App\Entity\User;
use App\Repository\UserClothingRecommendationRepository;
use Doctrine\Common\DataFixtures\Exception\CircularReferenceException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ShoppingController extends AbstractController
{
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
