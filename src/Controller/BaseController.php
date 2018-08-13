<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ValidationException;
use App\HttpFoundation\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractController
{
    protected function validate(ValidatorInterface $validator, $entity, $groups = null, $constraints = null)
    {
        $violations = $validator->validate($entity, $constraints, $groups);
        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }
    }

    protected function getList(string $class, array $criteria = [], array $orderBy = null, int $limit = null, int $offset = null)
    {
        $results = $this
            ->getDoctrine()
            ->getRepository($class)
            ->findBy($criteria, $orderBy, $limit, $offset)
        ;

        return new ApiResponse([
            'results' => $results,
        ]);
    }

    protected function getEntity(string $id, string $class): ApiResponse
    {
        $entity = $this
            ->getDoctrine()
            ->getRepository($class)
            ->find($id)
        ;

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        return new ApiResponse($entity);
    }

    protected function createEntity($entity, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        $this->validate($validator, $entity);

        $em->persist($entity);
        $em->flush();

        return new ApiResponse($entity, Response::HTTP_CREATED);
    }

    protected function updateEntity($data, string $id, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this
            ->getDoctrine()
            ->getRepository(get_class($data))
            ->find($id)
        ;

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $entity->update($data);

        $this->validate($validator, $entity);

        $em->flush();

        return new ApiResponse($entity, Response::HTTP_OK);
    }

    protected function deleteEntity(string $id, string $class)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this
            ->getDoctrine()
            ->getRepository($class)
            ->find($id)
        ;

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $em->remove($entity);
        $em->flush();

        return new ApiResponse([], Response::HTTP_NO_CONTENT);
    }
}
