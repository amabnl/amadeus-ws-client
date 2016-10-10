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

namespace Amadeus\Client\RequestCreator;

use Amadeus\Client\InvalidMessageException;
use Amadeus\Client\Params\RequestCreatorParams;
use Amadeus\Client\RequestOptions\CommandCrypticOptions;
use Amadeus\Client\RequestOptions\DocIssuanceIssueTicketOptions;
use Amadeus\Client\RequestOptions\InfoEncodeDecodeCityOptions;
use Amadeus\Client\RequestOptions\MiniRuleGetFromPricingRecOptions;
use Amadeus\Client\RequestOptions\OfferConfirmAirOptions;
use Amadeus\Client\RequestOptions\OfferConfirmCarOptions;
use Amadeus\Client\RequestOptions\OfferConfirmHotelOptions;
use Amadeus\Client\RequestOptions\OfferVerifyOptions;
use Amadeus\Client\RequestOptions\PriceXplorerExtremeSearchOptions;
use Amadeus\Client\RequestOptions\RequestOptionsInterface;
use Amadeus\Client\RequestOptions\SalesReportsDisplayQueryReportOptions;
use Amadeus\Client\RequestOptions\SecurityAuthenticateOptions;
use Amadeus\Client\RequestOptions\TicketCreateTstFromPricingOptions;
use Amadeus\Client\RequestOptions\TicketDeleteTstOptions;
use Amadeus\Client\RequestOptions\TicketDisplayTstOptions;
use Amadeus\Client\Struct;

/**
 * Base request creator - the default request creator.
 *
 * @package Amadeus\Client\RequestCreator
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Base implements RequestCreatorInterface
{
    /**
     * @var RequestCreatorParams
     */
    protected $params;

    /**
     * Associative array of messages (as keys) and versions (as values) that are present in the WSDL.
     *
     * @var array
     */
    protected $messagesAndVersions = [];

    /**
     * @param $params
     */
    public function __construct(RequestCreatorParams $params)
    {
        $this->params = $params;
        $this->messagesAndVersions = $params->messagesAndVersions;
    }

    /**
     * @param string $messageName the message name as named in the WSDL
     * @param RequestOptionsInterface $params
     * @throws Struct\InvalidArgumentException When invalid input is detected during message creation.
     * @throws InvalidMessageException when trying to create a request for a message that is not in your WSDL.
     * @return mixed the created request
     */
    public function createRequest($messageName, RequestOptionsInterface $params)
    {
        $this->checkMessageIsInWsdl($messageName);

        $builder = $this->findBuilderForMessage($messageName);

        $methodName = 'create' . str_replace("_", "", $messageName);

        if (method_exists($builder, $methodName)) {
            return $builder->$methodName($params, $this->getActiveVersionFor($messageName));
        } else {
            throw new \RuntimeException('Message ' . $methodName . ' is not implemented in ' . __CLASS__);
        }
    }

    /**
     * @return Struct\Security\SignOut
     */
    protected function createSecuritySignOut()
    {
        return new Struct\Security\SignOut();
    }

    /**
     * Create request object for Security_Authenticate message
     *
     * @param SecurityAuthenticateOptions $params
     * @return Struct\Security\Authenticate
     */
    protected function createSecurityAuthenticate(SecurityAuthenticateOptions $params)
    {
        return new Struct\Security\Authenticate($params);
    }

    /**
     * @param OfferVerifyOptions $params
     * @return Struct\Offer\Verify
     */
    protected function createOfferVerifyOffer(OfferVerifyOptions $params)
    {
        $req = new Struct\Offer\Verify(
            $params->offerReference,
            $params->segmentName
        );

        return $req;
    }

    /**
     * @param OfferConfirmAirOptions $params
     * @return Struct\Offer\ConfirmAir
     */
    protected function createOfferConfirmAirOffer(OfferConfirmAirOptions $params)
    {
        return new Struct\Offer\ConfirmAir($params);
    }


    /**
     * @param OfferConfirmHotelOptions $params
     * @return Struct\Offer\ConfirmHotel
     */
    protected function createOfferConfirmHotelOffer(OfferConfirmHotelOptions $params)
    {
        return new Struct\Offer\ConfirmHotel($params);
    }

    /**
     * @param OfferConfirmCarOptions $params
     * @return Struct\Offer\ConfirmCar
     */
    protected function createOfferConfirmCarOffer(OfferConfirmCarOptions $params)
    {
        return new Struct\Offer\ConfirmCar($params);
    }

    /**
     * Command_Cryptic
     *
     * @param CommandCrypticOptions $params
     * @return Struct\Command\Cryptic
     */
    protected function createCommandCryptic(CommandCrypticOptions $params)
    {
        return new Struct\Command\Cryptic($params->entry);
    }

    /**
     * Info_EncodeDecodeCity
     *
     * @param InfoEncodeDecodeCityOptions $params
     * @return Struct\Info\EncodeDecodeCity
     */
    protected function createInfoEncodeDecodeCity(InfoEncodeDecodeCityOptions $params)
    {
        return new Struct\Info\EncodeDecodeCity($params);
    }

    /**
     * makeMiniRuleGetFromPricingRec
     *
     * @param MiniRuleGetFromPricingRecOptions $params
     * @return Struct\MiniRule\GetFromPricingRec
     */
    protected function createMiniRuleGetFromPricingRec(MiniRuleGetFromPricingRecOptions $params)
    {
        return new Struct\MiniRule\GetFromPricingRec($params);
    }

    /**
     * Ticket_CreateTstFromPricing
     *
     * @param TicketCreateTstFromPricingOptions $params
     * @return Struct\Ticket\CreateTSTFromPricing
     */
    protected function createTicketCreateTSTFromPricing(TicketCreateTstFromPricingOptions $params)
    {
        return new Struct\Ticket\CreateTSTFromPricing($params);
    }

    /**
     * Ticket_DeleteTST
     *
     * @param TicketDeleteTstOptions $params
     * @return Struct\Ticket\DeleteTST
     */
    protected function createTicketDeleteTST(TicketDeleteTstOptions $params)
    {
        return new Struct\Ticket\DeleteTST($params);
    }

    /**
     * Ticket_DisplayTST
     *
     * @param TicketDisplayTstOptions $params
     * @return Struct\Ticket\DisplayTST
     */
    protected function createTicketDisplayTST(TicketDisplayTstOptions $params)
    {
        return new Struct\Ticket\DisplayTST($params);
    }

    /**
     * DocIssuance_IssueTicket
     *
     * @param DocIssuanceIssueTicketOptions $params
     * @return Struct\DocIssuance\IssueTicket
     */
    protected function createDocIssuanceIssueTicket(DocIssuanceIssueTicketOptions $params)
    {
        return new Struct\DocIssuance\IssueTicket($params);
    }

    /**
     * PriceXplorer_ExtremeSearch
     *
     * @param PriceXplorerExtremeSearchOptions $params
     * @return Struct\PriceXplorer\ExtremeSearch
     */
    protected function createPriceXplorerExtremeSearch(PriceXplorerExtremeSearchOptions $params)
    {
        return new Struct\PriceXplorer\ExtremeSearch($params);
    }

    /**
     * SalesReports_DisplayQueryReport
     *
     * @param SalesReportsDisplayQueryReportOptions $params
     * @return Struct\SalesReports\DisplayQueryReport
     */
    protected function createSalesReportsDisplayQueryReport(SalesReportsDisplayQueryReportOptions $params)
    {
        return new Struct\SalesReports\DisplayQueryReport($params);
    }

    /**
     * Check if a given message is in the active WSDL. Throws exception if it isn't.
     *
     * @throws InvalidMessageException if message is not in WSDL.
     * @param string $messageName
     */
    protected function checkMessageIsInWsdl($messageName)
    {
        if (!array_key_exists($messageName, $this->messagesAndVersions)) {
            throw new InvalidMessageException('Message "' . $messageName . '" is not in WDSL');
        }
    }

    /**
     * Get the version number active in the WSDL for the given message
     *
     * @param string $messageName
     * @return float|string
     */
    protected function getActiveVersionFor($messageName)
    {
        return $this->messagesAndVersions[$messageName];
    }

    /**
     * Find the correct builder for a given message
     *
     * Message build methods in all builders must adhere to the
     * 'create'<message name without underscores> logic as used in createRequest method.
     *
     * @param string $messageName
     * @return Base|Fare|Pnr
     */
    protected function findBuilderForMessage($messageName)
    {
        $builder = null;

        $section = strtolower(substr($messageName, 0, strpos($messageName, '_')));

        switch ($section) {
            case 'fare':
                $builder = new Fare();
                break;
            case 'pnr':
                $builder = new Pnr($this->params);
                break;
            case 'air':
                $builder = new Air();
                break;
            case 'queue':
                $builder = new Queue();
                break;
            default:
                $builder = $this;
                break;
        }

        return $builder;
    }
}
