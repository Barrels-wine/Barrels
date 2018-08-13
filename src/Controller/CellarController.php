<?php

declare(strict_types=1);

namespace App\Controller;

use App\Annotation\JsonBody;
use App\Entity\Storage;
use App\HttpFoundation\ApiResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CellarController extends BaseController
{
    /**
     * @Route("/storage_locations", name="get_storage_locations_list", methods={"GET"})
     */
    public function getStorageLocations(): ApiResponse
    {
        return $this->getList(Storage::class);
    }

    /**
     * @Route("/storage_locations/{id}", name="get_storage_location", methods={"GET"})
     */
    public function getStorageLocation(string $id): ApiResponse
    {
        return $this->getEntity($id, Storage::class);
    }

    /**
     * @Route("/storage_locations", name="create_storage_location", methods={"POST"})
     * @JsonBody
     */
    public function createStorageLocation(Storage $location, ValidatorInterface $validator): ApiResponse
    {
        return $this->createEntity($location, $validator);
    }

    /**
     * @Route("/storage_locations/{id}", name="update_storage_location", methods={"PUT"})
     * @JsonBody
     */
    public function updateStorageLocation(Storage $location, string $id, ValidatorInterface $validator): ApiResponse
    {
        return $this->updateEntity($location, $id, $validator);
    }

    /**
     * @Route("/storage_locations/{id}", name="delete_storage_location", methods={"DELETE"})
     */
    public function deleteStorageLocation(string $id): ApiResponse
    {
        return $this->deleteEntity($id, Storage::class);
    }
}
