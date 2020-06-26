<?php

namespace App\Http\Controllers;

use Flexy\Ftwo\Gateways\PaymentGateway\CreditCard\CreditCard;
use Flexy\Ftwo\Gateways\PaymentGateway\Juno\CreditCardToken\DirectCheckoutFactory;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CreditCardController extends Controller
{
    /**
     * @param Request $request
     * @return void
     */
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

    /**
     * @param Request $request
     * @return void
     */
    public function getPubKey(Request $request)
    {
        $data = file_get_contents('mykey.pub');
        $success = boolval($data);
        return response()->json(compact('data', 'success'));
    }

    /**
     * @param Request $request
     * @return void
     */
    public function cardHash(Request $request)
    {
        // $data = [
        //     $request->get('encryptedData'),
        //     urldecode($request->get('encryptedData')),
        //     utf8_encode(urldecode($request->get('encryptedData'))),
        //     base64_decode(utf8_encode(urldecode($request->get('encryptedData'))))
        // ];
        $encryptedData = base64_decode(utf8_encode(urldecode($request->get('encryptedData'))));

        $privateKey = file_get_contents('mykey.pem');
        // $envKey = rand() . PHP_EOL . rand();

        $bla = [];

        $data = '';
        // for ($i = 1; $i < 500; $i++) {
        //     $data2 = '';
        //     $bla[] = $i;
        //     foreach (str_split($encryptedData, $i) as $test) {
        //         openssl_private_decrypt($test, $data, $privateKey, OPENSSL_NO_PADDING);
        //         $data2 .= $data;
        //     }
        //     $bla[] = utf8_decode($data2);
        // }
        openssl_private_decrypt($encryptedData, $data, $privateKey, OPENSSL_NO_PADDING);
        // $error = openssl_get_cipher_methods();
        $error = openssl_error_string();
        return implode('<hr>', $bla);
        dd('bla');

        dd(compact('bla', 'encryptedData', 'privateKey', 'data', 'error'));
        return response()->json(compact('encryptedData', 'envKey', 'privateKey', 'request', 'data', 'error'));
    }
}
