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

use Amadeus\Client\Params\RequestCreatorParams;
use Amadeus\Client\RequestOptions\PnrAddMultiElementsBase;
use Amadeus\Client\RequestOptions\PnrCancelOptions;
use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
use Amadeus\Client\RequestOptions\PnrDisplayHistoryOptions;
use Amadeus\Client\RequestOptions\PnrRetrieveAndDisplayOptions;
use Amadeus\Client\RequestOptions\PnrRetrieveOptions;
use Amadeus\Client\RequestOptions\PnrTransferOwnershipOptions;
use Amadeus\Client\Struct;

/**
 * PNR Request Creator
 *
 * Responsible for creating all "Pnr_" messages
 *
 * methods for creation must have the correct name
 * 'create'<message name without underscores>
 *
 * @package Amadeus\Client\RequestCreator
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Pnr
{
    /**
     * Parameters
     *
     * @var RequestCreatorParams
     */
    protected $params;

    /**
     * Pnr Request Creator constructor.
     *
     * @param RequestCreatorParams $params
     */
    public function __construct(RequestCreatorParams $params)
    {
        $this->params = $params;
    }

    /**
     * Create request object for PNR_Retrieve message
     *
     * @param PnrRetrieveOptions $params
     * @return Struct\Pnr\Retrieve
     */
    public function createPNRRetrieve(PnrRetrieveOptions $params)
    {
        $retrieveRequest = new Struct\Pnr\Retrieve(
            Struct\Pnr\Retrieve::RETR_TYPE_BY_RECLOC,
            $params->recordLocator
        );

        return $retrieveRequest;
    }

    /**
     * PNR_RetrieveAndDisplay
     *
     * @param PnrRetrieveAndDisplayOptions $params
     * @return Struct\Pnr\RetrieveAndDisplay
     */
    public function createPNRRetrieveAndDisplay(PnrRetrieveAndDisplayOptions $params)
    {
        $req = new Struct\Pnr\RetrieveAndDisplay(
            $params->recordLocator,
            $params->retrieveOption
        );

        return $req;
    }

    /**
     * PNR_AddMultiElements
     *
     * @param PnrAddMultiElementsBase $params
     * @return Struct\Pnr\AddMultiElements
     */
    public function createPNRAddMultiElements(PnrAddMultiElementsBase $params)
    {
        if ($params instanceof PnrCreatePnrOptions && empty($params->receivedFrom)) {
            //Automagically add RF if not present:
            $params->receivedFrom = $this->params->receivedFrom;
        }

        $req = new Struct\Pnr\AddMultiElements($params);

        return $req;
    }

    /**
     * PNR_Cancel
     *
     * @param PnrCancelOptions $params
     * @return Struct\Pnr\Cancel
     */
    public function createPNRCancel(PnrCancelOptions $params)
    {
        return new Struct\Pnr\Cancel($params);
    }

    /**
     * PNR_DisplayHistory
     *
     * @param PnrDisplayHistoryOptions $params
     * @return Struct\Pnr\DisplayHistory
     */
    public function createPNRDisplayHistory(PnrDisplayHistoryOptions $params)
    {
        return new Struct\Pnr\DisplayHistory($params);
    }

    /**
     * PNR_TransferOwnership
     *
     * @param PnrTransferOwnershipOptions $params
     * @return Struct\Pnr\TransferOwnership
     */
    public function createPNRTransferOwnership(PnrTransferOwnershipOptions $params)
    {
        return new Struct\Pnr\TransferOwnership($params);
    }
}
