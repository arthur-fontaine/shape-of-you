<?php

namespace App\Controller;

use App\Entity\Clothing;
use App\Repository\ClothingRepository;
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
    public function show(Clothing $clothing): Response
    {
        $clothingData = [
            'id' => $clothing->getId(),
            'name' => $clothing->getName(),
            'type' => $clothing->getType()->value,
            'imageUrl' => $clothing->getImageUrl(),
            'color' => $clothing->getColor(),
            'socialRate5' => $clothing->getSocialRate5(),
            'ecologyRate5' => $clothing->getEcologyRate5(),
            'measurements' => $clothing->getMeasurements() 
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

    #[Route('/admin/clothings', name: 'app_admin_clothings', methods: ['GET'])]
    public function adminClothings(): Response
    {
        $clothings = $this->clothingRepository->findAll();
        return $this->render('admin/clothings.html.twig', [
            'clothings' => $clothings
        ]);
    }

    #[Route('/admin/delete/clothings/{id}', name: 'app_admin_clothing_delete')]
    public function deleteClothing(Clothing $clothing): Response
    {
        $this->clothingRepository->delete($clothing);
        return $this->redirectToRoute('app_admin_clothings');
    }


}