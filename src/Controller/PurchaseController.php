<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/purchase', name: 'purchase', methods: [Request::METHOD_POST])]
class PurchaseController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse();
    }
}
