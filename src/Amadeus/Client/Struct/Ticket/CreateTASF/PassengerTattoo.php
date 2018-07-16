<?php

namespace Amadeus\Client\Struct\Ticket\CreateTASF;

class PassengerTattoo
{
    /**
     * @var PassengerReference
     */
    public $passengerReference;

    /**
     * PassengerTattoo constructor.
     *
     * @param string $type
     * @param string|int $value
     */
    public function __construct($type, $value)
    {
        $this->passengerReference = new PassengerReference($type, $value);
    }
}
