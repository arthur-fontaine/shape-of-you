<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ClothingListRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
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

    #[Route('/admin/users', name: 'app_admin_users')]
    public function adminUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/admin/delete/users/{id}', name: 'app_admin_delete_users')]
    public function deleteUser(User $user, UserRepository $userRepository): Response
    {
        $userRepository->delete($user);
        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/admin/user/{id}', name: 'app_admin_user')]
    public function adminUser(User $user): Response
    {
        return $this->render('admin/user.html.twig', [
            'user' => $user
        ]);
    }
}
