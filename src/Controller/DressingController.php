<?php

namespace App\Controller;

use App\Entity\Clothing;
use App\Entity\ClothingLink;
use App\Entity\ClothingList;
use App\Entity\DressingPiece;
use App\Repository\DressingPieceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;


final class DressingController extends AbstractController
{
    private DressingPieceRepository $dressingPieceRepository;

    public function __construct(DressingPieceRepository $dressingPieceRepository)
    {
        $this->dressingPieceRepository = $dressingPieceRepository;
    }
    #[Route('/profile/dressing', name: 'app_dressing')]
    public function index(SerializerInterface $serializer): Response
    {
        if (!$this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, ?string $format, array $context): string {
                if (!$object instanceof Clothing && !$object instanceof ClothingLink && !$object instanceof ClothingList && !$object instanceof DressingPiece) {
                    throw new CircularReferenceException('A circular reference has been detected when serializing the object of class "'.get_debug_type($object).'".');
                }

                return '';
            },
        ];
        return $this->render('dressing/index.html.twig', [
            'dressing' => json_decode($serializer->serialize($this->getUser()->getDressing()->toArray(), 'json', $context)),
        ]);
    }

    #[Route('/profile/dressing/remove-item', name: 'app_dressing_piece_remove', requirements: ['_format' => 'json'], methods: ['POST'])]
    public function removeDressingPiece(Request $request): Response
    {
        $data = $request->toArray();


        if (!isset($data['clothingId'])) {
            throw new BadRequestHttpException('dressingPieceId is required');
        }

        $this->dressingPieceRepository->removeDressingPiece($data['clothingId'], $this->getUser());

        return new Response();
    }

    #[Route('/clothing/{clothingId}/upsert-to-dressing', name: 'api_dressing_piece_upsert', requirements: ['_format' => 'json'], methods: ['POST'])]
    public function upsertDressingPiece(Request $request, string $clothingId): Response
    {
        $data = ['clothingId' => $clothingId];
        if (!empty($request->getContent())) {
            $data = array_merge($request->toArray(), $data);
        }
        $this->dressingPieceRepository->upsertDressingPiece($data, $this->getUser());
        return new JsonResponse();
    }

    #[Route('/clothing/{clothingId}/add-to-dressing', name: 'api_dressing_piece_add', requirements: ['_format' => 'json'], methods: ['POST'])]
    public function addDressingPiece(Request $request, string $clothingId): Response
    {
        $data = ['clothingId' => $clothingId];
        if (!empty($request->getContent())) {
            $data = array_merge($request->toArray(), $data);
        }
        $this->dressingPieceRepository->upsertDressingPiece($data, $this->getUser());
        return new JsonResponse();
    }

    #[Route('/clothing/{clothingId}/remove-from-dressing', name: 'api_dressing_piece_remove', requirements: ['_format' => 'json'], methods: ['POST'])]
    public function removeDressingPieceByClothingId(Request $request, string $clothingId): Response
    {
        $data = ['clothingId' => $clothingId];
        if (!empty($request->getContent())) {
            $data = array_merge($request->toArray(), $data);
        }
        $this->dressingPieceRepository->removeDressingPiece($data['clothingId'], $this->getUser());
        return new JsonResponse();
    }
}
