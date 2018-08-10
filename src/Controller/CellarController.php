<?php

declare(strict_types=1);

namespace App\Controller;

use App\Annotation\JsonBody;
use App\Entity\Storage;
use App\HttpFoundation\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CellarController extends BaseController
{
    /**
     * @Route("/storage_locations", name="get_storage_locations_list", methods={"GET"})
     */
    public function getStorageLocations(): ApiResponse
    {
        $locations = $this
            ->getDoctrine()
            ->getRepository(Storage::class)
            ->findAll()
        ;

        return new ApiResponse([
            'results' => $locations,
        ]);
    }

    /**
     * @Route("/storage_location/{id}", name="get_storage_location", methods={"GET"})
     */
    public function getStorageLocation(string $id): ApiResponse
    {
        $location = $this
            ->getDoctrine()
            ->getRepository(Storage::class)
            ->find($id)
        ;

        if (!$location) {
            throw new NotFoundHttpException();
        }

        return new ApiResponse($location);
    }

    /**
     * @Route("/storage_locations", name="create_storage_location", methods={"POST"})
     * @JsonBody
     */
    public function createStorageLocation(Storage $location, ValidatorInterface $validator): ApiResponse
    {
        $em = $this->getDoctrine()->getManager();

        $this->validate($validator, $location);

        $em->persist($location);
        $em->flush();

        return new ApiResponse($location, Response::HTTP_CREATED);
    }

    /**
     * @Route("/storage_locations/{id}", name="update_storage_location", methods={"PUT"})
     * @JsonBody
     */
    public function updateStorageLocation(Storage $location, string $id, ValidatorInterface $validator): ApiResponse
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this
            ->getDoctrine()
            ->getRepository(Storage::class)
            ->find($id)
        ;

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $this->validate($validator, $location);

        $entity->update($location);

        $em->flush();

        return new ApiResponse($location, Response::HTTP_OK);
    }
}
