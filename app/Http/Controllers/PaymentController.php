<?php
// controllers/PaymentController.php
namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Gateways\StripeGateway;
use App\Gateways\PayPalGateway;
use App\Helpers\Response;
use App\Http\Request;
use App\Interfaces\PaymentGatewayInterface;

// use Stripe\PaymentIntent;

class PaymentController extends Controller {

    public function processPayment(Request $request) 
    {
        $data = $request->all();

        // Validate payment details
        $this->validatePayment($data);

        // Example configuration or dynamic selection logic
        $gatewayConfig = [
            'type' => 'stripe', // This could be 'paypal' or any other
            'secret_key' => getenv('STRIPE_SECRET_KEY')
        ];

        $gateway = $this->getGateway($gatewayConfig);
        $paymentService = new PaymentService($gateway);
        $response = $paymentService->processPayment($data);

        if ($response['status'] === 'success') {
            Response::json(['message' => 'Payment successful', 'data' => $response['data']], 200);
        } else {
            Response::json(['error' => 'Payment failed', 'message' => $response['message']], 400);
        }
    }

    private function getGateway(array $config): PaymentGatewayInterface {
        switch ($config['type']) {
            case 'stripe':
                $gateway = new StripeGateway();
                break;
            case 'paypal':
                $gateway = new PayPalGateway();
                break;
            default:
                throw new \Exception('Invalid payment gateway type');
        }
        $gateway->initialize($config);
        return $gateway;
    }

    private function validatePayment(array $data): void
    {
        if (empty($data['amount']) || empty($data['payment_method_id'])) {
            Response::json(['error' => 'Missing payment details'], 400);
        }
    }

    public function handleWebhook() 
    {
        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                // Handle the successful payment here
                break;
            default:
                echo 'Received unknown event type';
        }

        http_response_code(200);
    }
}
