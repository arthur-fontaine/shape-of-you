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

    #[Route('/dressing/add/{id}', name: 'app_dressing_piece', requirements: ['id' => '\d+', '_format' => 'html'], methods: ['GET'])]
    public function renderNewDressingPiece(Clothing $clothing, Request $request): Response
    {
        $clothingId = $clothing->getId();
        return $this->render('dressing/new.html.twig', [
            'clothing' => $clothing,
        ]);
    }

    #[Route('/dressing/add', name: 'app_dressing_add_piece', requirements: ['_format' => 'json'], methods: ['POST'])]
    public function addDressingPiece(Request $request): Response
    {
        $data = $request->request->all();

        if (!isset($data['clothingId'])) {
            throw new BadRequestHttpException('clothingId is required');
        }

        $dressingPiece = $this->dressingPieceRepository->createDressingPiece($data, $this->getUser());


        return new Response();
    }
}
