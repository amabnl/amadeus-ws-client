<?php

namespace Amadeus\Client\Struct\Travel;

/**
 * TravelAgency
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class TravelAgency
{
    /**
     * @var string
     */
    public $AgencyID;

    /**
     * @var string
     */
    public $PseudoCityID;

    /**
     * @param string $AgencyID
     * @param string $PseudoCityID
     */
    public function __construct($AgencyID, $PseudoCityID)
    {
        $this->AgencyID = $AgencyID;
        $this->PseudoCityID = $PseudoCityID;
    }
}
