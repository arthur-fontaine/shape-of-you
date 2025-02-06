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

    #[Route('/posts/new', name: 'app_post_new', methods: ['GET'], requirements: ['_format' => 'html'])]
    public function renderNewPostPage(Request $request): Response
    {
        return $this->render('posts/new.html.twig');
    }

    #[Route('/posts/new', name: 'api_post_new', methods: ['POST'], requirements: ['_format' => 'json'])]
    public function createNewPost(Request $request): Response
    {
        $form = $this->createForm(NewPostFormType::class);
        $form->submit(array_merge($request->request->all(), $request->files->all()));

        if ($form->isSubmitted() && $form->isValid()) {
            $filename = $this->mediaRepository->upload($form->get('image')->getData());

            $this->postRepository->create($this->getUser(), $form->get('text')->getData(), [$filename]);

            return new Response();
        }

        throw new BadRequestHttpException($form->getErrors(true, false));
    }
}
