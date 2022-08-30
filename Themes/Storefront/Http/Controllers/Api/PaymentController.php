<?php
namespace Themes\Storefront\Http\Controllers\Api;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
class PaymentController
{
 public function mobilepayment(Request $request)
 {
\Stripe\Stripe::setApiKey('sk_test_51IUTxCKklycvAUjbIIMj2Vo1ml9M4Ld8Tjq3OW3tCkbOKJf8uBLCNS7oNVC6gIU6uTMOE0sQsM9plBu7qtg72mUb00pQW2vLT4');


header('Content-Type: application/json');

try {

  $paymentIntent = \Stripe\PaymentIntent::create([
    'amount' => $request->amount,
            'currency' =>'usd',
  ]);

  $output = [
    'clientSecret' => $paymentIntent->client_secret,
  ];

  echo json_encode($output);
} catch (Error $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
  }
 }
}
