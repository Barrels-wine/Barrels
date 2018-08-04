<?php

declare(strict_types=1);

namespace App\Controller;

use App\HttpFoundation\ApiResponse;
use App\Reference\Designations;
use App\Reference\FrenchRegions;
use App\Reference\Varietals;
use Symfony\Component\Routing\Annotation\Route;

class ReferenceController extends BaseController
{
    /**
     * @Route("/references", name="references", methods={"GET"})
     */
    public function getReferences(): ApiResponse
    {
        return new ApiResponse([
            'varietals' => Varietals::getConstants(),
            'frenchRegions' => FrenchRegions::getConstants(),
            'designations' => Designations::getAll(),
        ]);
    }
}
