<?php

declare(strict_types=1);

namespace App\Controller;

use App\CalculatePrice\CalculatePriceWorkflow;
use App\DTO\CalculatePriceDTO;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/calculate-price', name: 'calculate-price', methods: [Request::METHOD_POST])]
#[AsController]
class CalculateController
{
    public function __construct(private readonly CalculatePriceWorkflow $workflow)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = new CalculatePriceDTO(...$request->toArray());

            $result = $this->workflow->calculatePrice($data);

            return new JsonResponse($result);
        } catch (Exception $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
