<?php

namespace Flexy\Ftwo\Gateways\PaymentGateway\Juno\CreditCardToken;

class DirectCheckout
{
    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var CreditCard
     */
    private $creditCard;

    /**
     * @return string
     */
    public function encryptCreditCard()
    {
        $crypted = '';
        openssl_public_encrypt($this->creditCard->stringDataEncode(), $crypted, $this->publicKey, OPENSSL_PKCS1_OAEP_PADDING);
        return $crypted;
    }

    /**
     * Get the value of creditCard
     */
    public function getCreditCard()
    {
        return $this->creditCard;
    }

    /**
     * Set the value of holderName
     *
     * @return  self
     */
    public function setCreditCard(CreditCard $creditCard)
    {
        $this->creditCard = $creditCard;

        return $this;
    }

    /**
     * Get the value of publicKey
     *
     * @return  string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Set the value of publicKey
     *
     * @param  string  $publicKey
     *
     * @return  self
     */
    public function setPublicKey($publicKey)
    {
        $eol = PHP_EOL;
        $publicKey = str_replace('\\r\\n', $eol, $publicKey);

        $this->publicKey = "-----BEGIN PUBLIC KEY-----{$eol}{$publicKey}{$eol}-----END PUBLIC KEY-----";

        return $this;
    }
}
