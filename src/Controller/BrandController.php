<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class BrandController extends AbstractController
{

    public function __construct(
        private BrandRepository $brandRepository,
    )
    {
    }
    #[Route('/brand', name: 'app_admin_brands')]
    public function index(BrandRepository $brandRepository): Response
    {
        $brands = $brandRepository->findAll();
        return $this->render('admin/brands.html.twig', [
            'brands' => $brands,
        ]);
    }

    #[Route('/admin/brand/{id}', name: 'app_admin_brand', methods: ['GET'])]
    public function show(Brand $brand): Response
    {
        return $this->render('admin/brand.html.twig', [
            'brand' => $brand,
        ]);
    }

    #[Route('/admin/brand/{id}', name: 'app_admin_brand_update', methods: ['POST'])]
    public function update(Brand $brand, Request $request): Response
    {
        $brand->setName($request->request->get('name'));
        $this->brandRepository->save($brand);
        return $this->redirectToRoute('app_admin_brand', ['id' => $brand->getId()]);
    }

    #[Route('/admin/new/brand', name: 'app_admin_brand_new', methods: ['GET'])]
    public function new(): Response
    {
        return $this->render('admin/brand_new.html.twig');
    }

    #[Route('/admin/new/brand', name: 'app_admin_brand_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $brand = new Brand();
        $brand->setName($request->request->get('name'));
        $this->brandRepository->save($brand);
        return $this->redirectToRoute('app_admin_brand', ['id' => $brand->getId()]);
    }
}
