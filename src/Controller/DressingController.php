<?php

namespace App\Controller;

use App\Entity\Clothing;
use App\Entity\ClothingLink;
use App\Entity\ClothingList;
use App\Entity\DressingPiece;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;


final class DressingController extends AbstractController
{
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
    public function renderNewDressingPiece(Clothing $clothing): Response
    {
        $clothingId = $clothing->getId();
        return $this->render('dressing/new.html.twig', [
            'clothingId' => $clothingId,
        ]);
    }
}
