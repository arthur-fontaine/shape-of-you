<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\MediaRepository;
use App\Repository\PostRateRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class PostRateController extends AbstractController
{
    private PostRateRepository $postRateRepository;

    public function __construct(PostRateRepository $postRateRepository)
    {
        $this->postRateRepository = $postRateRepository;
    }

    #[Route('/posts/{id}/rate', name: 'api_rate_post', methods: ['PATCH'], requirements: ['_format' => 'json'])]
    public function ratePost(Post $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['rate'])) {
            throw new BadRequestHttpException('Missing required parameters');
        }

        $this->postRateRepository->ratePost($id, $this->getUser(), $data['rate']);

        return new Response();
    }
}
