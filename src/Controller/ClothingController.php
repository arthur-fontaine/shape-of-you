<?php

namespace App\Controller;

use App\Entity\Clothing;
use App\Entity\Interaction;
use App\Entity\User;
use App\Enum\ClothingType;
use App\Enum\Color;
use App\Repository\BrandRepository;
use App\Repository\ClothingRepository;
use App\Repository\DressingPieceRepository;
use App\Repository\InteractionRepository;
use Container8JAxYPd\getTwig_MimeBodyRendererService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ClothingController extends AbstractController
{
    public function __construct(
        private ClothingRepository $clothingRepository,
    ) {}

    #[Route('/clothing/{id}', name: 'app_clothing_show', methods: ['GET'])]
    public function show(Clothing $clothing, DressingPieceRepository $dressingPieceRepository, InteractionRepository $interactionRepository): Response
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
        $interaction = new Interaction();
        $interaction->setOriginUser($user);
        $interaction->setInteraction(
            [
                'type' => 'pageView',
                'brand' => $clothing->getBrand()->getName(),
                'datetime' => new \DateTime()
            ]
        );
        $interactionRepository->save($interaction);
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

    #[Route('/admin/clothing/{id}', name: 'app_admin_clothing', methods: ['GET'])]
    public function adminClothing(Clothing $clothing, BrandRepository $brandRepository): Response
    {
        $clothingData = [
            'id' => $clothing->getId(),
            'name' => $clothing->getName(),
            'type' => $clothing->getType()->value,
            'imageUrl' => $clothing->getImageUrl(),
            'color' => $clothing->getColor(),
            'socialRate5' => $clothing->getSocialRate5(),
            'ecologyRate5' => $clothing->getEcologyRate5(),
            'measurements' => $clothing->getMeasurements(),
            'brand' => [
                'id' => $clothing->getBrand()?->getId(),
                'name' => $clothing->getBrand()?->getName()
            ]
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
        $brands = $brandRepository->findAll();
        return $this->render('admin/clothing.html.twig', [
            'clothing' => $clothingData,
            'links' => $links,
            'clothing_types' => ClothingType::cases(),
            'colors' => Color::cases(),
            'brands' => $brands
        ]);
    }

    #[Route('/admin/clothing/{id}', name: 'app_admin_clothing_update', methods: ['POST'])]
    public function updateClothing(Request $request, Clothing $clothing, BrandRepository $brandRepository): Response
    {
        $color[] = Color::from($request->request->get('color'));
        $clothing->setName($request->request->get('name'));
        $clothing->setType(ClothingType::From($request->request->get('type')));
        $clothing->setImageUrl($request->request->get('imageUrl'));
        $clothing->setColor($color);
        $clothing->setSocialRate5((int) $request->request->get('socialRate5'));
        $clothing->setEcologyRate5((int) $request->request->get('ecologyRate5'));
        $clothing->setMeasurements(json_decode($request->request->get('measurements'), true));
        if ($request->request->get('brand')) {
            $clothing->setBrand($brandRepository->find($request->request->get('brand')));
        }
        else {
            $clothing->setBrand(null);
        }
        $this->clothingRepository->save($clothing);
        return $this->redirectToRoute('app_admin_clothing', ['id' => $clothing->getId()]);
    }

    #[Route('/admin/new/clothing', name: 'app_admin_clothing_new', methods: ['GET'])]
    public function newClothing(BrandRepository $brandRepository): Response
    {
        $brands = $brandRepository->findAll();
        return $this->render('admin/clothing_new.html.twig', [
            'clothing_types' => ClothingType::cases(),
            'colors' => Color::cases(),
            'brands' => $brands
        ]);
    }

    #[Route('/admin/new/clothing', name: 'app_admin_clothing_create', methods: ['POST'])]
    public function createClothing(Request $request, BrandRepository $brandRepository): Response
    {
        $color[] = Color::from($request->request->get('color'));
        $clothing = new Clothing();
        $clothing->setName($request->request->get('name'));
        $clothing->setType(ClothingType::From($request->request->get('type')));
        $clothing->setImageUrl($request->request->get('imageUrl'));
        $clothing->setColor($color);
        $clothing->setSocialRate5((int) $request->request->get('socialRate5'));
        $clothing->setEcologyRate5((int) $request->request->get('ecologyRate5'));
        $clothing->setMeasurements(json_decode($request->request->get('measurements'), true));
        if ($request->request->get('brand')) {
            $clothing->setBrand($brandRepository->find($request->request->get('brand')));
        }
        else {
            $clothing->setBrand(null);
        }
        $this->clothingRepository->save($clothing);
        return $this->redirectToRoute('app_admin_clothing', ['id' => $clothing->getId()]);
    }


}
