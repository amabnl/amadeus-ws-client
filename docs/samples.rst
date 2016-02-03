=======
Samples
=======
Some examples of how you can do specific things and send specific messages.

***********************************************
Switching between stateful & stateless messages
***********************************************
If you do not require an active context in your session, you're better off using stateless messages.

However, for many operations, you'll need an active context (a PNR context, a pricing context, ...).

You can easily switch from stateful to stateless messages at runtime with:

.. code-block:: php

    $client->setStateful(false); //Enable stateless messages

    $client->setStateful(true); //Enable stateful messages

*************************
Ending a stateful session
*************************

After doing multiple calls with a stateful session, there are two ways to end the session:

- do a Security_SignOut call:

.. code-block:: php

    $client->signOut(); //Terminates an active stateful session. There is no active session with stateless messages.

- set an 'endSession' message option on the last call you want to make:

.. code-block:: php

    $client->pnrRetrieve(
        new PnrRetrieveOptions(['recordLocator' => 'ABC123']),
        ['endSession' => true]
    );


*********************
Handling the response
*********************
Sometimes it's useful if there result from the SOAP call gets returned as a PHP object,
sometimes a string containing the XML document of the SOAP-BODY is more useful.

For example, when trying to extract specific information from a PNR, it can be useful to load the
PNR_Reply as a ``\DOMDocument`` and query it using a ``\DOMXPath`` object.

The library supports this through the message option 'asString':

- Retrieving a PNR's contents and requesting the result as a string:

.. code-block:: php

    $client->pnrRetrieve(
        new PnrRetrieveOptions(['recordLocator' => 'ABC123']),
        ['asString' => true] //This is the default setting for the pnrRetrieve() method
    );

- Retrieving a PNR's contents and requesting the result as a PHP Object:

.. code-block:: php

    $client->pnrRetrieve(
        new PnrRetrieveOptions(['recordLocator' => 'ABC123']),
        ['asString' => false]
    );

***
PNR
***
Creating a PNR (simplified example containing only the most basic PNR elements needed to save the PNR):

.. code-block:: php

    $opt = new Client\RequestOptions\PnrCreatePnrOptions();
    $opt->actionCode = 11; //11	End transact with retrieve (ER)
    $opt->travellers[] = new Client\RequestOptions\Pnr\Traveller([
        'number' => 1,
        'firstName' => 'FirstName',
        'lastName' => 'LastName'
    ]);
    $opt->tripSegments[] = new Client\RequestOptions\Pnr\Segment\Miscellaneous([
        'status ' => Client\RequestOptions\Pnr\Segment::STATUS_CONFIRMED,
        'company' => '1A',
        'date' => \DateTime::createFromFormat('Ymd', '20161022', new \DateTimeZone('UTC')),
        'cityCode' => 'BRU',
        'freeText' => 'DUMMY MISCELLANEOUS SEGMENT'
    ]);

    $opt->elements[] = new Client\RequestOptions\Pnr\Element\Ticketing([
        'ticketMode' => 'OK'
    ]);
    $opt->elements[] = new Client\RequestOptions\Pnr\Element\Contact([
        'type' => Client\RequestOptions\Pnr\Element\Contact::TYPE_PHONE_MOBILE,
        'value' => '+3222222222'
    ]);

    $createdPnr = $client->pnrCreatePnr($opt);

Retrieving a PNR:

.. code-block:: php

    $pnrContent = $client->pnrRetrieve(
        new Client\RequestOptions\PnrRetrieveOptions(['recordLocator' => 'ABC123'])
    );

Retrieving a PNR with offers:

.. code-block:: php

    $pnrContent = $client->pnrRetrieveAndDisplay(
        new Client\RequestOptions\PnrRetrieveAndDisplayOptions([
            'recordLocator' => 'ABC123',
            'retrieveOption' => Client\RequestOptions\PnrRetrieveAndDisplayOptions::RETRIEVEOPTION_ALL
        ])
    );

*****
Queue
*****
Get a list of all PNR's on a given queue:

.. code-block:: php



*****
Offer
*****
