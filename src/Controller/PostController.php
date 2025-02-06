<?php

namespace App\Controller;

use App\Form\NewPostFormType;
use App\Repository\MediaRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    private MediaRepository $mediaRepository;
    private PostRepository $postRepository;

    public function __construct(MediaRepository $mediaRepository, PostRepository $postRepository)
    {
        $this->mediaRepository = $mediaRepository;
        $this->postRepository = $postRepository;
    }

    #[Route('/posts/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('posts/new.html.twig', [
                'controller_name' => 'PostController',
            ]);
        }

        // Handle the submit of the form to create a new post

        $form = $this->createForm(NewPostFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filename = $this->mediaRepository->upload($form->get('image')->getData());

            $this->postRepository->create($this->getUser(), $form->get('text')->getData(), [$filename]);

            return new JsonResponse();
        }

        throw new BadRequestHttpException($form->getErrors(true, false));
    }
}
