<?php
// gateways/StripeGateway.php
namespace App\Gateways;

use App\Interfaces\PaymentGatewayInterface;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeGateway implements PaymentGatewayInterface {
    public function initialize(array $config): void {
        Stripe::setApiKey($config['secret_key']);
    }

    public function processPayment(array $paymentDetails): array {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $paymentDetails['amount'],
                'currency' => 'usd',
                'payment_method' => $paymentDetails['payment_method_id'],
                'confirmation_method' => 'manual',
                'confirm' => true,
            ]);
    
            return [
                'status' => 'success',
                'message' => 'Payment processed successfully',
                'data' => $paymentIntent->toArray()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'failure',
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function refundPayment(string $transactionId, float $amount): array {
        // Refund logic for Stripe
        try {
            $refund = \Stripe\Refund::create([
                'charge' => $transactionId,
                'amount' => $amount,
            ]);

            return [
                'status' => 'success',
                'message' => 'Refund processed successfully',
                'data' => $refund->toArray()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'failure',
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }
}
