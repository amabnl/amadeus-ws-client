<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * EmailAddress
 *
 * Email contact (EmailAddressType).
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class EmailAddress
{
    /**
     * @var string|null
     */
    public $LabelText;

    /**
     * @var string
     */
    public $EmailAddressText;

    /**
     * @param string $emailAddressText
     * @param string|null $labelText
     */
    public function __construct($emailAddressText, $labelText = null)
    {
        if ($labelText !== null) {
            $this->LabelText = $labelText;
        }

        $this->EmailAddressText = $emailAddressText;
    }
}
