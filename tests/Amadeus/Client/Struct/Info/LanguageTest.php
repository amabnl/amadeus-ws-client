<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Test\Amadeus\Client\Struct\Info;

use Amadeus\Client\Struct\Info\Language;
use Test\Amadeus\BaseTestCase;

/**
 * LanguageTest
 *
 * @package Test\Amadeus\Client\Struct\Info
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class LanguageTest extends BaseTestCase
{
    public function testCanConstruct()
    {
        //Sweet sweet 100% coverage

        $language = new Language('EN');

        $this->assertEquals(Language::QUAL_LANGUAGE_TRANSACTIONAL, $language->languageCodeQualifier);
        $this->assertEquals('EN', $language->languageDetails->languageCode);
    }

}
