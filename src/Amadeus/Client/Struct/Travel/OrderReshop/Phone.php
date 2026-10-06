<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * Phone
 *
 * Telephone contact (PhoneType).
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class Phone
{
    /**
     * @var string|null
     */
    public $LabelText;

    /**
     * @var string
     */
    public $PhoneNumberText;

    /**
     * @param string $phoneNumberText
     * @param string|null $labelText
     */
    public function __construct($phoneNumberText, $labelText = null)
    {
        if ($labelText !== null) {
            $this->LabelText = $labelText;
        }

        $this->PhoneNumberText = $phoneNumberText;
    }
}
