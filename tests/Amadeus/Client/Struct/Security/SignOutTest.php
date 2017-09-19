<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Test\Amadeus\Client\Struct\Security;

use Amadeus\Client\Struct\Security\SignOut;
use Test\Amadeus\BaseTestCase;

/**
 * SignOutTest
 *
 * @package Test\Amadeus\Client\Struct\Security
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SignOutTest extends BaseTestCase
{
    public function testCanCreateSecuritySignOut()
    {
        $msg = new SignOut();

        $this->assertInstanceOf('\Amadeus\Client\Struct\Security\SignOut', $msg);
    }
}
