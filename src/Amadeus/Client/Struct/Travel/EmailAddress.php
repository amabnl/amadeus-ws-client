<?php

namespace Amadeus\Client\Struct\Travel;

/**
 * EmailAddress
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class EmailAddress
{
    /**
     * @var string
     */
    public $LabelText;

    /**
     * @var string
     */
    public $EmailAddressText;

    /**
     * @param string $labelText
     * @param string $emailAddressText
     */
    public function __construct($labelText, $emailAddressText)
    {
        $this->LabelText = $labelText;
        $this->EmailAddressText = $emailAddressText;
    }
}
