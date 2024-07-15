<?php

namespace Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\TravelOrderPayOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Travel\OrderPay\Amount;
use Amadeus\Client\Struct\Travel\OrderPay\Cash;
use Amadeus\Client\Struct\Travel\OrderPay\PaymentMethod;

/**
 * Travel_OrderPay message structure
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class OrderPay extends BaseWsMessage
{
    /**
     * @var Party
     */
    public $Party;

    /**
     * @var OrderPay\Request
     */
    public $Request;

    public function __construct(TravelOrderPayOptions $options)
    {
        $this->Party = new Party($options->party);
        $this->Request = new OrderPay\Request(
            new OrderPay\PaymentInfo(
                new Amount($options->amount, $options->currencyCode),
                $options->type,
                $this->getPaymentMethod($options->type)
            ),
            new Order(
                $options->orderId,
                $options->ownerCode
            )
        );
    }

    /**
     * @param string $type
     * @return PaymentMethod
     */
    private function getPaymentMethod($type)
    {
        $cash = null;

        if ($type === PaymentMethod::TYPE_CASH) {
            $cash = new Cash(true);
        }

        return new PaymentMethod($cash);
    }
}
