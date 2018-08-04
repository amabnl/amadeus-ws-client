<?php

namespace Amadeus\Client\RequestOptions\Ticket;

use Amadeus\Client\LoadParamsFromArray;

class PassengerTattoo extends LoadParamsFromArray
{
    const TYPE_ADULT = 'PT';
    const TYPE_INFANT = 'PI';

    /**
     *
     * Must be PT for general case and PI for infant.
     *
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * Must contain the tattoo number of the passenger (who has to pay the TASF).
     *
     * @var string|int
     */
    public $value;
}
