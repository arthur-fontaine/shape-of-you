<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'app_user')]
    public function index(User $user): Response
    {
        //recupere les post de l'utilisateur
        $posts = $user->getPosts();
        //dd($user, count($posts), count($user->getFriends()));
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }
}
