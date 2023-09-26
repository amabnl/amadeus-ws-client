<?php

namespace Amadeus\Client\Struct\Travel;

/**
 * DataList
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class DataList
{
    /**
     * @var PaxList
     */
    public $PaxList;

    /**
     * @param PaxList $paxList
     */
    public function __construct(PaxList $paxList)
    {
        $this->PaxList = $paxList;
    }
}
