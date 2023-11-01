<?php

namespace Amadeus\Client\RequestOptions\Travel;

use Amadeus\Client\LoadParamsFromArray;

class SelectedSeat extends LoadParamsFromArray
{
    /**
     * @var string
     */
    public $column;

    /**
     * @var int
     */
    public $rowNumber;
}
