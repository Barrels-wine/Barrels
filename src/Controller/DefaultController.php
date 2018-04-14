<?php

namespace App\Controller;

use App\Entity\Wine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        $wines = $this
            ->getDoctrine()
            ->getRepository(Wine::class)
            ->findAll()
        ;

        return $this->render('default/index.html.twig', [
            'wines' => $wines,
        ]);
    }
}
