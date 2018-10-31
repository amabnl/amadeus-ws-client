<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 29/10/2018
 * Time: 15:32
 */

namespace Amadeus\Client\Struct\Service;

use Amadeus\Client\Struct\BaseWsMessage;

class BookPriceProduct extends BaseWsMessage
{
    public $Version;
    public $Recommendation;
    //public $ServiceMiscellaneous;
    //public $Products;

    public function __construct($options)
    {
        if (!is_null($options)) {
            $this->Version = $options->Version;
            $this->Recommendation = $options->Recommendation;
            //$this->ServiceMiscellaneous = $options->ServiceMiscellaneous;
            //$this->Products = $options->Products;
        }
    }
}