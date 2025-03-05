<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BrandRepository;
use App\Repository\ClothingListRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
    ) {
    }
    #[Route('/profile', name: 'app_user')]
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

    #[Route('/admin/user/{id}', name: 'app_admin_user', methods: ['GET'])]
    public function adminUser(User $user, BrandRepository $brandRepository): Response
    {
        $brands = $brandRepository->findAll();
        return $this->render('admin/user.html.twig', [
            'user' => $user,
            'brands' => $brands
        ]);
    }

    #[Route('/admin/user/{id}', name: 'app_admin_user_update', methods: ['POST'])]
    public function updateUser(User $user, BrandRepository $brandRepository, Request $request): Response
    {
        if ($request->request->get('brand')) {
            $user->setBrand($brandRepository->find($request->request->get('brand')));
        }
        else {
            $user->setBrand(null);
        }
        $this->userRepository->save($user);
        return $this->redirectToRoute('app_admin_user', ['id' => $user->getId()]);
    }
}
