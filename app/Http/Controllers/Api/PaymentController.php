<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServicePrice;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function getPrices()
    {
        return response()->json(ServicePrice::all());
    }

    public function getStripeKey()
    {
        $key = PaymentSetting::where('key', 'stripe_publishable_key')->first();
        return response()->json(['publishable_key' => $key ? $key->value : '']);
    }

    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'required|string',
        ]);

        $secretKey = PaymentSetting::where('key', 'stripe_secret_key')->first();

        if (!$secretKey || empty($secretKey->value)) {
            return response()->json(['error' => 'Stripe secret key not configured.'], 500);
        }

        Stripe::setApiKey($secretKey->value);

        // Fix for intermittent "Errno 7: Bad access" on Windows/XAMPP
        // Forces IPv4 resolution and sets reliable timeouts
        \Stripe\ApiRequestor::setHttpClient(new \Stripe\HttpClient\CurlClient([
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_TIMEOUT => 40,
        ]));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100, // Stripe uses cents
                'currency' => $request->currency,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'id' => $paymentIntent->id,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
