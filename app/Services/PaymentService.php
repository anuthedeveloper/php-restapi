<?php
// services/PaymentService.php
namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;

class PaymentService {
    private PaymentGatewayInterface $gateway;

    public function __construct(PaymentGatewayInterface $gateway) {
        $this->gateway = $gateway;
    }

    public function processPayment(array $paymentDetails): array {
        return $this->gateway->processPayment($paymentDetails);
    }

    public function refundPayment(string $transactionId, float $amount): array {
        return $this->gateway->refundPayment($transactionId, $amount);
    }
}
