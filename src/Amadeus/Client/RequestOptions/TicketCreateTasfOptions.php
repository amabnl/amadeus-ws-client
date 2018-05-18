<?php

namespace Amadeus\Client\RequestOptions;

use Amadeus\Client\RequestOptions\Ticket\MonetaryInformation;
use Amadeus\Client\RequestOptions\Ticket\PassengerTattoo;

/**
 * TicketCreateTasfOptions
 *
 * @package Amadeus\Client\RequestOptions
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class TicketCreateTasfOptions extends Base
{
    /**
     * @var PassengerTattoo
     */
    public $passengerTattoo;

    /**
     * @var MonetaryInformation
     */
    public $monetaryInformation;

    /**
     * Used to specify the RFIC.
     *
     * @var string
     */
    public $reasonForIssuanceCode;
}
