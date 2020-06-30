<?php

namespace Flexy\Ftwo\Gateways\PaymentGateway\Juno\CreditCardToken;

use phpseclib\Crypt\RSA;

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
        try {
            /** @var RSA */
            $rsaObj = $this->createRsaCryptObject();
            $creditCardString = (string) $this->creditCard;
            $crypted = $rsaObj->encrypt($creditCardString);
            $encoded = base64_encode($crypted);
            return $encoded;
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * @return RSA
     */
    public function createRsaCryptObject()
    {
        $rsa = new RSA();
        $rsa->setEncryptionMode(RSA::ENCRYPTION_OAEP);
        $rsa->setHash("sha256");
        // $rsa->setMGFHash("sha256");
        $rsa->setMGFHash("sha1");
        $rsa->loadKey($this->publicKey);
        return $rsa;
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
        // $eol = PHP_EOL;
        // $publicKey = str_replace('\\r\\n', $eol, $publicKey);

        // $this->publicKey = "-----BEGIN PUBLIC KEY-----{$eol}{$publicKey}{$eol}-----END PUBLIC KEY-----";
        $this->publicKey = $publicKey;
        return $this;
    }
}
