<?php

namespace Amadeus\Client\Struct\Travel\OfferPrice;

/**
 * SelectedSeat
 *
 * @package Amadeus\Client\Struct\Travel\OfferPrice
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class SelectedSeat
{
    /**
     * @var int
     */
    public $SeatRowNumber;

    /**
     * @var string
     */
    public $ColumnID;

    /**
     * @param int $seatRowNumber
     * @param string $columnId
     */
    public function __construct($seatRowNumber, $columnId)
    {
        $this->SeatRowNumber = $seatRowNumber;
        $this->ColumnID = $columnId;
    }
}
