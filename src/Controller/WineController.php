<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Wine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class WineController extends AbstractController
{
    /**
     * @Route("/wines", name="wines_list", methods={"GET"})
     */
    public function getWines(): JsonResponse
    {
        $wines = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Wine')
            ->findAll()
        ;

        return new JsonResponse([
            'wines' => $wines,
        ]);
    }

    /**
     * @Route("/wines/{wineId}", requirements={"wineId" = "\d+"}, name="wine", methods={"GET"})
     */
    public function getWine(Wine $wine): JsonResponse
    {
        if (!$wine) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($wine);
    }
}
