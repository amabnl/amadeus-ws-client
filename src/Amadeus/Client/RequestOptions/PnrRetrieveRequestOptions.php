<?php
/**
 * Created by PhpStorm.
 * User: dieter
 * Date: 7/01/2016
 * Time: 15:22
 */

namespace Amadeus\Client\RequestOptions;


class PnrRetrieveRequestOptions implements RequestOptionsInterface
{
    /**
     * @var string
     */
    public $recordLocator;

    /**
     * @param string|null $recordLocator
     */
    public function __construct($recordLocator = null)
    {
        $this->recordLocator = $recordLocator;
    }
}