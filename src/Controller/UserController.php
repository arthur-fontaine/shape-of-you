<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\UserMoodPrompt;
use App\Repository\ClothingListRepository;
use App\Repository\PostRepository;
use App\Repository\UserMoodPromptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_my_user')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'userRelationship' => 'self'
        ]);
    }

    #[Route('/users/{id}', name: 'app_user')]
    public function show(User $user): Response
    {
        if ($user === $this->getUser()) {
            return $this->redirectToRoute('app_my_user');
        }
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'userRelationship' => (
                $user->getFriends()->contains($this->getUser())
                    ? 'friend'
                    : 'none'
            )
    }

    #[Route('/edit-mood-prompt', name: 'app_edit_mood_prompt', methods: ['POST'])]
    public function editMoodPrompt(Request $request, UserMoodPromptRepository $userMoodPromptRepository, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $body = $request->toArray();
        $moodPromptReq = $body['mood'];
        $userMoodPrompt = $userMoodPromptRepository->findOneBy(['owner' => $user]);
        
        if ($userMoodPrompt) {
            $userMoodPrompt->setPrompt($moodPromptReq);
        } else {
            $userMoodPrompt = new UserMoodPrompt();
            $userMoodPrompt->setOwner($user);
            $userMoodPrompt->setPrompt($moodPromptReq);
        }
        
        $entityManager->persist($userMoodPrompt);
        $entityManager->flush();

        return $this->redirectToRoute('app_user');
    }

    #[Route('/profile/ai', name: 'app_user_ai')]
    public function ai(): Response
    {
        $user = $this->getUser();
        return $this->render('user/ai.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/users/{id}/add-friend', name: 'app_user_add_friend')]
    public function addFriend(User $user, EntityManagerInterface $entityManager): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($currentUser === $user) {
            throw $this->createAccessDeniedException('You cannot add yourself as a friend');
        }
        $user->addFriend($currentUser);
        $entityManager->flush();
        return new JsonResponse();
    }

    #[Route('/users/{id}/remove-friend', name: 'app_user_remove_friend')]
    public function removeFriend(User $user, EntityManagerInterface $entityManager): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($currentUser === $user) {
            throw $this->createAccessDeniedException('You cannot remove yourself as a friend');
        }
        $user->removeFriend($currentUser);
        $entityManager->flush();
        return new JsonResponse();
    }
}
