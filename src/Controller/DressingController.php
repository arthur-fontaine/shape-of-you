<?php

namespace App\Controller;

use App\Entity\Clothing;
use App\Entity\ClothingLink;
use App\Entity\ClothingList;
use App\Entity\DressingPiece;
use App\Repository\DressingPieceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    #[Route('/dressing', name: 'app_dressing')]
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

    #[Route('/dressing/remove', name: 'app_dressing_piece_remove', requirements: ['_format' => 'json'], methods: ['POST'])]
    public function removeDressingPiece(Request $request): Response
    {
        $data = $request->toArray();


        if (!isset($data['clothingId'])) {
            throw new BadRequestHttpException('dressingPieceId is required');
        }

        $this->dressingPieceRepository->removeDressingPiece($data['clothingId'], $this->getUser());

        return new Response();
    }

    #[Route('/dressing/new/{id}', name: 'app_dressing_piece_new_render', requirements: ['_format' => 'html'], methods: ['GET'])]
    public function newDressingPieceRender(Clothing $clothing, SerializerInterface $serializer): Response
    {
        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, ?string $format, array $context): string {
                if (!$object instanceof Clothing && !$object instanceof ClothingLink && !$object instanceof ClothingList && !$object instanceof DressingPiece) {
                    throw new CircularReferenceException('A circular reference has been detected when serializing the object of class "'.get_debug_type($object).'".');
                }

                return '';
            },
        ];
        return $this->render('dressing/new.html.twig',
            [
                'clothing' => json_decode($serializer->serialize($clothing, 'json', $context)),
            ]);
    }

    #[Route('/dressing/new', name: 'app_dressing_piece_new', requirements: ['_format' => 'json'], methods: ['POST'])]
    public function newDressingPiece(Request $request): Response
    {
        $data = $request->request->all();

        if (!isset($data['clothingId'])) {
            throw new BadRequestHttpException('clothingId is required');
        }

        $dressing =  $this->dressingPieceRepository->createDressingPiece($data, $this->getUser());

        return new Response();
    }
}
