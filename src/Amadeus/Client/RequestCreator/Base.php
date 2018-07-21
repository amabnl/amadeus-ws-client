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
use Amadeus\Client\RequestCreator\Converter\ConvertInterface;
use Amadeus\Client\RequestOptions\RequestOptionsInterface;
use Amadeus\Client\Struct;

/**
 * Base request creator - the default request creator.
 *
 * In charge of building the request objects that are used by the Soap Client.
 * They are built depending on:
 * - Which message is being created.
 * - Which request options were provided.
 * - What message version is active in the current WSDL?
 *
 * @package Amadeus\Client\RequestCreator
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Base implements RequestCreatorInterface
{
    /**
     * Parameters
     *
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
     * All message builders already instantiated
     *
     * @var array
     */
    protected $messageBuilders = [];

    /**
     * Base Request Creator constructor.
     *
     * @param RequestCreatorParams $params
     */
    public function __construct(RequestCreatorParams $params)
    {
        $this->params = $params;
        $this->messagesAndVersions = $params->messagesAndVersions;
    }

    /**
     * Create a request message for a given message with a set of options.
     *
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

        if ($builder instanceof ConvertInterface) {
            return $builder->convert($params, $this->getActiveVersionFor($messageName));
        } else {
            throw new \RuntimeException('No builder found for message '.$messageName);
        }
    }

    /**
     * Check if a given message is in the active WSDL(s). Throws exception if it isn't.
     *
     * @throws InvalidMessageException if message is not in loaded WSDL(s).
     * @param string $messageName
     */
    protected function checkMessageIsInWsdl($messageName)
    {
        if (!array_key_exists($messageName, $this->messagesAndVersions)) {
            throw new InvalidMessageException('Message "'.$messageName.'" is not in WDSL');
        }
    }

    /**
     * Get the version number active in the WSDL for the given message
     *
     * @param string $messageName
     * @return float|string|null
     */
    protected function getActiveVersionFor($messageName)
    {
        $found = null;

        if (isset($this->messagesAndVersions[$messageName]) &&
            isset($this->messagesAndVersions[$messageName]['version'])
        ) {
            $found = $this->messagesAndVersions[$messageName]['version'];
        }

        return $found;
    }

    /**
     * Find the correct builder for a given message
     *
     * Builder classes implement the ConvertInterface and are used to build only one kind of message.
     *
     * The standard converter class is
     * __NAMESPACE__ \ Converter \ Sectionname \ Messagename + "Conv"
     * e.g.
     * Amadeus\Client\RequestCreator\Converter\DocIssuance\IssueTicketConv
     *
     * @param string $messageName
     * @return ConvertInterface|null
     */
    protected function findBuilderForMessage($messageName)
    {
        $builder = null;

        if (array_key_exists($messageName, $this->messageBuilders) &&
            $this->messageBuilders[$messageName] instanceof ConvertInterface
        ) {
            $builder = $this->messageBuilders[$messageName];
        } else {
            $section = substr($messageName, 0, strpos($messageName, '_'));
            $message = substr($messageName, strpos($messageName, '_') + 1);

            $builderClass = __NAMESPACE__.'\\Converter\\'.$section.'\\'.$message."Conv";

            if (class_exists($builderClass)) {
                /** @var ConvertInterface $builder */
                $builder = new $builderClass();
                $builder->setParams($this->params);

                $this->messageBuilders[$messageName] = $builder;
            }
        }

        return $builder;
    }
}
