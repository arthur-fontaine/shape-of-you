<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
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
        $data = array_merge($request->request->all(), $request->files->all());

        if (!isset($data['text']) || !isset($data['image'])) {
            throw new BadRequestHttpException('Missing required parameters');
        }

        $image = is_array($data['image'])
            ? $data['image'][0]
            : $data['image'];
        $imageContent = is_string($image)
            ? $image
            : $image->getContent();
        if (strpos($imageContent, 'data:image/') === 0) {
            $filename = $this->mediaRepository->uploadBase64($imageContent);
        } else {
            if (strpos($image->getMimeType(), 'image/') !== 0) {
                throw new BadRequestHttpException('Invalid image type');
            }
            $filename = $this->mediaRepository->upload($image);
        }

        $this->postRepository->create($this->getUser(), $data['text'], [$filename]);

        return new JsonResponse();
    }

    #[Route('/posts/{id}/delete', name: 'app_user_delete_post', methods: ['POST'])]
    public function deleteUserPost(Post $post, Request $request): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        
        if ($post->getAuthor() !== $currentUser) {
            throw $this->createAccessDeniedException('You do not have permission to delete this post');
        }
        
        $this->postRepository->delete($post->getId(), $currentUser->getId());
        
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['success' => true]);
        }
        
        $this->addFlash('success', 'Your post was successfully deleted');
        
        return new JsonResponse();
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

    #[Route('/admin/delete/posts/{id}', name: 'app_admin_delete_posts')]
    public function deletePost(int $id, PostRepository $postRepository): Response
    {
        $post = $postRepository->find($id);
        $postRepository->delete($post);
        return $this->redirectToRoute('app_admin_posts');
    }

    #[Route('/admin/post/{id}', name: 'app_admin_post')]
    public function adminPost(Post $post): Response
    {
        return $this->render('admin/post.html.twig', [
            'post' => $post
        ]);
    }
}
