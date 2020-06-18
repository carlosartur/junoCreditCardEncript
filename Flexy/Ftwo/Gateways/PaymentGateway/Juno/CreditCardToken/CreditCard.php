<?php

namespace Flexy\Ftwo\Gateways\PaymentGateway\Juno\CreditCardToken;

use Flexy\Ftwo\Gateways\PaymentGateway\CreditCard\CreditCard as CreditCardCreditCard;

class CreditCard
{
    /**
     *
     */
    const CREDITCARD_VALIDATIONS = [
        CreditCardCreditCard::VISA => [
            "cardLength" => 16,
            "cvvLength" => 3,
            "regex" => "/^4/"
        ],
        CreditCardCreditCard::MASTERCARD => [
            "cardLength" => 16,
            "cvvLength" => 3,
            "regex" => "/^(5[1-5]|2(2(2[1-9]|[3-9])|[3-6]|7([0-1]|20)))/"
        ],
        CreditCardCreditCard::AMEX => [
            "cardLength" => 15,
            "cvvLength" => 4,
            "regex" => "/^3[47]/"
        ],
        CreditCardCreditCard::DISCOVER => [
            "cardLength" => 16,
            "cvvLength" => 3,
            "regex" => "/^6(?:011\d{12}|5\d{14}|4[4-9]\d{13}|22(?:1(?:2[6-9]|[3-9]\d)|[2-8]\d{2}|9(?:[01]\d|2[0-5]))\d{10})/"
        ],
        CreditCardCreditCard::HIPERCARD => [
            "cardLength" => 16,
            "cvvLength" => 3,
            "regex" => "/^606282|384100|384140|384160/"
        ],
        CreditCardCreditCard::DINERS => [
            "cardLength" => 14,
            "cvvLength" => 3,
            "regex" => "/^(300|301|302|303|304|305|36|38)/"
        ],
        CreditCardCreditCard::JCB => [
            15 => [
                "cardLength" => 15,
                "cvvLength" => 3,
                "regex" => "/^2131|1800/"
            ],
            16 => [
                "cardLength" => 16,
                "cvvLength" => 3,
                "regex" => "/^35(?:2[89]|[3-8]\d)/"
            ]
        ],
        CreditCardCreditCard::ELO => [
            "cardLength" => 16,
            "cvvLength" => 3,
            "regex" => "/^(4011(78|79)|43(1274|8935)|45(1416|7393|763(1|2))|50(4175|6699|67([0-6][0-9]|7[0-8])|9\d{3})|627780|63(6297|6368)|650(03([^4])|04([0-9])|05(0|1)|4(0[5-9]|(1|2|3)[0-9]|8[5-9]|9[0-9])|5((3|9)[0-8]|4[1-9]|([0-2]|[5-8])\d)|7(0\d|1[0-8]|2[0-7])|9(0[1-9]|[1-6][0-9]|7[0-8]))|6516(5[2-9]|[6-7]\d)|6550(2[1-9]|5[0-8]|(0|1|3|4)\d))\d*/"
        ],
        CreditCardCreditCard::AURA => [
            "cardLength" => 19,
            "cvvLength" => 3,
            "regex" => "/^((?!5066|5067|50900|504175|506699)50)/"
        ]
    ];

    /**
     * @var String
     */
    const MIN_CVV_LENGTH = 3;

    /**
     * @var String
     */
    private $cardNumber;

    /**
     * @var String
     */
    private $holderName;

    /**
     * @var String
     */
    private $securityCode;

    /**
     * @var String
     */
    private $expirationMonth;

    /**
     * @var String
     */
    private $expirationYear;

    /**
     * @var String
     */
    private $brand;

    /**
     * @var String
     */
    private $validators;

    /**
     * Set the value of cardNumber
     *
     * @return  self
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;

        $this->setValidators();

        return $this;
    }

    /**
     * Get the value of holderName
     */
    public function getHolderName()
    {
        return $this->holderName;
    }

    /**
     * Set the value of holderName
     *
     * @return  self
     */
    public function setHolderName($holderName)
    {
        $this->holderName = $holderName;

        return $this;
    }

    /**
     * Set the value of securityCode
     *
     * @return  self
     */
    public function setSecurityCode($securityCode)
    {
        $this->securityCode = $securityCode;

        return $this;
    }

    /**
     * Set the value of expirationMonth
     *
     * @return  self
     */
    public function setExpirationMonth($expirationMonth)
    {
        $this->expirationMonth = $expirationMonth;

        return $this;
    }

    /**
     * Set the value of expirationYear
     *
     * @return  self
     */
    public function setExpirationYear($expirationYear)
    {
        $this->expirationYear = $expirationYear;

        return $this;
    }

    /**
     * @return void
     */
    private function validateExpirationTime()
    {
        if (!$this->expirationYear || !$this->expirationMonth) {
            throw new \Exception("Expiration year or month of Credit card missing.");
        }
        $currentTime = new \DateTime();
        $currentYear = (int) $currentTime->format('Y');
        $currentMonth = (int) $currentTime->format('n');

        $isYearExpired = ($this->expirationYear < $currentYear);
        $isMonthExpired = ($this->expirationYear == $currentYear && $this->expirationMonth < $currentMonth);

        if ($isYearExpired || $isMonthExpired) {
            throw new \Exception("Credit card expired.");
        }
    }

    /**
     * @return void
     */
    private function validateNumber()
    {
        $number = preg_replace('/[^\d]/', '', $this->cardNumber);
        if (!$this->checksum($number)) {
            throw new \Exception("Invalid card number.");
        }

        $regex = $this->validators["regex"];
        $length = $this->validators["cardLength"];

        if (!preg_match($regex, $number)) {
            throw new \Exception("Credit card of brand [{$this->brand}] number does not match with regex");
        }

        if (strlen($number) !== $length) {
            throw new \Exception("Credit card of brand [{$this->brand}] number does not match with length of [{$length}] chars");
        }
    }

    /**
     * @return void
     */
    private function validateCvv()
    {
        $cvv = preg_replace('/[^\d]/', '', $this->securityCode);
        $length = $this->validators["cvvLength"];
        if (strlen($cvv) !== $this->validators["cvvLength"]) {
            throw new \Exception("Credit card of brand [{$this->brand}] cvv does not match with length of [{$length}] chars");
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $card_number
     * @return void
     */
    private function checksum($cardNumber)
    {
        $cardNumberChecksum = '';

        foreach (str_split(strrev((string) $cardNumber)) as $i => $d) {
            $cardNumberChecksum .= $i % 2 !== 0 ? $d * 2 : $d;
        }

        return array_sum(str_split($cardNumberChecksum)) % 10 === 0;
    }

    /**
     * @return void
     */
    public function validations()
    {
        $this->validateExpirationTime();
        $this->validateNumber();
        $this->validateCvv();
    }

    /**
     * Get the value of brand
     *
     * @return  String
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set the value of brand
     *
     * @param  String  $brand
     *
     * @return  self
     */
    public function setBrand(String $brand)
    {
        $this->brand = $brand;

        $this->setValidators();

        return $this;
    }

    /**
     * Define validations
     *
     * @return void
     */
    private function setValidators()
    {
        if (!$this->brand || !$this->cardNumber) {
            return $this;
        }

        if (!array_key_exists($this->brand, self::CREDITCARD_VALIDATIONS)) {
            throw new \Exception("Credit card brand {$this->brand} is not valid for Juno gateway.");
        }

        $this->validators = self::CREDITCARD_VALIDATIONS[$this->brand];

        if (count($this->validators) !== count($this->validators, COUNT_RECURSIVE)) {
            $number = preg_replace('/[^\d]/', '', $this->cardNumber);
            $numberLength = strlen($number);
            if (!array_key_exists($numberLength, $this->validators)) {
                throw new \Exception("Credit card brand [{$this->brand}] has a invalid card number length of [{$numberLength}]");
            }
            $this->validators = $this->validators[$numberLength];
        }
    }

    /**
     * @return array
     */
    public function stringDataEncode()
    {
        $array = [];
        $cardDataString = json_encode($this->getThisArray());
        for ($i = 0; $i < strlen($cardDataString); $i++) {
            $array[$i] = $this->getUtf8CharCode($cardDataString[$i]);
        }
        return json_encode($array); // return (new JunoEncode())->doEncode($array);
    }

    /**
     * Undocumented function
     *
     * @param string $string
     * @return integer
     */
    public function getUtf8CharCode($string)
    {
        try {
            return unpack('V', iconv('UTF-8', 'UCS-4LE', $string))[1];
        } catch (\Exception $exeption) {
            return 0;
        }
    }

    /**
     * @return array
     */
    private function getThisArray()
    {
        $ignoreAttrs = ['brand', 'validators'];
        $array = [];
        foreach ($this as $key => $value) {
            if (in_array($key, $ignoreAttrs)) {
                continue;
            }
            $array[$key] = $value;
        }
        return $array;
    }
}
