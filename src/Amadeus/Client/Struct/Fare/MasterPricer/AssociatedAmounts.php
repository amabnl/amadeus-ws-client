<?php

namespace Amadeus\Client\Struct\Fare\MasterPricer;

use Amadeus\Client\RequestOptions\Fare\MasterPricer\MonetaryDetails as MonetaryDetailsRequest;

/**
 * AssociatedAmounts.php
 *
 * <Description>
 *
 * @copyright Copyright (c) 2018 Invia Flights GmbH
 * @author    Invia Flights Germany GmbH <teamleitung-dev@invia.de>
 * @author    Fluege-Dev <fluege-dev@invia.de>
 */
class AssociatedAmounts
{
    /**
     * @var MonetaryDetails[]
     */
    public $monetaryDetails;

    /**
     * AssociatedAmounts constructor.
     *
     * @param MonetaryDetailsRequest[] $monetaryDetails
     */
    public function __construct(array $monetaryDetails)
    {
        foreach ($monetaryDetails as $monetaryDetail) {
            $this->monetaryDetails[] = new MonetaryDetails($monetaryDetail);
        }
    }
}