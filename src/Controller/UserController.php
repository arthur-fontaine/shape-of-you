<?php

namespace App\Controller;

use App\Entity\User;
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
    #[Route('/profile', name: 'app_user')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
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

}
