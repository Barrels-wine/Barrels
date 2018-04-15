<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Wine;
use App\HttpFoundation\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class WineController extends AbstractController
{
    /**
     * @Route("/wines", name="wines_list", methods={"GET"})
     */
    public function getWines(): ApiResponse
    {
        $wines = $this
            ->getDoctrine()
            ->getRepository(Wine::class)
            ->findAll()
        ;

        return new ApiResponse([
            'wines' => $wines,
        ]);
    }

    /**
     * @Route("/wines/{id}", name="wine", methods={"GET"})
     */
    public function getWine(string $id): ApiResponse
    {
        $wine = $this
            ->getDoctrine()
            ->getRepository(Wine::class)
            ->find($id)
        ;

        if (!$wine) {
            throw new NotFoundHttpException();
        }

        return new ApiResponse($wine);
    }
}
