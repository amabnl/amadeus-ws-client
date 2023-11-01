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
     * @param RequestOptions\Travel\OrderChange\AcceptChange|null $acceptChange
     * @param RequestOptions\Travel\OrderChange\UpdateOrderItem|null $updateOrderItem
     * @param RequestOptions\Travel\DataList[] $dataLists
     * @param Order $order
     */
    public function __construct(
        $acceptChange,
        $updateOrderItem,
        array $dataLists,
        Order $order
    ) {
        $this->ChangeOrder = new ChangeOrder();

        if ($acceptChange) {
            $this->ChangeOrder->setAcceptChange(new AcceptChange($acceptChange->orderItemRefIds));
        }

        if ($updateOrderItem) {
            $selectedOfferItems = array_map(
                static function (RequestOptions\Travel\SelectedOfferItem $item) {
                    $selectedOfferItem = new SelectedOfferItem(
                        $item->offerItemRefId,
                        $item->paxRefId[0]
                    );

                    if ($selectedSeat = $item->selectedSeat) {
                        $selectedOfferItem->setSelectedSeat(
                            new SelectedSeat($selectedSeat->rowNumber, $selectedSeat->column)
                        );
                    }

                    return $selectedOfferItem;
                },
                $updateOrderItem->offer->selectedOfferItems
            );

            $this->ChangeOrder->setUpdateOrderItem(
                new UpdateOrderItem(
                    new AcceptOffer(
                        new SelectedOffer(
                            $updateOrderItem->offer->offerRefID,
                            $updateOrderItem->offer->ownerCode,
                            $updateOrderItem->offer->shoppingResponseRefID,
                            $selectedOfferItems
                        )
                    )
                )
            );
        }

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
