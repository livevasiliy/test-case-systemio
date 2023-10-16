<?php

namespace App\Controller;

use App\DTO\PurchaseDTO;
use App\Payment\PaymentProcessor;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/purchase', name: 'purchase', methods: [Request::METHOD_POST])]
class PurchaseController
{
    public function __construct(private readonly PaymentProcessor $paymentProcessor)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = new PurchaseDTO(...$request->toArray());
            $this->paymentProcessor->process($data);
            return new JsonResponse(['success' => true]);
        } catch (Exception $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
