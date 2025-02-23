<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $data = array_merge($request->request->all(), $request->files->all());

        if (!isset($data['text']) || !isset($data['image'])) {
            throw new BadRequestHttpException('Missing required parameters');
        }

        $image = is_array($data['image'])
            ? $data['image'][0]
            : $data['image'];
        if (strpos($image->getContent(), 'data:image/') === 0) {
            $filename = $this->mediaRepository->uploadBase64($image->getContent());
        } else {
            if (strpos($image->getMimeType(), 'image/') !== 0) {
                throw new BadRequestHttpException('Invalid image type');
            }
            $filename = $this->mediaRepository->upload($image);
        }

        $this->postRepository->create($this->getUser(), $data['text'], [$filename]);

        return new Response();
    }

    #[Route('/admin/posts', name: 'app_admin_posts')]
    public function adminPosts(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        //dd($posts);
        return $this->render('admin/posts.html.twig', [
            'posts' => $posts
        ]);
    }
}
