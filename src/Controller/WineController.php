<?php

declare(strict_types=1);

namespace App\Controller;

use App\Annotation\JsonBody;
use App\Entity\Bottle;
use App\Entity\Wine;
use App\HttpFoundation\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WineController extends BaseController
{
    /**
     * @Route("/wines", name="get_wines_list", methods={"GET"})
     */
    public function getWines(): ApiResponse
    {
        $wines = $this
            ->getDoctrine()
            ->getRepository(Wine::class)
            ->findBy([], ['createdAt' => 'DESC']);

        return new ApiResponse([
            'results' => $wines,
        ]);
    }

    /**
     * @Route("/bottles/count", name="get_bottles_count", methods={"GET"})
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
     * @Route("/wines/{id}", name="get_wine", methods={"GET"})
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
     * @Route("/wines", name="create_wine", methods={"POST"})
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

    /**
     * @Route("/bottles", name="create_bottle", methods={"POST"})
     * @JsonBody
     */
    public function createBottle(Bottle $bottle, Request $request, ValidatorInterface $validator): ApiResponse
    {
        $nbBottles = $request->query->get('nb', 1);
        dump($nbBottles);
        $em = $this->getDoctrine()->getManager();

        $this->validate($validator, $bottle);

        $wine = $bottle->getWine();
        if (!$wine->getId()) {
            $em->persist($wine);
        }

        for ($i = 0; $i < $nbBottles; ++$i) {
            $clone = clone $bottle;
            $em->persist($clone);
        }
        $em->flush();
        $em->refresh($wine);

        return new ApiResponse($wine, Response::HTTP_CREATED);
    }
}
