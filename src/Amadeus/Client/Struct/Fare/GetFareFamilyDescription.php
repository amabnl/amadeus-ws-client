<?php

namespace Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\DocRefund\Reference;
use Amadeus\Client\RequestOptions\FareGetFareFamilyDescriptionOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\ReferenceInformation;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription\BookingDateInformation;

/**
 * Class GetFareFamilyDescription
 * @package Amadeus\Client\Struct\Fare
 */
class GetFareFamilyDescription extends BaseWsMessage
{
    /**
     * @var ReferenceInformation
     */
    private $referenceInformation;

    /**
     * @var BookingDateInformation
     */
    private $bookingDateInformation;

    /**
     * GetFareFamilyDescription constructor.
     *
     * @param FareGetFareFamilyDescriptionOptions|null $options
     */
    public function __construct($options)
    {
        $this->referenceInformation = new ReferenceInformation(
            [
                new Reference([
                    'type' => 'FC',
                    'value' => 1
                ])
            ]
        );
//        $this->bookingDateInformation = new BookingDateInformation(new DateTime(new \DateTime()));
    }

}