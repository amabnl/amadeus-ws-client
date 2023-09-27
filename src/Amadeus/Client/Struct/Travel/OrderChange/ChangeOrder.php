<?php

namespace Amadeus\Client\Struct\Travel\OrderChange;

/**
 * ChangeOrder
 *
 * @package Amadeus\Client\Struct\Travel\OrderRetrieve
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class ChangeOrder
{
    /**
     * @var AcceptChange
     */
    public $AcceptChange;

    public function __construct(AcceptChange $acceptChange)
    {
        $this->AcceptChange = $acceptChange;
    }
}
