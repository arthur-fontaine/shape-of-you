<?php

namespace App\Controller;

use App\Entity\Clothing;
use App\Repository\ClothingRepository;
use App\Repository\DressingPieceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ClothingController extends AbstractController
{
    public function __construct(
        private ClothingRepository $clothingRepository,
    ) {
    }

    #[Route('/clothing/{id}', name: 'app_clothing_show', methods: ['GET'])]
    public function show(Clothing $clothing, DressingPieceRepository $dressingPieceRepository): Response
    {
        $dressingData = $dressingPieceRepository->findOneBy(['clothing' => $clothing, 'owner' => $this->getUser()]);
        $clothingData = [
            'id' => $clothing->getId(),
            'name' => $clothing->getName(),
            'type' => $clothing->getType()->value,
            'imageUrl' => $clothing->getImageUrl(),
            'color' => $clothing->getColor(),
            'socialRate5' => $clothing->getSocialRate5(),
            'ecologyRate5' => $clothing->getEcologyRate5(),
            'measurements' => $clothing->getMeasurements() ,
            'isInDressing' => $dressingData !== null,
            'rate' => $dressingData?->getRate10(),
            'comment' => $dressingData?->getComment(),
        ];

        $links = array_map(function($link) {
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