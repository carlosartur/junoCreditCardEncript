<?php

namespace Flexy\Ftwo\Gateways\PaymentGateway\Juno\CreditCardToken;

use GuzzleHttp\Client;

class DirectCheckoutFactory
{
    /**
     * @var string
     */
    const JUNO_API_VERSION = "0.0.2";

    /**
     * @var string
     */
    const SANDBOX_URL = 'https://sandbox.boletobancario.com/boletofacil/integration/api/';

    /**
     * @var string
     */
    const PRODUCTION_URL = 'https://www.boletobancario.com/boletofacil/integration/api/';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var stdClass
     */
    private $request;

    /**
     * @var boolean
     */
    private $production;

    /**
     * @var string
     */
    private $publicToken;

    /**
     * @var DirectCheckout
     */
    private $directCheckout;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return DirectCheckout
     */
    public function createDirectCheckout()
    {
        if (!$this->request) {
            throw new \Exception('Request missing to create credit card token.');
        }

        $publicKey = $this->loadPublicKey();
        $this->directCheckout = (new DirectCheckout())
            ->setCreditCard($this->createCreditCard())
            ->setPublicKey($publicKey);
        return $this->directCheckout;
    }

    /**
     * @return CreditCard
     */
    public function createCreditCard()
    {
        $requestCreditCard = reset($this->request->paymentMethods->creditCard);
        $creditCard = (new CreditCard())
            ->setCardNumber($requestCreditCard->number)
            ->setExpirationMonth($requestCreditCard->expirationMonth)
            ->setExpirationYear($requestCreditCard->expirationYear)
            ->setSecurityCode($requestCreditCard->cvv)
            ->setHolderName($requestCreditCard->holder->name)
            ->setBrand($requestCreditCard->brand);
        $creditCard->validations();
        return $creditCard;
    }

    /**
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request;
        $this->publicToken = $this->request->auth->public_token;
        return $this;
    }

    /**
     * @param boolean $production
     * @return self
     */
    public function setProduction($production)
    {
        $this->production = $production;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        if ($this->production) {
            return self::PRODUCTION_URL;
        }
        return self::SANDBOX_URL;
    }

    /**
     * @return string
     */
    public function loadPublicKey()
    {
        $body = http_build_query([
            "publicToken" => $this->publicToken,
            "version" => self::JUNO_API_VERSION
        ]);

        $headers = [
            "content-type" => "application/x-www-form-urlencoded"
        ];

        $response = $this->client->request(
            'POST',
            "{$this->getUrl()}get-public-encryption-key.json",
            compact('body', 'headers')
        );

        $response = json_decode((string) $response->getBody());
        if (!isset($response->success) || !$response->success) {
            throw new \Exception("Try to get public key returned a error. Message [$response->errorMessage]. Body [$body].");
        }
        return $response->data;
    }

    /**
     * @return string
     */
    public function generateCreditCardHash()
    {
        if (!$this->directCheckout) {
            $this->createDirectCheckout();
        }

        $encryptedData = urlencode($this->directCheckout->encryptCreditCard());

        $body = http_build_query([
            "publicToken" => $this->publicToken,
            "encryptedData" => $encryptedData
        ]);

        $headers = [
            "content-type" => "application/x-www-form-urlencoded"
        ];
        
        $response = $this->client->request(
            'POST',
            "{$this->getUrl()}get-credit-card-hash.json",
            compact('body', 'headers')
        );

        $response = json_decode((string) $response->getBody());
        if (!isset($response->success) || !$response->success) {
            $response = json_encode($response);
            throw new \Exception("Failed to get credit card Hash. Response of request was: {$response}");
        }
        return $response->data;
    }
}
