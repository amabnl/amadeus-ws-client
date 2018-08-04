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

namespace Amadeus\Client\Struct\Ticket;

use Amadeus\Client\RequestOptions\Ticket\PassengerReference;
use Amadeus\Client\RequestOptions\Ticket\Pricing;
use Amadeus\Client\RequestOptions\TicketCreateTsmFromPricingOptions;
use Amadeus\Client\RequestOptions\TicketCreateTstFromPricingOptions;
use Amadeus\Client\Struct\BaseWsMessage;
/**
 * Ticket_CreateTSTFromPricing
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author dieter <dieter.devlieghere@benelux.amadeus.com>
 */
class CancelDocument extends BaseWsMessage
{
	public $documentNumberDetails = [];
	
	public $stockProviderDetails;
	
	public $targetOfficeDetails;
	
	public function __construct($params) {
		
		if(!empty($params->ticketNumber)){
			$this->documentNumberDetails = new DocumentNumberDetails($params->ticketNumber);
		}
		
		if(!empty($params->marketIataCode)) {
			$this->stockProviderDetails = new StockProviderDetails($params->marketIataCode);
		}
		//$this->targetOfficeDetails = new TargetOfficeDetails($params->officeId);
	}
	
	
}
