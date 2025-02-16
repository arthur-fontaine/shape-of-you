<?php

namespace App\Controller;

use App\Entity\Clothing;
use App\Entity\ClothingLink;
use App\Entity\ClothingList;
use App\Repository\ClothingListRepository;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class ClothingListController extends AbstractController
{
    #[Route('/bookmark', name: 'app_user_bookmark')]
    public function bookmark(): Response
    {
        $user = $this->getUser();
        return $this->render('user/bookmark.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/bookmark/{id}', name: 'app_user_clothing_list')]
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

    #[Route('/bookmark/delete/{id}/{idClothing}', name: 'app_user_clothing_list_delete_element', methods: ['DELETE'])]
    public function deleteElement(ClothingList $clothingList, int $clothingId, ClothingListRepository $clothingListRepository): Response
    {
        $clothing = $clothingListRepository->find($clothingId);

        if ($clothing) {
            $clothingListRepository->removeClothing($clothing);
        }

        return $this->redirectToRoute('app_user_clothing_list', ['id' => $clothingList->getId()]);
    }

    #[Route('/bookmark/add/{id}', name: 'app_user_clothing_list_delete', methods: ['GET'])]
    public function addElement(Clothing $clothing): Response
    {
        $clothingList = $this->getUser()->getClothingLists()->toArray();
        return $this->render('clothing_list/modal.html.twig', [
            'clothingList' => $clothingList,
            'clothingId' => $clothing->getId()
        ]);
    }
}
