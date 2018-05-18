<?php

namespace Amadeus\Client\Struct\Ticket;

use Amadeus\Client\RequestOptions\TicketCreateTasfOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Ticket\CreateTASF\CriteriaDetails;
use Amadeus\Client\Struct\Ticket\CreateTASF\MonetaryInformation;
use Amadeus\Client\Struct\Ticket\CreateTASF\PassengerTattoo;
use Amadeus\Client\Struct\Ticket\CreateTASF\ReasonForIssuance;

/**
 * CreateTASF
 *
 * Basic Request structure for the TicketCreateTASF messages
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class CreateTASF extends BaseWsMessage
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
     * @var ReasonForIssuance
     */
    public $reasonForIssuanceCode;

    /**
     * CreateTASF constructor.
     *
     * @param TicketCreateTasfOptions $requestOptions
     */
    public function __construct($requestOptions)
    {
        $passengerTattooRequest = $requestOptions->passengerTattoo;
        $this->passengerTattoo = new PassengerTattoo($passengerTattooRequest->type, $passengerTattooRequest->value);

        $monetaryInformationRequest = $requestOptions->monetaryInformation;
        $this->monetaryInformation = new MonetaryInformation(
            $monetaryInformationRequest->amount,
            $monetaryInformationRequest->currency
        );

        if (!is_null($requestOptions->reasonForIssuanceCode)) {
            $this->reasonForIssuanceCode = new ReasonForIssuance(
                new CriteriaDetails($requestOptions->reasonForIssuanceCode)
            );
        }
    }
}
