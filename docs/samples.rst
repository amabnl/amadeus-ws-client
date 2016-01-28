=======
Samples
=======
Some examples of how you can do specific things and send specific messages.

***********************************************
Switching between stateful & stateless messages
***********************************************
If you do not require an active context in your session, you're better off using stateless messages.

However, for many operations, you'll need an active context (a PNR context, a pricing context, ...).

You can easily switch from stateful to stateless messages at runtime with

.. code-block:: php

    $client->setStateful(false); //Enable stateless messages

    $client->setStateful(true); //Enable stateful messages

********
Security
********

.. code-block:: php

    $client->signOut(); //Terminates an active stateful session. There is no active session with stateless messages.

***
PNR
***

*****
Queue
*****

*****
Offer
*****
