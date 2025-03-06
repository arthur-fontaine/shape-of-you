<?php

namespace App\Controller;

use App\Entity\Clothing;
use App\Entity\User;
use App\Repository\ClothingRepository;
use App\Repository\DressingPieceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ClothingController extends AbstractController
{
    public function __construct(
        private ClothingRepository $clothingRepository,
    ) {}

    #[Route('/clothing/{id}', name: 'app_clothing_show', methods: ['GET'])]
    public function show(Clothing $clothing, DressingPieceRepository $dressingPieceRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $dressingData = $dressingPieceRepository->findOneBy(['clothing' => $clothing, 'owner' => $this->getUser()]);
        $clothingData = array_merge(
            $clothing->jsonSerialize(),
            [
                'dressing' => $dressingData ? [
                    'rate' => $dressingData->getRate10(),
                    'comment' => $dressingData->getComment()
                ] : null,
                'bookmarked' => $user->getClothingLists()->exists(fn($key, $clothingList) => $clothingList->getClothings()->contains($clothing))
            ]
        );

        $links = array_map(function ($link) {
            $prices = $link->getPrices();
            $latestPrice = $prices->isEmpty() ? null : $prices->last();

            return [
                'id' => $link->getId(),
                'url' => $link->getUrl(),
                'currentPrice' => $latestPrice ? [
                    'priceCts' => $latestPrice->getPriceCts(),
                    'isOnSale' => $latestPrice->isOnSale(),
                    'registeredAt' => $latestPrice->getRegisteredAt()->format('c')
                ] : null
            ];
        }, $clothing->getLinks()->toArray());
        return $this->render('clothing/index.html.twig', [
            'clothing' => $clothingData,
            'links' => $links
        ]);
    }
}
