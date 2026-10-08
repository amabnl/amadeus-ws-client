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

namespace Amadeus\Client\ResponseHandler\Travel;

use Amadeus\Client\Result;
use Amadeus\Client\Result\NotOk;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * HandlerOrderReshop
 *
 * Response handler for the Travel_OrderReshop message.
 *
 * The shared HandlerTravel only recognises errors shaped as <Error> or
 * <Errors><Error>. The NDC OrderReshop response can instead return a "flat"
 * error where the <Errors> node directly carries <Code> and <DescText> without
 * an inner <Error> wrapper, e.g.:
 *
 *   <Errors>
 *       <Code>368</Code>
 *       <DescText>NO CANCELLATION SOLUTION FOUND</DescText>
 *       <OwnerName>EY</OwnerName>
 *   </Errors>
 *
 * Note: $response->responseObject is the object graph decoded by PHP's
 * \SoapClient (stdClass / arrays), not a SimpleXMLElement, so error detection
 * is done against that structure.
 *
 * @package Amadeus\Client\ResponseHandler\Travel
 */
class HandlerOrderReshop extends HandlerTravel
{
    /**
     * @param SendResult $response
     * @return Result
     */
    public function analyze(SendResult $response)
    {
        $result = $this->analyzeSimpleResponseErrorCodeAndMessageStatusCode($response);

        foreach ($this->collectErrorNodes($response->responseObject) as $errorNode) {
            $result->setStatus(Result::STATUS_ERROR);
            $result->messages[] = new NotOk(
                $this->stringValue($errorNode, 'Code'),
                $this->stringValue($errorNode, 'DescText')
            );
        }

        if ($unwrapped = isset($result->response->Response) ? $result->response->Response : null) {
            $result->response = $unwrapped;
        }

        return $result;
    }

    /**
     * Collect every error node from the possible NDC locations and shapes.
     *
     * Handles, both at the document root and nested under Response:
     *  - Error                                   (one or more)
     *  - Errors -> Error                         (wrapped, one or more)
     *  - Errors carrying Code/DescText directly  (flat NDC error)
     *
     * The response object is the SoapClient-decoded graph (stdClass / array).
     *
     * @param \stdClass|array|null $responseObject
     * @return array
     */
    private function collectErrorNodes($responseObject)
    {
        $nodes = [];

        if (!is_object($responseObject)) {
            return $nodes;
        }

        $containers = [$responseObject];
        if (isset($responseObject->Response) && is_object($responseObject->Response)) {
            $containers[] = $responseObject->Response;
        }

        foreach ($containers as $container) {
            if (isset($container->Error)) {
                foreach ($this->toArray($container->Error) as $error) {
                    if (is_object($error)) {
                        $nodes[] = $error;
                    }
                }
            }

            if (isset($container->Errors)) {
                foreach ($this->toArray($container->Errors) as $errors) {
                    if (!is_object($errors)) {
                        continue;
                    }

                    if (isset($errors->Error)) {
                        foreach ($this->toArray($errors->Error) as $error) {
                            if (is_object($error)) {
                                $nodes[] = $error;
                            }
                        }
                    } elseif (isset($errors->Code) || isset($errors->DescText)) {
                        $nodes[] = $errors;
                    }
                }
            }
        }

        return $nodes;
    }

    /**
     * @param mixed $value
     * @return array
     */
    private function toArray($value)
    {
        return is_array($value) ? $value : [$value];
    }

    /**
     * Safely read a scalar property from an error node.
     *
     * @param \stdClass $node
     * @param string $property
     * @return string
     */
    private function stringValue($node, $property)
    {
        return isset($node->$property) && is_scalar($node->$property)
            ? (string) $node->$property
            : '';
    }
}
