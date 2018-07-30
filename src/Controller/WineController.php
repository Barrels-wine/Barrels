<?php

declare(strict_types=1);

namespace App\Controller;

use App\Annotation\JsonBody;
use App\Entity\Bottle;
use App\Entity\Wine;
use App\HttpFoundation\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WineController extends BaseController
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
            'results' => $wines,
        ]);
    }

    /**
     * @Route("/bottles/count", name="bottles_count", methods={"GET"})
     */
    public function countBottles(): ApiResponse
    {
        $count = $this
            ->getDoctrine()
            ->getRepository(Bottle::class)
            ->count([])
        ;

        return new ApiResponse([
            'count' => $count,
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

    /**
     * @Route("/wines", name="wine", methods={"POST"})
     * @JsonBody
     */
    public function createWine(Wine $wine, ValidatorInterface $validator): ApiResponse
    {
        $em = $this->getDoctrine()->getManager();

        $this->validate($validator, $wine);

        $em->persist($wine);
        $em->flush();
        $em->refresh($wine);

        return new ApiResponse($wine, Response::HTTP_CREATED);
    }
}
