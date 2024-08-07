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

namespace Test\Amadeus\Client\Struct\Fop;

use Amadeus\Client\RequestOptions\Fop\ThreeDSecureInfo;
use Amadeus\Client\Struct\Fop\Communication;
use Amadeus\Client\Struct\Fop\TdsInformation;
use Test\Amadeus\BaseTestCase;

/**
 * TdsInformationTest
 *
 * @package Test\Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TdsInformationTest extends BaseTestCase
{
    public function testCanConstructWithAcsUrl()
    {
        $pares = <<<EOT
ZUp6ZFdGbVBvMGdTZnU5ZlVhcDV0SG80aksdV3k2UGt2ZzhiYk1NYkJneVkyMkN1WDcvcGNuZFZ6V3ozcW1kblY5clpVaUVuWDBiR2tSSHhrYkQdcmMvU3B6YTQxbkdSdnp4anY2TFBUMEh1Rlg2Y2h5L1Bsc2wdWGo3L3R2bTBOcU5yRU5DN3dMdGRnODFhQ2VyYURZT24ySDk1MXNFMnFEN250OXByMjdKSh0vQjVzNzVqOWV1c3BNZjJXU01zVk03RjdjanZzRzRlUVlHdkJqZlEzcS80R3ZsMkN4VmZ2Y2pObTgzYTlTcFNVRGZFQWx1Z3N6WHk5WGFkQlZlQjNxeFdLTHFZcjVISDNScDVYNmJmN3FNYR10akgva1lqT3RRNk54bGZEald6aXdNbVZ5aEEyNk5tR2k5cjVDNng5dDBtMk9Bb05rVnhkUEdFb2w5ZS85ZklLNzR1Nx1wQVZ0eWdiZ3hGN3hNZm9UWGNqaXZjcm1Heldpelh5TnZkT3VqTElnHWdCQXp1YmJ4RzNyMHIzWHlEL3Y1dkJYVkRkRzBlTh1zbXpyNTZOVVdKSnd6N01sdDhJYUNGVjN4ZE4yNXpxemYyR3ZrNldudHUyMjRVR25RL3VHQzByeUxyd0lzMzZOMHAdUHU2Q3FSaGNZMmJLTnRnRDVsM1lJM2NYVUZlazdsWjcdSXdoOGF1d1JPc21MeB1lWTZhcHZ5Q0lGM1gvZHBOZnkydUlZTERLQkIwaFVBQnY0N0RYNTRmcXdKZnlNL0ZuMXBHdVhtUng1NmJ4cVBid01wUWdpWXEvS2MzMzc2bnh0emVOV0hJbHFFHVExV2ZQWXpJUDk4UmRJck5ucEVQRWZ5TXRqODZkYTNkejNYa1luZEYyHUFjM0JNZFBGbGI0ZVg1bHg4VU9SMkhRZDM4TzhhHUdYcG8yTHZwTGRpSWs3M05tVnYyYXFZOUp0U2FBZVlEVmpyTEtWdkE3SDZVWENOdkRzTHhld1kdN01CRFVQWnA4eUMzTno5QnJFeGRUVjB5c0U3bnNJc1p1NnFTcFNheDhZNFZMOXU5ZTF1bEhaTTFsQy9oc1g2MEN2WWNlZjN0RTNvcVNHTWVUMUpMUEVUT3VjSjFidDdLM2NBR2VPVmlSTk1OdDc0T0E4L25zR1RKN09jajJGWGNvdE11aB1RVUxRbnBVOEJFYzk5b2k5N2lkN2ttaGQ1ZUZqbWRIN0hiYlYvb3hHVnZKZ29lcTBWZWRTHVBVRDY0djVhQzRSSFhjWWF1YUxkeEh5TXF1RGJ4R1ZZUDdHQkZFT2pNcENqUVNTSG9CQktFZ2doMGRuTE9xa1FYNlVBQktFZEJwM2JDYVVvYkRFa2FGbEFFVGxXTXVxTU1tOTRiQnNkMEltZGRHRmtCQ1Fjd2k2RkloVkpvdTZkTklKT2h1aWVCWjVMTXJMUnhxMmRvb0Qyd3dpUlJ0ckVQMjlRL0tqMVBBL2VCMXlhRFF5ekdCb2RERzU5clV2bmdERFlzR3Z1WWZOQ3BtQ1JMUm9ISjlNSUlJakpNcWlpSnVWV0hrcFRCSEZnZXprSGRKMjZiR3R5Ym5adU5yeHFGSkk2MHljQx1ad2JGaE5lZDhmWUZ4SVE3MW12dldCZmFhRR1OUUh6WXRFMlFPTHNQc1F0TUozVHFoVkVWVUwvR1R2WUtZMmJweFRFWlF3SEVZejhpaFRjT1dPUndlOHdiR1U4aGl3ZmVLeWJFY1Brb0pxY2RocnE0MVRpNUdNbkgxN2plZEFLbzAdTFltWEpoTGdyRmZNeUhDTGEyNWNFNXFLZUdGN0MvMlJsbWx4T09kcUd6N0dqREZxWENFYUxXVThGckRnRU40N3JuVVFRRlJ3Sk5PazQ1SGQwR1BEa2NnOE53VW9FSR1NakRMaE90ZDVLYnRRMlVoZXNUUVdCanN1S1pwY3NBSTlWTEsdUEgzdHYxeXhVcEt1clJSVk5GWWhBUTZxVTVQVWRjZlRuRUpKdmhGZHFMMUdCZ0ZlbTB5V1RRcENGQlk0cFd6R3BVcXNaRHpHb28vQXFXNE1sTjlsUnZhZkdNT2hHSDBSdnJSdFRhWW9ZcTZmV1l1d0l0NnlCVVNBQzRTM2dtdXZEOE5ROWIxQ1NCMEFFYTFHU1lobEVTa2xFYlVjQkFHYjYzZlhOS3BxZFVOVTJLNUozRExQVXlGblVPUm1qZx04SG4wc3c5cUpIUFdSMy8wS1dScE0yd2dsTHQ5S2h4eEZHUmZUYWQ3cHBMbi9QakNnMk9jTi9DZXozd080VUpuUkR1OFNFSzdTQUpiUVBXQy9NejlmSlhhHU50UGF3Ti9sNGJWclpxZmZyUDFnZE9VUWJkbi9OWlZkS0RNVFdCHXJHUGdHR3g0TjZ1QmpBNWJjcnRZHU80RDA4VFoyc2Q2ZFIzOGFLYW91bEVUcTY0SDBlNVVPTEIxZ1pSdFY4Nmd6YTE2bE5PU2pkV2taTHVzajhXVnpmbXlKYXNXWmxNOFZoUkIvMGFFQU9WRTdTbVQycHppbTFYVy95bx1aUWZLMzFxVk5KMUtVM21mZ0tmZE5QRlJaenR1bGhobXJ4R2pZeXNSY1ByOGFGQXh6VEN5Tk81SERXaUxBbnhhUFRoQ3lUQ1A3TGM5MmlQeWkdUTlzTGdlN1RuLzREMnlKHWh2YjlLY2UvckljVmhNTjE3c1QxTmpmOEF6VEc5d3Y2UjVsNngveTJhTS85TE5DZTI4M0JINEpnbnd3ZTVaMGV0anM3M29aZDFIclZWcVNKZldIcmVaejZ1MnFYWFdJTkRrclA1Y1hHMHE2bWlPcGZnT3FJRUlVZ01JUGFJch1xbm02YUl6WFVsbERaL25WZ2FFUUcvd1NSUUM5SktrQzg3HUppVzVrYkRxHWhKQmxKaXVyT1pjN0dtempsRnNiWmQzVExlTUlObGZLSm4xTkk4aHZZHVFBeGRaUGZ5MFpEYWJ6UjNyd1VGZFBmOThabk9ZQlVBOGZNL3hjbx1ZbVhBQ1ZscHpuYmNwNnVWVkZwTTRLUGMyV1dwYW5rOU9uR1hxa002NDdVYkVjb2pQU3IxTGp5aG96SUMwaXBLY0UyMHJyMk5oS1NQbnE1TFV3d3kvTVdZaVRMaDRBdHM3UGxKbVNYVFFnOVZkbEpjQW1TWEphZ3poQVlYSWFuTkZ0Y0ZnHVN0aTdZbWhzZ1ZzY0lXYmpqSjhuT3VuL2FMNkVCcjU0SExaNWk0akJkNW1hWW84Wk50U3A5bzJLYk40WHR0NnYwZnRtbXZtbURRZnQdbTM3Qy8yZFBsWjA4ZndKdWxXU09rcHhNUVdzTVptcEpTM2NvVW5NTjJIUEtHc2psYTN1VlZ5bnZYUU51bjhybmRNZkVKZ0hZZUpScDhueWl2aGpNZExYUUp6HU5USVBSQXRKYTRFbWhNSkRLRHM1ekx1Z0lXNWx6TjI3bWEwV2QdbjAxbTJZRHdZcmgwUjBSZHFRaFFEdGZDbnBPTzBIdXAzVGxZZC9NdXR6bDFpYmJIUUxMbnF0LzR4alppMzA0ZlFkS0ZnZjM3RTRPcXhLUFZhbXE1SzEwWld4NjBvaGNYazBvOFR5NHdWdnp0eE9EQ2s0dDdBLzlLdnJBNXZoUTQ1dTlXeTJHWWdPVnhObkVyWTVtb1hVZURiNmN6cXdNZFF5TGpqMDRZMUlST0lsblYdWUc4MVpyRB0dSzF6Y0ltUDQxWjcvSXhDN21aZGtGS0hLUEJtdDZXQmIdU3FPbGlFb0hWMFY4MkpvSUZ6Rnd5allPOHo4dnd5bUFxcHRCRmRBSR1qHTd5VUo3cjZXcUdsd3VQTlNVZkNwNVQ1cFkzTEtMcEM1MUZ6OXdjMzBaelNyRldPTGFFMUxZNlUveXVtHVJqYm5IZDlsakFiRHUzc3h2eHFYUmJkTjg5WVNEdjcxakkyM3ZYHXh2WjYwZVkxNDlDOTg4R0h6OFdmZm9ISG5JRnpBPT0=
EOT;

        $options = new ThreeDSecureInfo([
            'veresStatus' => ThreeDSecureInfo::VERES_AUTHENTICATION_AVAILABLE,
            'creditCardCompany' => ThreeDSecureInfo::CC_COMP_MASTERCARD_DIRECTORY_SERVER,
            'transactionIdentifier' => 'TzR3MFVmdG1IcHlzRVNpZUVuTUNBRFl6T1RRPQ==',
            'transactionIdentifierLength' => 28, //OPTIONAL, CAN BE OMITTED
            'paresAuthResponse' => $pares,
            'acsUrl' => 'http://dummy.acs.url'
        ]);

        $obj = new TdsInformation($options);

        $this->assertEquals('http://dummy.acs.url', $obj->acsURL->communication->internetAddress);
        $this->assertEquals(Communication::QUAL_WWW, $obj->acsURL->communication->adressQualifier);
    }

    public function testCanConstructWithThreeDSecureVersionTwo()
    {
        $options = new ThreeDSecureInfo([
            'transactionsStatus' => ThreeDSecureInfo::PARES_AUTHENTICATION_SUCCESSFUL,
            'tdsVersion' => '2.0.1',
            'creditCardCompany' => ThreeDSecureInfo::CC_COMP_VISA_DIRECTORY_SERVER,
            'authenticationIndicator' => '05',
            'tdsServerTransactionId' => 'U0RTRzNTRUczNEdTR1NFUldXRkNXRkRXRUZFRw==',
            'tdsServerTransactionIdLength' => 28,
            'directoryServerTransactionId' => 'Q2pENDJ0Tll0WlZ6VFcwSEVvdDVIRGt4TXpFPQ',
            'directoryServerTransactionIdLength' => 28,
            'tdsAuthenticationVerificationCode' => 'QUFBQkJYbGprUUFBQUFBRUFXT1JBQUFBQUFBPQ',
            'tdsAuthenticationVerificationCodeLength' => 28,
            'tdsAuthenticationVerificationCodeReference' => ThreeDSecureInfo::AUTHENTICATION_VERIFICATION_CODE_VISA
        ]);

        $obj = new TdsInformation($options);

        $this->assertEquals(ThreeDSecureInfo::PARES_AUTHENTICATION_SUCCESSFUL, $obj->authenticationData->authenticationDataDetails->transStatus);
        $this->assertEquals('2.0.1', $obj->authenticationData->tdsVersion);
        $this->assertEquals('U0RTRzNTRUczNEdTR1NFUldXRkNXRkRXRUZFRw==', $obj->tdsBlobData[0]->tdsBlbData->binaryData);
        $this->assertEquals('QUFBQkJYbGprUUFBQUFBRUFXT1JBQUFBQUFBPQ', $obj->tdsBlobData[1]->tdsBlbData->binaryData);
        $this->assertEquals('Q2pENDJ0Tll0WlZ6VFcwSEVvdDVIRGt4TXpFPQ', $obj->tdsBlobData[2]->tdsBlbData->binaryData);
    }
}
