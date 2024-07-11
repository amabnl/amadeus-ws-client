<?php

namespace Amadeus\Client\Struct\Travel\OrderRetrieve;

/**
 * Request
 *
 * @package Amadeus\Client\Struct\Travel\OrderRetrieve
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Request
{
    /**
     * @var OrderFilterCriteria
     */
    public $OrderFilterCriteria;

    /**
     * @param OrderFilterCriteria $orderFilterCriteria
     */
    public function __construct($orderFilterCriteria)
    {
        $this->OrderFilterCriteria = $orderFilterCriteria;
    }
}
