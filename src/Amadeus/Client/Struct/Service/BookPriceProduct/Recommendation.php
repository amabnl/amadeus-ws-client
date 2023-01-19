<?php

namespace Amadeus\Client\Struct\Service\BookPriceProduct;

class Recommendation
{
    /**
     * @var string
     */
    public $RecoID;

    /**
     * @var string[]
     */
    public $CustomerRefIds;

    /**
     * @param \Amadeus\Client\RequestOptions\Service\BookPriceProduct\Recommendation $recommendationOptions
     */
    public function __construct($recommendationOptions)
    {
        $this->RecoID = $recommendationOptions->id;

        if (!empty($recommendationOptions->customerRefIds)) {
            $this->CustomerRefIds = $recommendationOptions->customerRefIds;
        }
    }
}
