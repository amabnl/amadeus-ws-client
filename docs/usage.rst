=====
Usage
=====
***********************
Get access from Amadeus
***********************
First, get a WSDL by requesting a WSAP (Web Service Access Point) for your project from Amadeus. You will probably have to go through your Amadeus sales channel and start a web services project with Amadeus.

See `the Amadeus Web Services website <https://webservices.amadeus.com/>`_ for more information.

The basic pieces of information you will need to use this library are:

- **The WSDL file with all its includes**: You can just extract the ZIP file you received from Amadeus to a location on your filesystem where the client can access it.
- **The authentication information required to start a session**: Office ID, Originator, Organization ID, Password Length, Password Data.

You usually receive this information after the project kick-off has been done and a support person has been assigned to your project.

******************************
Install library in PHP project
******************************
Install the client library in your PHP project by requiring the package with Composer:

``composer require amabnl/amadeus-ws-client``

********************
Set up a test client
********************

.. code-block:: php

    <?php

    use Amadeus\Client;
    use Amadeus\Client\RequestOptions\PnrRetrieveOptions;

    //Set up the client with necessary parameters:

    $params = new ClientParams([
        'sessionHandlerParams' => [
            'soapHeaderVersion' => Client::HEADER_V4, //This is the default value, can be omitted.
            'wsdl' => '/home/user/mytestproject/data/amadeuswsdl/1ASIWXXXXXX_PDT_20160101_080000.wsdl',
            'stateful' => false,
            'logger' => new Psr\Log\NullLogger(),
            'authParams' => [
                'officeId' => 'BRUXX1111', //The Amadeus Office Id you want to sign in to - must be open on your WSAP.
                'originator' => 'WSBENXXX', // also known as 'User ID' for Soap Header 4 WSDL's
                'passwordData' => 'dGhlIHBhc3N3b3Jk', // base 64 encoded password
                'nonceBase' => 'random string for generating unique nonce' //optional but recommended - to avoid collisions with other users of this library.
                'organizationId' => 'NMC-BENELU', // Soap Header 1&2 only!
                'passwordLength' => '12', // Soap Header 1&2 only!
            ]
        ],
        'requestCreatorParams' => [
            'originatorOfficeId' => 'BRUXX1111', // Same office ID as in authParams
            'receivedFrom' => 'my test project' // The "Received From" string that will be visible in PNR History
        ]
    ]);

    $client = new Client($params);

    $pnrContent = $client->retrievePnr(
        new PnrRetrieveOptions(['recordLocator' => 'ABC123'])
    );




