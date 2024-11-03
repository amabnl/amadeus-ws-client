<?php

namespace Amadeus\Client\Struct\Service;

use Amadeus\Client\RequestOptions\ServiceBookPriceProductOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Service\BookPriceProduct\Recommendation;

class BookPriceProduct extends BaseWsMessage
{
    /**
     * @var Recommendation[]
     */
    public $Recommendation;
    
    public $Version;

    /**
     * @param ServiceBookPriceProductOptions $options
     */
    public function __construct($options)
    {
        $this->Version = $options->version;
        
        foreach ($options->recommendations as $recommendation) {
            $this->Recommendation[] = new Recommendation($recommendation);
        }
    }
}
