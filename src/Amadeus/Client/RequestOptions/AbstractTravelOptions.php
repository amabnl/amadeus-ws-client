<?php

namespace Amadeus\Client\RequestOptions;

use Amadeus\Client\RequestOptions\Travel\Party;

abstract class AbstractTravelOptions extends Base
{
    /**
     * @var Party|null
     */
    public $party;
}
