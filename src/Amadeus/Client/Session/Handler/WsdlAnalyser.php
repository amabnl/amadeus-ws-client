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

namespace Amadeus\Client\Session\Handler;

use Amadeus\Client\InvalidWsdlFileException;

/**
 * Wsdl Analyser
 *
 * Analyses the given WSDL or WSDL's for the WSAP
 * Extracts available messages & versions from it
 *
 * @package Amadeus\Client\Session\Handler
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class WsdlAnalyser
{
    /**
     * XPATH query to retrieve all operations from a WSDL
     *
     * @var string
     */
    const XPATH_ALL_OPERATIONS = '/wsdl:definitions/wsdl:portType/wsdl:operation/@name';

    /**
     * XPATH query to retrieve WSDL imports from a WSDL.
     */
    const XPATH_IMPORTS = '/wsdl:definitions/wsdl:import/@location';

    /**
     * XPATH query to retrieve the full operation name + version from a WSDL for a given operation.
     *
     * @var string
     */
    const XPATH_VERSION_FOR_OPERATION = "string(/wsdl:definitions/wsdl:message[contains(./@name, '%s_')]/@name)";

    /**
     * Alternate XPATH query to retrieve the full operation name + version from a WSDL for a given operation
     *
     * Necessary for "interface" wsdls
     *
     * @var string
     */
    const XPATH_ALT_VERSION_FOR_OPERATION = "string(//wsdl:operation[contains(./@name, '%s')]/wsdl:input/@message)";

    /**
     * List of WSDL ID's and their path
     *
     * format:
     * [
     *      '7d36c7b8' => '/path/to/wsdl.wsdl'
     * ]
     *
     * @var array
     */
    public static $wsdlIds = [];

    /**
     * Dom Document array where the WSDL's contents will be loaded
     *
     * format:
     * [
     *     '7d36c7b8' => \DOMDocument,
     *     '7e84f2537' => \DOMDocument
     * ]
     *
     * @var array
     */
    protected static $wsdlDomDoc;

    /**
     * To query the WSDL contents
     *
     * format:
     * [
     *     '7d36c7b8' => \DOMXpath,
     *     '7e84f2537' => \DOMXpath
     * ]
     *
     * @var array
     */
    protected static $wsdlDomXpath;

    /**
     * Loads messages & versions from WSDL.
     *
     * @param $wsdls
     * @return array
     */
    public static function loadMessagesAndVersions($wsdls)
    {
        $msgAndVer = [];

        foreach ($wsdls as $wsdl) {
            $wsdlIdentifier = self::makeWsdlIdentifier($wsdl);

            self::$wsdlIds[$wsdlIdentifier] = $wsdl;

            self::loadWsdlXpath($wsdl, $wsdlIdentifier);

            $operations = self::$wsdlDomXpath[$wsdlIdentifier]->query(self::XPATH_ALL_OPERATIONS);
            if ($operations->length === 0) {
                //No operations found - are there any external WSDLs being imported?
                $imports = self::$wsdlDomXpath[$wsdlIdentifier]->query(self::XPATH_IMPORTS);
                $operations = [];

                foreach ($imports as $import) {
                    if (!empty($import->value)) {
                        $tmpMsg = self::getMessagesAndVersionsFromImportedWsdl(
                            $import->value,
                            $wsdl,
                            $wsdlIdentifier
                        );
                        foreach ($tmpMsg as $msgName => $msgInfo) {
                            $msgAndVer[$msgName] = $msgInfo;
                        }
                    }
                }
            }

            $msgAndVer = array_merge(
                $msgAndVer,
                self::loopOperationsWithQuery(
                    $operations,
                    self::XPATH_VERSION_FOR_OPERATION,
                    $wsdlIdentifier,
                    self::$wsdlDomXpath[$wsdlIdentifier]
                )
            );
        }

        return $msgAndVer;
    }

    /**
     * Get Messages & Versions from an imported WSDL file
     *
     * Imported wsdl's are a little different, they require a different query
     * to extract the version nrs.
     *
     * @param string $import
     * @param string $wsdlPath
     * @param string $wsdlIdentifier
     * @return array
     * @throws InvalidWsdlFileException when the WSDL import could not be loaded.
     */
    protected static function getMessagesAndVersionsFromImportedWsdl($import, $wsdlPath, $wsdlIdentifier)
    {
        $msgAndVer = [];
        $domXpath = null;

        $importPath = realpath(dirname($wsdlPath)).DIRECTORY_SEPARATOR.$import;
        $wsdlContent = file_get_contents($importPath);

        if ($wsdlContent !== false) {
            $domDoc = new \DOMDocument('1.0', 'UTF-8');
            $ok = $domDoc->loadXML($wsdlContent);

            if ($ok === true) {
                $domXpath = new \DOMXPath($domDoc);
                $domXpath->registerNamespace(
                    'wsdl',
                    'http://schemas.xmlsoap.org/wsdl/'
                );
                $domXpath->registerNamespace(
                    'soap',
                    'http://schemas.xmlsoap.org/wsdl/soap/'
                );
            }
        } else {
            throw new InvalidWsdlFileException('WSDL '.$importPath.' import could not be loaded');
        }

        if ($domXpath instanceof \DOMXPath) {
            $nodeList = $domXpath->query(self::XPATH_ALL_OPERATIONS);

            $msgAndVer = array_merge(
                $msgAndVer,
                self::loopOperationsWithQuery(
                    $nodeList,
                    self::XPATH_ALT_VERSION_FOR_OPERATION,
                    $wsdlIdentifier,
                    $domXpath
                )
            );
        }

        return $msgAndVer;
    }

    /**
     * Load the WSDL contents to a queryable DOMXpath.
     *
     * @param string $wsdlFilePath
     * @param string $wsdlId
     * @uses $this->wsdlDomDoc
     * @uses $this->wsdlDomXpath
     * @throws InvalidWsdlFileException when WSDL cannot be found.
     */
    public static function loadWsdlXpath($wsdlFilePath, $wsdlId)
    {
        if (!isset(self::$wsdlDomXpath[$wsdlId]) || is_null(self::$wsdlDomXpath[$wsdlId])) {
            $wsdlContent = file_get_contents($wsdlFilePath);

            if ($wsdlContent !== false) {
                self::$wsdlDomDoc[$wsdlId] = new \DOMDocument('1.0', 'UTF-8');
                self::$wsdlDomDoc[$wsdlId]->loadXML($wsdlContent);
                self::$wsdlDomXpath[$wsdlId] = new \DOMXPath(self::$wsdlDomDoc[$wsdlId]);
                self::$wsdlDomXpath[$wsdlId]->registerNamespace(
                    'wsdl',
                    'http://schemas.xmlsoap.org/wsdl/'
                );
                self::$wsdlDomXpath[$wsdlId]->registerNamespace(
                    'soap',
                    'http://schemas.xmlsoap.org/wsdl/soap/'
                );
            } else {
                throw new InvalidWsdlFileException('WSDL '.$wsdlFilePath.' could not be loaded');
            }
        }
    }

    /**
     * extractMessageVersion
     *
     * extracts "4.1" from a string like "Security_SignOut_4_1"
     * or "1.00" from a string like "tns:AMA_MediaGetMediaRQ_1.000"
     *
     * @param string $fullVersionString
     * @return string
     */
    protected static function extractMessageVersion($fullVersionString)
    {
        $marker = strpos($fullVersionString, '_', strpos($fullVersionString, '_') + 1);

        $num = substr($fullVersionString, $marker + 1);

        return str_replace('_', '.', $num);
    }

    /**
     * Generates a unique identifier for a wsdl based on its path.
     *
     * @param string $wsdlPath
     *
     * @return string
     */
    protected static function makeWsdlIdentifier($wsdlPath)
    {
        return sprintf('%x', crc32($wsdlPath));
    }

    /**
     * Evaluate an XPATH query on a given WSDL
     *
     * @param string $wsdlId
     * @param string $wsdlFilePath
     * @param string $xpath XPATH query
     * @return string|null
     */
    public static function exaluateXpathQueryOnWsdl($wsdlId, $wsdlFilePath, $xpath)
    {
        WsdlAnalyser::loadWsdlXpath($wsdlFilePath, $wsdlId);

        return self::$wsdlDomXpath[$wsdlId]->evaluate($xpath);
    }

    /**
     * Loop all extracted operations from a wsdl and find their message versions
     *
     * @param \DOMNodeList $operations
     * @param string $query
     * @param string $wsdlIdentifier
     * @param \DOMXPath $domXpath
     * @return array
     */
    protected static function loopOperationsWithQuery($operations, $query, $wsdlIdentifier, $domXpath)
    {
        $msgAndVer = [];

        foreach ($operations as $operation) {
            if (!empty($operation->value)) {
                $fullVersion = $domXpath->evaluate(
                    sprintf($query, $operation->value)
                );

                if (!empty($fullVersion)) {
                    $extractedVersion = self::extractMessageVersion($fullVersion);
                    $msgAndVer[$operation->value] = [
                        'version' => $extractedVersion,
                        'wsdl' => $wsdlIdentifier
                    ];
                }
            }
        }

        return $msgAndVer;
    }
}
