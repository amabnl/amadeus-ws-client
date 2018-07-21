<?php

namespace Amadeus\Client\Struct\Ticket\CreateTASF;

class PassengerReference
{
    const TYPE_ADULT = 'PT';
    const TYPE_INFANT = 'PI';

    /**
     *
     * Must be PT for general case and PI for infant.
     *
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * Must contain the tattoo number of the passenger (who has to pay the TASF).
     *
     * @var string|int
     */
    public $value;

    /**
     * PassengerTattoo constructor.
     *
     * @param string $type
     * @param string|int $value
     */
    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}
