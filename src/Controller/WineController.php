<?php

declare(strict_types=1);

namespace App\Controller;

use App\Annotation\JsonBody;
use App\Entity\Bottle;
use App\Entity\Wine;
use App\HttpFoundation\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WineController extends BaseController
{
    /**
     * @Route("/wines", name="get_wines_list", methods={"GET"})
     */
    public function getWines(): ApiResponse
    {
        return $this->getList(Wine::class, [], ['createdAt' => 'DESC']);
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
        return $this->getEntity($id, Wine::class);
    }

    /**
     * @Route("/wines", name="create_wine", methods={"POST"})
     * @JsonBody
     */
    public function createWine(Wine $wine, ValidatorInterface $validator): ApiResponse
    {
        return $this->createEntity($wine, $validator);
    }

    /**
     * @Route("/wines/{id}", name="update_wine", methods={"PUT"})
     * @JsonBody
     */
    public function updateWine(Wine $wine, string $id, ValidatorInterface $validator): ApiResponse
    {
        return $this->updateEntity($wine, $id, $validator);
    }

    /**
     * @Route("/bottles", name="create_bottle", methods={"POST"})
     * @JsonBody
     */
    public function createBottle(Bottle $bottle, Request $request, ValidatorInterface $validator): ApiResponse
    {
        $nbBottles = $request->query->get('nb', 1);
        $em = $this->getDoctrine()->getManager();

        $this->validate($validator, $bottle);

        $wine = $bottle->getWine();
        if (!$wine->getId()) {
            $em->persist($wine);
        }

        $storage = $bottle->getStorageLocation();
        if (!$storage->getId()) {
            $em->persist($storage);
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
