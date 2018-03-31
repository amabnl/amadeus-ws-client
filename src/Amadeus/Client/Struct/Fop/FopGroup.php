<?php
/**
 * amadeus-ws-client
 *
 * Copyright 2015 Amadeus Benelux NV
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package Amadeus
 * @license https://opensource.org/licenses/Apache-2.0 Apache 2.0
 */

namespace Amadeus\Client\Struct\Fop;

use Amadeus\Client\RequestOptions\Fop\ElementRef;
use Amadeus\Client\RequestOptions\Fop\Group;
use Amadeus\Client\RequestOptions\Fop\FopRef;
use Amadeus\Client\RequestOptions\Fop\ObFeeComputation;
use Amadeus\Client\RequestOptions\Fop\PaxRef;
use Amadeus\Client\Struct\Fop\CreateFormOfPayment\FopGroup14;
use Amadeus\Client\Struct\Fop\CreateFormOfPayment\MopDescription14;

/**
 * FopGroup
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FopGroup
{
    /**
     * @var FopReference
     */
    public $fopReference;
    /**
     * @var PassengerAssociation[]
     */
    public $passengerAssociation = [];
    /**
     * @var PnrElementAssociation[]
     */
    public $pnrElementAssociation = [];
    /**
     * @var PricingTicketingDetails
     */
    public $pricingTicketingDetails;
    /**
     * @var FeeTypeInfo
     */
    public $feeTypeInfo;
    /**
     * @var FeeDetailsInfoGroup[]
     */
    public $feeDetailsInfoGroup = [];
    /**
     * @var FpProcessingOptions
     */
    public $fpProcessingOptions;
    /**
     * @var MopDescription[]
     */
    public $mopDescription = [];

    /**
     * FopGroup constructor.
     *
     * @param Group $options
     */
    public function __construct(Group $options)
    {
        $this->loadObFeeComp($options);

        $this->loadPaxElementRefs($options->paxRef, $options->elementRef);

        foreach ($options->mopInfo as $mopInfo) {
            if ($this instanceof FopGroup14) {
                $this->mopDescription[] = new MopDescription14($mopInfo);
            } else {
                $this->mopDescription[] = new MopDescription($mopInfo);
            }
        }

        if ($options->fopRef instanceof FopRef) {
            $this->fopReference = new FopReference($options->fopRef);
        }
    }

    /**
     * @param Group $options
     */
    protected function loadObFeeComp(Group $options)
    {
        if ($options->obFeeComputation instanceof ObFeeComputation) {
            $this->feeTypeInfo = new FeeTypeInfo(
                $options->obFeeComputation->option,
                $options->obFeeComputation->optionInformation
            );

            $this->pricingTicketingDetails = new PricingTicketingDetails(
                $options->obFeeComputation->departureDate,
                $options->obFeeComputation->city
            );
        }
    }

    /**
     * @param PaxRef[] $paxRef
     * @param ElementRef[] $elementRef
     */
    protected function loadPaxElementRefs($paxRef, $elementRef)
    {
        foreach ($paxRef as $singlePaxRef) {
            $this->passengerAssociation[] = new PassengerAssociation(
                $singlePaxRef->type,
                $singlePaxRef->value
            );
        }

        foreach ($elementRef as $singleElRef) {
            $this->pnrElementAssociation[] = new PnrElementAssociation(
                $singleElRef->type,
                $singleElRef->value
            );
        }
    }
}
