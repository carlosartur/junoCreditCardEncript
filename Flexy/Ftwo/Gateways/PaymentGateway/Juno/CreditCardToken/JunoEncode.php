<?php

namespace Flexy\Ftwo\Gateways\PaymentGateway\Juno\CreditCardToken;

class JunoEncode
{
    /**
     * @var array
     */
    const CHARS = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "+", "/"];

    /**
     * @param array $toEncode
     * @return string
     */
    public function doEncode(array $toEncode)
    {
        $base64 = '';
        for ($i = 0; $i < count($toEncode); $i = $i + 3) {
            $valuePositionPlus1 = array_key_exists($i + 1, $toEncode)
                ? $toEncode[$i + 1]
                : null;

            $valuePositionPlus2 = array_key_exists($i + 2, $toEncode)
                ? $toEncode[$i + 2]
                : null;
            $base64 .= self::CHARS[$toEncode[$i] >> 2];
            $base64 .= self::CHARS[(($toEncode[$i] & 3) << 4) | ($valuePositionPlus1 >> 4)];
            $base64 .= self::CHARS[(($valuePositionPlus1 & 15) << 2) | ($valuePositionPlus2 >> 6)];
            $base64 .= self::CHARS[($valuePositionPlus2 & 63)];
        }

        if (count($toEncode) % 3 === 2) {
            $base64 = substr($base64, 0, strlen($base64) - 1) . "=";
        }

        if (count($toEncode) % 3 === 1) {
            $base64 = substr($base64, 0, strlen($base64) - 2) . "==";
        }
        return $base64;
    }
}
