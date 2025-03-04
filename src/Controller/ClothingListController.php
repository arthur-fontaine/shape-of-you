<?php

namespace App\Controller;

use App\Entity\Clothing;
use App\Entity\ClothingLink;
use App\Entity\ClothingList;
use App\Repository\ClothingListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class ClothingListController extends AbstractController
{
    private ClothingListRepository $clothingListRepository;

    public function __construct(ClothingListRepository $clothingListRepository)
    {
        $this->clothingListRepository = $clothingListRepository;
    }
    #[Route('/profile/bookmarks', name: 'app_clothing_list')]
    public function bookmark(): Response
    {
        $user = $this->getUser();
        return $this->render('user/bookmark.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/profile/bookmarks/new', name: 'app_new_clothing_list', requirements: ['_format' => 'html'], methods: ['GET'])]
    public function renderNewBookmarkPage(Request $request): Response
    {
        return $this->render('clothing_list/new.html.twig');
    }

    #[Route('/profile/bookmarks/new', name: 'api_new_clothing_list', requirements: ['_format' => 'json'], methods: ['POST'])]
    public function createNewBookmark(Request $request): Response
    {
        $data = $request->request->all();

        if (!isset($data['name'])) {
            throw new BadRequestHttpException('Missing required parameters');
        }

        $isBookmark = ($data['isBookmark'] != 'null') ? $data['isBookmark'] : false;
        $clothingList= $this->clothingListRepository->create($this->getUser(), $data['name'], $isBookmark);

        $this->getUser()->addClothingList($clothingList);

        return $this->json([
            'bookmarkListUrl' => $this->generateUrl('app_user_clothing_list', ['id' => $clothingList->getId()])
        ]);
    }

    #[Route('/profile/bookmarks/delete', name: 'api_delete_clothing_list', requirements: ['_format' => 'json'], methods: ['POST'])]
    public function delete(Request $request): Response
    {
        $data = $request->toArray();
        if (!isset($data['bookmarkId'])) {
            throw new BadRequestHttpException('Missing required parameters');
        }
        $this->clothingListRepository->delete($data['bookmarkId']);
        return new JsonResponse();
    }

    #[Route('/profile/bookmarks/{id}', name: 'app_user_clothing_list')]
    public function clothingList(ClothingList $clothingList, SerializerInterface $serializer ): Response
    {
        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, ?string $format, array $context): string {
                if (!$object instanceof Clothing && !$object instanceof ClothingLink && !$object instanceof ClothingList) {
                    throw new CircularReferenceException('A circular reference has been detected when serializing the object of class "'.get_debug_type($object).'".');
                }

                return '';
            },
        ];

        return $this->render('clothing_list/index.html.twig', [
            'clothingList' => $clothingList,
            'clothingCollection' => json_decode($serializer->serialize($clothingList->getClothings()->toArray(), 'json', $context)),
        ]);
    }

    #[Route('/profile/bookmarks/{bookmarkId}/remove-item', name: 'api_remove_bookmark_item', requirements: ['_format' => 'json'], methods: ['POST'])]
    public function removeItem(Request $request, string $bookmarkId): Response
    {
        $data = $request->toArray();
        if (!isset($data['clothingId'])) {
            throw new BadRequestHttpException('Missing required parameters');
        }
        $this->clothingListRepository->removeClothing($data['clothingId'], (int) $bookmarkId);

        return new JsonResponse();
    }

    #[Route('/clothing/{clothingId}/add-to-bookmarks', name: 'api_add_clothing_to_clothing_list_modal', requirements: ['_format' => 'html'], methods: ['GET'])]
    public function renderAddElementModal(string $clothingId): Response
    {
        $clothingList = $this->getUser()->getClothingLists()->toArray();
        return $this->render('clothing_list/modal.html.twig', [
            'clothingList' => $clothingList,
            'clothingId' => $clothingId,
        ]);
    }

    #[Route('/clothing/{clothingId}/add-to-bookmarks', name: 'api_add_clothing_to_clothing_list', methods: ['POST'])]
    public function addElement(Request $request, string $clothingId): Response
    {
        $collection = $request->request->get('collection');
        if (!$collection) {
            throw new BadRequestHttpException('Missing required parameters');
        }

        $this->clothingListRepository->addClothing((int) $clothingId, (int) $collection);
        return new JsonResponse();
    }
}
