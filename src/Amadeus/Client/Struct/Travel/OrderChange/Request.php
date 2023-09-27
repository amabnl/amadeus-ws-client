<?php

namespace Amadeus\Client\Struct\Travel\OrderChange;

use Amadeus\Client\RequestOptions;
use Amadeus\Client\Struct\Travel\DataList;
use Amadeus\Client\Struct\Travel\Order;
use Amadeus\Client\Struct\Travel\PaxList;

/**
 * Request
 *
 * @package Amadeus\Client\Struct\Travel\OrderRetrieve
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Request
{
    /**
     * @var ChangeOrder
     */
    public $ChangeOrder;

    /**
     * @var DataList
     */
    public $DataLists;

    /**
     * @var Order
     */
    public $Order;

    /**
     * @param RequestOptions\Travel\OrderChange\AcceptChange $acceptChange
     * @param RequestOptions\Travel\DataList[] $dataLists
     * @param Order $order
     */
    public function __construct(
        RequestOptions\Travel\OrderChange\AcceptChange $acceptChange,
        array $dataLists,
        Order $order
    ) {
        $this->ChangeOrder = new ChangeOrder(
            new AcceptChange($acceptChange->orderItemRefIds)
        );
        $this->loadDataLists($dataLists);
        $this->Order = $order;
    }

    /**
     * @param RequestOptions\Travel\DataList[] $requestDataLists
     * @return void
     */
    protected function loadDataLists(array $requestDataLists)
    {
        foreach ($requestDataLists as $requestDataList) {
            $this->DataLists = new DataList(new PaxList($requestDataList->paxList));
        }
    }
}
