<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BrandController extends AbstractController
{
    #[Route('/brand', name: 'app_admin_brands')]
    public function index(BrandRepository $brandRepository): Response
    {
        $brands = $brandRepository->findAll();
        return $this->render('admin/brands.html.twig', [
            'brands' => $brands,
        ]);
    }

    #[Route('/admin/brand/{id}', name: 'app_admin_brand')]
    public function show(Brand $brand): Response
    {
        return $this->render('admin/brand.html.twig', [
            'brand' => $brand,
        ]);
    }
}
