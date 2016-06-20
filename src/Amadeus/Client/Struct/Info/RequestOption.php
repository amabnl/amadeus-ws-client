<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Info;

/**
 * RequestOption
 *
 * @package Amadeus\Client\Struct\Info
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class RequestOption
{
    /**
     * @var SelectionDetails
     */
    public $selectionDetails;

    /**
     * @var SelectionDetails[]
     */
    public $otherSelectionDetails = [];

    /**
     * RequestOption constructor.
     *
     * @param string|null $optionInfo SelectionDetails::OPTINF_*
     * @param string|null $option SelectionDetails::OPT_*
     */
    public function __construct($optionInfo = null, $option = null)
    {
        if (!is_null($optionInfo) && !is_null($option)) {
            $this->selectionDetails = new SelectionDetails($optionInfo, $option);
        }
    }
}
