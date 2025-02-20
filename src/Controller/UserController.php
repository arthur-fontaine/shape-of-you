<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ClothingListRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/profil', name: 'app_user')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/bookmark', name: 'app_user_bookmark')]
    public function bookmark(): Response
    {
        $user = $this->getUser();
        return $this->render('user/bookmark.html.twig', [
            'user' => $user
        ]);
    }
}
