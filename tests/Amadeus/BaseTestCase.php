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
 * @package Test\Amadeus
 * @license https://opensource.org/licenses/Apache-2.0 Apache 2.0
 */

namespace Test\Amadeus;

/**
 * BaseTestCase
 *
 * @package Test\Amadeus
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Get a protected or private method from given class
     *
     * @param string $helper
     * @param string $name
     * @return \ReflectionMethod
     */
    protected static function getMethod($helper, $name)
    {
        $method = new \ReflectionMethod($helper, $name);
        $method->setAccessible(true);
        return $method;
    }

    /**
     * Get a protected or private property from given class
     *
     * @param string $helper
     * @param string $name
     * @return \ReflectionProperty
     */
    protected static function getProperty($helper, $name)
    {
        $property = new \ReflectionProperty($helper, $name);
        $property->setAccessible(true);
        return $property;
    }

    /**
     * @param $fileName
     * @return string
     */
    protected function getTestFile($fileName)
    {
        $reflector = new \ReflectionClass(get_class($this));
        $path = dirname($reflector->getFileName());

        $fullPath = realpath($path . DIRECTORY_SEPARATOR . "testfiles" . DIRECTORY_SEPARATOR . $fileName);
        return file_get_contents($fullPath);
    }

    /**
     * @param $theArray
     * @param $theObject
     * @return bool
     */
    protected function assertArrayContainsSameObject($theArray, $theObject)
    {
        foreach($theArray as $arrayItem) {
            if($arrayItem == $theObject) {
                return true;
            }
        }
        return false;
    }
}