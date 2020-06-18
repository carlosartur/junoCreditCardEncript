<?php

namespace Flexy\Ftwo\Gateways\PaymentGateway\CreditCard;

/**
 * @package Flexy\Ftwo\Gateways\PaymentGateway\CreditCard
 * @author Gabriel Felipe Soares <gabriel.soares@flexy.com.br>
 */
class CreditCard
{

    const HIPERCARD = 'payment.method.creditcard.hipercard';
    const OIPAGGO = 'payment.method.creditcard.oipaggo';
    const VISA = 'payment.method.creditcard.visa';
    const VISAELECTRON = 'payment.method.creditcard.visaelectron';
    const MASTERCARD = 'payment.method.creditcard.mastercard';
    const AMEX = 'payment.method.creditcard.americanexpress';
    const ELO = 'payment.method.creditcard.elo';
    const DINERS = 'payment.method.creditcard.dinners';
    const JCB = 'payment.method.creditcard.jcb';
    const DISCOVER = 'payment.method.creditcard.discover';
    const AURA = 'payment.method.creditcard.aura';

    /**
     * @param $number
     * @return string
     */
    public static function mask($number)
    {
        return str_repeat('*', strlen($number) - 4) . substr($number, -4);
    }
}
