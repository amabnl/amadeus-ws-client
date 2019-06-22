<?php
/**
 * Created by PhpStorm.
 * User: ruslan
 * Date: 6/22/19
 * Time: 11:53 PM
 */

namespace Amadeus\Client\Struct\DocRefund;

class StockProviderDetails
{
    /**
     * @var string
     */
    public $stockTypeCode;
    
    /**
     * @var string
     */
    public $stockProvider;
    
    /**
     * StockProviderDetails constructor.
     *
     * @param string|null $stockTypeCode
     * @param string|null $stockProvider
     */
    public function __construct($stockTypeCode, $stockProvider)
    {
        $this->stockTypeCode = $stockTypeCode;
        $this->stockProvider = $stockProvider;
    }
}