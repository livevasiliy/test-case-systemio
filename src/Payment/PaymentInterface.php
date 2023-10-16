<?php

namespace App\Payment;

interface PaymentInterface
{
    public function pay(float $amount): void;
}