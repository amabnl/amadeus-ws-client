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

namespace Amadeus\Client\Struct\Pnr\AddMultiElements;

use Amadeus\Client\RequestOptions\Pnr\Segment;
use Amadeus\Client\Struct\InvalidArgumentException;

/**
 * AirAuxItinerary
 * 
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class AirAuxItinerary
{
    /**
     * @var TravelProduct
     */
    public $travelProduct;
    /**
     * @var MessageAction
     */
    public $messageAction;
    /**
     * @var RelatedProduct
     */
    public $relatedProduct;

    public $selectionDetailsAir;

    public $reservationInfoSell;
    /**
     * @var FreetextItinerary
     */
    public $freetextItinerary;

    /**
     * AirAuxItinerary constructor.
     *
     * @param string $segmentType
     * @param Segment $segmentContent
     */
    public function __construct($segmentType, $segmentContent)
    {
        switch($segmentType) {
            case 'Miscellaneous':
                $this->loadMiscellaneous($segmentContent);
                break;
            case 'Air':
                $this->loadAir($segmentContent);
                break;
            default:
                throw new InvalidArgumentException('Segment type ' . $segmentType . 'is not supported');
                break;
        }
    }

    /**
     * @param Segment\Miscellaneous $segment
     */
    protected function loadMiscellaneous(Segment\Miscellaneous $segment)
    {
        $this->travelProduct = new TravelProduct(
            $segment->date,
            $segment->cityCode,
            $segment->company
        );
        $this->messageAction = new MessageAction(Business::FUNC_MISC);

        $this->relatedProduct = new RelatedProduct($segment->status, $segment->quantity);

        $this->freetextItinerary = new FreetextItinerary($segment->freeText);
    }

    /**
     * @param Segment\Air $segment
     */
    protected function loadAir(Segment\Air $segment)
    {
        throw new \RuntimeException('NOT YET IMPLEMENTED');
    }
}
