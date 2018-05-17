<?php

namespace Amadeus\Client\RequestCreator\Converter\Ticket;

use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\TicketCreateTasfOptions;
use Amadeus\Client\Struct;

/**
 * TicketCreateTSAF Request converter
 *
 * @package Amadeus\Client\RequestCreator\Converter\Ticket
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class CreateTASFConv extends BaseConverter
{
    /**
     * @param TicketCreateTasfOptions $requestOptions
     * @param int|string $version
     * @return Struct\Ticket\CreateTSAF
     */
    public function convert($requestOptions, $version)
    {
        return new Struct\Ticket\CreateTSAF($requestOptions);
    }
}
