<?php
// interfaces/PaymentGatewayInterface.php
namespace App\Interfaces;

interface PaymentGatewayInterface {
    public function initialize(array $config): void;
    public function processPayment(array $paymentDetails): array;
    public function refundPayment(string $transactionId, float $amount): array;
}
