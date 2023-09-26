<?php

namespace Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\Travel\Party as RequestParty;

/**
 * Party
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Party
{
    /**
     * @var Sender
     */
    public $Sender;

    /**
     * @param RequestParty|null $requestParty
     */
    public function __construct($requestParty)
    {
        $this->Sender = $this->getSender($requestParty);
    }

    /**
     * @param RequestParty|null $requestParty
     * @return Sender
     */
    private function getSender($requestParty)
    {
        if ($requestParty === null) {
            $sender = new Sender(new TravelAgency('unused', 'unused'));
        } else {
            $sender = new Sender(new TravelAgency(
                $requestParty->sender->travelAgency->agencyId,
                $requestParty->sender->travelAgency->pseudoCityId
            ));
        }

        return $sender;
    }
}
