<?php

namespace App\Controller;

use App\Entity\Clothing;
use App\Entity\ClothingLink;
use App\Entity\ClothingList;
use App\Entity\User;
use App\Repository\ClothingListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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

    #[IsGranted('ROLE_USER')]
    #[Route('/profile/bookmarks', name: 'app_clothing_list')]
    public function bookmark(): Response
    {
        $user = $this->getUser();
        return $this->render('user/bookmark.html.twig', [
            'user' => $user
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/profile/bookmarks/new', name: 'app_new_clothing_list', requirements: ['_format' => 'html'], methods: ['GET'])]
    public function renderNewBookmarkPage(Request $request): Response
    {
        return $this->render('clothing_list/new.html.twig');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/profile/bookmarks/new', name: 'api_new_clothing_list', requirements: ['_format' => 'json'], methods: ['POST'])]
    public function createNewBookmark(Request $request): Response
    {
        $data = $request->request->all();

        if (!isset($data['name'])) {
            throw new BadRequestHttpException('Missing required parameters');
        }

        /** @var User $user */
        $user = $this->getUser();

        $clothingList = $this->clothingListRepository->create($user, $data['name'], true);

        $user->addClothingList($clothingList);

        return $this->json([
            'bookmarkListUrl' => $this->generateUrl('app_user_clothing_list', ['id' => $clothingList->getId()])
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/profile/bookmarks/delete', name: 'api_delete_clothing_list', requirements: ['_format' => 'json'], methods: ['POST'])]
    public function delete(Request $request): Response
    {
        $data = $request->toArray();
        if (!isset($data['bookmarkId'])) {
            throw new BadRequestHttpException('Missing required parameters');
        }
        /** @var User $user */
        $user = $this->getUser();
        $this->clothingListRepository->delete($data['bookmarkId'], $user->getId());
        return new JsonResponse();
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/profile/bookmarks/{id}', name: 'app_user_clothing_list')]
    public function clothingList(ClothingList $clothingList, SerializerInterface $serializer): Response
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

    #[IsGranted('ROLE_USER')]
    #[Route('/profile/bookmarks/{bookmarkId}/remove-item', name: 'api_remove_bookmark_item', requirements: ['_format' => 'json'], methods: ['POST'])]
    public function removeItem(Request $request, string $bookmarkId): Response
    {
        $data = $request->toArray();
        if (!isset($data['clothingId'])) {
            throw new BadRequestHttpException('Missing required parameters');
        }
        /** @var User $user */
        $user = $this->getUser();
        $this->clothingListRepository->removeClothing($data['clothingId'], (int) $bookmarkId, $user->getId());

        return new JsonResponse();
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/clothing/{clothingId}/add-to-bookmarks', name: 'api_add_clothing_to_clothing_list_modal', requirements: ['_format' => 'html'], methods: ['GET'])]
    public function renderAddElementModal(string $clothingId): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $clothingList = $user->getClothingLists()->toArray();
        return $this->render('clothing_list/modal.html.twig', [
            'clothingList' => $clothingList,
            'clothingId' => $clothingId,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/clothing/{clothingId}/add-to-bookmarks', name: 'api_add_clothing_to_clothing_list', methods: ['POST'])]
    public function addElement(Request $request, string $clothingId): Response
    {
        $data = json_decode($request->getContent(), true);
        $collection = $data['collection'] ?? null;
        if (!$collection) {
            throw new BadRequestHttpException('Missing required parameters');
        }

        /** @var User $user */
        $user = $this->getUser();

        $this->clothingListRepository->addClothing((int) $clothingId, (int) $collection, $user->getId());
        return new JsonResponse();
    }
}
