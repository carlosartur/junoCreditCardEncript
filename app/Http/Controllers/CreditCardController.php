<?php

namespace App\Http\Controllers;

use Flexy\Ftwo\Gateways\PaymentGateway\CreditCard\CreditCard;
use Flexy\Ftwo\Gateways\PaymentGateway\Juno\CreditCardToken\DirectCheckoutFactory;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CreditCardController extends Controller
{
    public function token(Request $request)
    {
        $guzzle = new Client(['url']);
        $requestToSend = json_decode(json_encode([
            "paymentMethods" => [
                "creditCard" => [[
                    "brand" => CreditCard::MASTERCARD,
                    "number" => $request->get('card_number'),
                    "expirationMonth" => $request->get('mes'),
                    "expirationYear" => $request->get('ano'),
                    "cvv" => $request->get('cvv'),
                    "holder" => [
                        "name" => $request->get("card_holder")
                    ]
                ]]
            ],
            "auth" => [
                "public_token" => $request->get("public-token")
            ]
        ]));
        $response = [
            'token' => utf8_encode((new DirectCheckoutFactory($guzzle))
                ->setRequest($requestToSend)
                ->setProduction(false)
                ->generateCreditCardHash())
        ];

        return response()->json($response);
    }
}
