================================
FOP_CreateFormOfPayment examples
================================

.. contents::

3D-Secure payment
=================

Query and response for handling 3DS data.

*(Operation 5.1 from the FOP_CreateFormOfPayment docs)*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\ElementRef;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;
    use Amadeus\Client\RequestOptions\Fop\CreditCardInfo;
    use Amadeus\Client\RequestOptions\Fop\ThreeDSecureInfo;


    $options = new FopCreateFopOptions([
        'transactionCode' => FopCreateFopOptions::TRANS_AUTH_ON_TICKET_MCO_EMD,
        'fopGroup' => [
            new Group([
                'elementRef' => [
                    new ElementRef([
                        'type' => ElementRef::TYPE_TST_NUMBER,
                        'value' => 1
                    ])
                ],
                'mopInfo' => [
                    new MopInfo([
                        'sequenceNr' => 1,
                        'fopCode' => 'CCCA',
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'payMerchant' => 'AY',
                        'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                        'creditCardInfo' => new CreditCardInfo([
                            'vendorCode' => 'CA',
                            'cardNumber' => '5000000000000009',
                            'expiryDate' => '1014',
                            'securityId' => 123,
                            'threeDSecure' => new ThreeDSecureInfo([
                                'veresStatus' => ThreeDSecureInfo::VERES_AUTHENTICATION_AVAILABLE,
                                'creditCardCompany' => ThreeDSecureInfo::CC_COMP_MASTERCARD_DIRECTORY_SERVER,
                                'transactionIdentifier' => 'TzR3MFVmdG1IcHlzRVNpZUVuTUNBRFl6T1RRPQ==',
                                'transactionIdentifierLength' => 28, //OPTIONAL, LENGTH CAN BE OMITTED
                                'paresAuthResponse' => 'ZUp6ZFdGbVBvMGdTZnU5ZlVhcDV0SG80aksdV3k2UGt2ZzhiYk1NYkJneVkyMkN1WDcvcGNuZFZ6V3ozcW1kblY5clpVaUVuWDBiR2tSSHhrYkQdcmMvU3B6YTQxbkdSdnp4anY2TFBUMEh1Rlg2Y2h5L1Bsc2wdWGo3L3R2bTBOcU5yRU5DN3dMdGRnODFhQ2VyYURZT24ySDk1MXNFMnFEN250OXByMjdKSh0vQjVzNzVqOWV1c3BNZjJXU01zVk03RjdjanZzRzRlUVlHdkJqZlEzcS80R3ZsMkN4VmZ2Y2pObTgzYTlTcFNVRGZFQWx1Z3N6WHk5WGFkQlZlQjNxeFdLTHFZcjVISDNScDVYNmJmN3FNYR10akgva1lqT3RRNk54bGZEald6aXdNbVZ5aEEyNk5tR2k5cjVDNng5dDBtMk9Bb05rVnhkUEdFb2w5ZS85ZklLNzR1Nx1wQVZ0eWdiZ3hGN3hNZm9UWGNqaXZjcm1Heldpelh5TnZkT3VqTElnHWdCQXp1YmJ4RzNyMHIzWHlEL3Y1dkJYVkRkRzBlTh1zbXpyNTZOVVdKSnd6N01sdDhJYUNGVjN4ZE4yNXpxemYyR3ZrNldudHUyMjRVR25RL3VHQzByeUxyd0lzMzZOMHAdUHU2Q3FSaGNZMmJLTnRnRDVsM1lJM2NYVUZlazdsWjcdSXdoOGF1d1JPc21MeB1lWTZhcHZ5Q0lGM1gvZHBOZnkydUlZTERLQkIwaFVBQnY0N0RYNTRmcXdKZnlNL0ZuMXBHdVhtUng1NmJ4cVBid01wUWdpWXEvS2MzMzc2bnh0emVOV0hJbHFFHVExV2ZQWXpJUDk4UmRJck5ucEVQRWZ5TXRqODZkYTNkejNYa1luZEYyHUFjM0JNZFBGbGI0ZVg1bHg4VU9SMkhRZDM4TzhhHUdYcG8yTHZwTGRpSWs3M05tVnYyYXFZOUp0U2FBZVlEVmpyTEtWdkE3SDZVWENOdkRzTHhld1kdN01CRFVQWnA4eUMzTno5QnJFeGRUVjB5c0U3bnNJc1p1NnFTcFNheDhZNFZMOXU5ZTF1bEhaTTFsQy9oc1g2MEN2WWNlZjN0RTNvcVNHTWVUMUpMUEVUT3VjSjFidDdLM2NBR2VPVmlSTk1OdDc0T0E4L25zR1RKN09jajJGWGNvdE11aB1RVUxRbnBVOEJFYzk5b2k5N2lkN2ttaGQ1ZUZqbWRIN0hiYlYvb3hHVnZKZ29lcTBWZWRTHVBVRDY0djVhQzRSSFhjWWF1YUxkeEh5TXF1RGJ4R1ZZUDdHQkZFT2pNcENqUVNTSG9CQktFZ2doMGRuTE9xa1FYNlVBQktFZEJwM2JDYVVvYkRFa2FGbEFFVGxXTXVxTU1tOTRiQnNkMEltZGRHRmtCQ1Fjd2k2RkloVkpvdTZkTklKT2h1aWVCWjVMTXJMUnhxMmRvb0Qyd3dpUlJ0ckVQMjlRL0tqMVBBL2VCMXlhRFF5ekdCb2RERzU5clV2bmdERFlzR3Z1WWZOQ3BtQ1JMUm9ISjlNSUlJakpNcWlpSnVWV0hrcFRCSEZnZXprSGRKMjZiR3R5Ym5adU5yeHFGSkk2MHljQx1ad2JGaE5lZDhmWUZ4SVE3MW12dldCZmFhRR1OUUh6WXRFMlFPTHNQc1F0TUozVHFoVkVWVUwvR1R2WUtZMmJweFRFWlF3SEVZejhpaFRjT1dPUndlOHdiR1U4aGl3ZmVLeWJFY1Brb0pxY2RocnE0MVRpNUdNbkgxN2plZEFLbzAdTFltWEpoTGdyRmZNeUhDTGEyNWNFNXFLZUdGN0MvMlJsbWx4T09kcUd6N0dqREZxWENFYUxXVThGckRnRU40N3JuVVFRRlJ3Sk5PazQ1SGQwR1BEa2NnOE53VW9FSR1NakRMaE90ZDVLYnRRMlVoZXNUUVdCanN1S1pwY3NBSTlWTEsdUEgzdHYxeXhVcEt1clJSVk5GWWhBUTZxVTVQVWRjZlRuRUpKdmhGZHFMMUdCZ0ZlbTB5V1RRcENGQlk0cFd6R3BVcXNaRHpHb28vQXFXNE1sTjlsUnZhZkdNT2hHSDBSdnJSdFRhWW9ZcTZmV1l1d0l0NnlCVVNBQzRTM2dtdXZEOE5ROWIxQ1NCMEFFYTFHU1lobEVTa2xFYlVjQkFHYjYzZlhOS3BxZFVOVTJLNUozRExQVXlGblVPUm1qZx04SG4wc3c5cUpIUFdSMy8wS1dScE0yd2dsTHQ5S2h4eEZHUmZUYWQ3cHBMbi9QakNnMk9jTi9DZXozd080VUpuUkR1OFNFSzdTQUpiUVBXQy9NejlmSlhhHU50UGF3Ti9sNGJWclpxZmZyUDFnZE9VUWJkbi9OWlZkS0RNVFdCHXJHUGdHR3g0TjZ1QmpBNWJjcnRZHU80RDA4VFoyc2Q2ZFIzOGFLYW91bEVUcTY0SDBlNVVPTEIxZ1pSdFY4Nmd6YTE2bE5PU2pkV2taTHVzajhXVnpmbXlKYXNXWmxNOFZoUkIvMGFFQU9WRTdTbVQycHppbTFYVy95bx1aUWZLMzFxVk5KMUtVM21mZ0tmZE5QRlJaenR1bGhobXJ4R2pZeXNSY1ByOGFGQXh6VEN5Tk81SERXaUxBbnhhUFRoQ3lUQ1A3TGM5MmlQeWkdUTlzTGdlN1RuLzREMnlKHWh2YjlLY2UvckljVmhNTjE3c1QxTmpmOEF6VEc5d3Y2UjVsNngveTJhTS85TE5DZTI4M0JINEpnbnd3ZTVaMGV0anM3M29aZDFIclZWcVNKZldIcmVaejZ1MnFYWFdJTkRrclA1Y1hHMHE2bWlPcGZnT3FJRUlVZ01JUGFJch1xbm02YUl6WFVsbERaL25WZ2FFUUcvd1NSUUM5SktrQzg3HUppVzVrYkRxHWhKQmxKaXVyT1pjN0dtempsRnNiWmQzVExlTUlObGZLSm4xTkk4aHZZHVFBeGRaUGZ5MFpEYWJ6UjNyd1VGZFBmOThabk9ZQlVBOGZNL3hjbx1ZbVhBQ1ZscHpuYmNwNnVWVkZwTTRLUGMyV1dwYW5rOU9uR1hxa002NDdVYkVjb2pQU3IxTGp5aG96SUMwaXBLY0UyMHJyMk5oS1NQbnE1TFV3d3kvTVdZaVRMaDRBdHM3UGxKbVNYVFFnOVZkbEpjQW1TWEphZ3poQVlYSWFuTkZ0Y0ZnHVN0aTdZbWhzZ1ZzY0lXYmpqSjhuT3VuL2FMNkVCcjU0SExaNWk0akJkNW1hWW84Wk50U3A5bzJLYk40WHR0NnYwZnRtbXZtbURRZnQdbTM3Qy8yZFBsWjA4ZndKdWxXU09rcHhNUVdzTVptcEpTM2NvVW5NTjJIUEtHc2psYTN1VlZ5bnZYUU51bjhybmRNZkVKZ0hZZUpScDhueWl2aGpNZExYUUp6HU5USVBSQXRKYTRFbWhNSkRLRHM1ekx1Z0lXNWx6TjI3bWEwV2QdbjAxbTJZRHdZcmgwUjBSZHFRaFFEdGZDbnBPTzBIdXAzVGxZZC9NdXR6bDFpYmJIUUxMbnF0LzR4alppMzA0ZlFkS0ZnZjM3RTRPcXhLUFZhbXE1SzEwWld4NjBvaGNYazBvOFR5NHdWdnp0eE9EQ2s0dDdBLzlLdnJBNXZoUTQ1dTlXeTJHWWdPVnhObkVyWTVtb1hVZURiNmN6cXdNZFF5TGpqMDRZMUlST0lsblYdWUc4MVpyRB0dSzF6Y0ltUDQxWjcvSXhDN21aZGtGS0hLUEJtdDZXQmIdU3FPbGlFb0hWMFY4MkpvSUZ6Rnd5allPOHo4dnd5bUFxcHRCRmRBSR1qHTd5VUo3cjZXcUdsd3VQTlNVZkNwNVQ1cFkzTEtMcEM1MUZ6OXdjMzBaelNyRldPTGFFMUxZNlUveXVtHVJqYm5IZDlsakFiRHUzc3h2eHFYUmJkTjg5WVNEdjcxakkyM3ZYHXh2WjYwZVkxNDlDOTg4R0h6OFdmZm9ISG5JRnpBPT0='
                            ])
                        ])
                    ]),
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


3D-Secure payment version 2.x with external authentication
=================

.. code-block:: php

    use Amadeus\Client\RequestOptions\Fop\CreditCardInfo;
    use Amadeus\Client\RequestOptions\Fop\CreditCardSupplementaryData;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;
    use Amadeus\Client\RequestOptions\Fop\ThreeDSecureInfo;
    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\Struct\Fop\AttributeDetails;

    $options = new FopCreateFopOptions([
        'transactionCode' => FopCreateFopOptions::TRANS_CREATE_FORM_OF_PAYMENT,
        'fopGroup' => [
            new Group([
                'mopInfo' => [
                    new MopInfo([
                        'fopType' => 'CC',
                        'attributeType' => AttributeDetails::TYPE_FP_ELEMENT,
                        'payMerchant' => 'EW',
                        'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                        'creditCardInfo' => new CreditCardInfo([
                            'name' => 'Name Surname',
                            'cardNumber' => 'XXXXXXXXXXXX0003',
                            'vendorCode' => 'VI',
                            'expiryDate' => '1020',
                            'securityId' => '999',
                            'threeDSecure' => new ThreeDSecureInfo([
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
                            ]),
                            'supplementaryData' => [
                                new CreditCardSupplementaryData([
                                    'setType' => CreditCardSupplementaryData::SET_TYPE_3DS,
                                    'attributeType' => CreditCardSupplementaryData::ATTRIBUTE_TYPE_EXTERNAL_AUTHENTICATION,
                                    'attributeDescription' => CreditCardSupplementaryData::ATTRIBUTE_DESCRIPTION_Y,
                                ])
                            ]
                        ]),
                        'sequenceNr' => 1,
                        'fopCode' => CC,
                        'fopStatus' => MopInfo::STATUS_NEW,
                    ])
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


Best Effort
===========

Best effort process triggered.

*(Operation 5.2 from the FOP_CreateFormOfPayment docs)*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\ElementRef;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;
    use Amadeus\Client\RequestOptions\Fop\CreditCardInfo;

    $options = new FopCreateFopOptions([
        'transactionCode' => FopCreateFopOptions::TRANS_AUTH_ON_TICKET_MCO_EMD,
        'bestEffortIndicator' => FopCreateFopOptions::BESTEFFORT_IND_CREATE_FOP_IF_AUTH_FAILS,
        'bestEffortAction' => FopCreateFopOptions::BESTEFFORT_ACT_CONFIRM,
        'fopGroup' => [
            new Group([
                'elementRef' => [
                    new ElementRef([
                        'type' => ElementRef::TYPE_TST_NUMBER,
                        'value' => 1
                    ])
                ],
                'mopInfo' => [
                    new MopInfo([
                        'sequenceNr' => 1,
                        'fopCode' => 'CCVI',
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'payMerchant' => 'AY',
                        'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                        'creditCardInfo' => new CreditCardInfo([
                            'vendorCode' => 'VI',
                            'cardNumber' => '4541000000000016',
                            'expiryDate' => '0915'
                        ])
                    ]),
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


Descriptive Billing Information (DBI) for TP cards
==================================================

DBI - TP card: According to the context and for TP cards, you may be requested to input DBI data (Descriptive Billing Information) within the authorization request.

*(Operation 5.4 from the FOP_CreateFormOfPayment docs)*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\ElementRef;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;
    use Amadeus\Client\RequestOptions\Fop\PaySupData;
    use Amadeus\Client\RequestOptions\Fop\CreditCardInfo;

    $options = new FopCreateFopOptions([
        'transactionCode' => FopCreateFopOptions::TRANS_AUTH_ON_TICKET_MCO_EMD,
        'fopGroup' => [
            new Group([
                'elementRef' => [
                    new ElementRef([
                        'type' => ElementRef::TYPE_TST_NUMBER,
                        'value' => 1
                    ])
                ],
                'mopInfo' => [
                    new MopInfo([
                        'sequenceNr' => 1,
                        'fopCode' => 'VI',
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'paySupData' => [
                            new PaySupData([
                                'function' => 'DBI',
                                'data' => [
                                    ' KS' => '12345',
                                    'RZ' => 'NCE',
                                    ' AE' => '4',
                                    'AU' => '526',
                                    'PK' => '1234',
                                ]
                            ])
                        ],
                        'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                        'creditCardInfo' => new CreditCardInfo([
                            'vendorCode' => 'TP',
                            'cardNumber' => '4541000000000013',
                            'expiryDate' => '0916'
                        ])
                    ])
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


DCC Offer
=========

The TFOPCQ will retry the PRI and the DCC currency chosen in order to perform an authorization request to the PSP with the correct currency chosen by the customer.

*(Operation 5.5 from the FOP_CreateFormOfPayment docs)*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\ElementRef;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;
    use Amadeus\Client\RequestOptions\Fop\Payment;
    use Amadeus\Client\RequestOptions\Fop\PayId;

    $options = new FopCreateFopOptions([
        'transactionCode' => FopCreateFopOptions::TRANS_CREATE_FORM_OF_PAYMENT,
        'fopGroup' => [
            new Group([
                'elementRef' => [
                    new ElementRef([
                        'type' => ElementRef::TYPE_TST_NUMBER,
                        'value' => 1
                    ])
                ],
                'mopInfo' => [
                    new MopInfo([
                        'sequenceNr' => 1,
                        'fopCode' => 'VI',
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'payMerchant' => 'AY',
                        'payments' => [
                            new Payment([
                                'type' => Payment::TYPE_TOTAL_FARE_AMOUNT,
                                'amount' => 100,
                                'currency' => 'EUR'
                            ])
                        ],
                        'payIds' => [
                            new PayId([
                                'type' => PayId::TYPE_DCC_CURRENCY_CHOSEN,
                                'id' => 'EUR'
                            ]),
                            new PayId([
                                'type' => PayId::TYPE_PAYMENT_RECORD_ID,
                                'id' => 123456
                            ]),
                        ]
                    ])
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


FOP Account (ELV)
=================

Payment with account: Several types of account can be handled. The ELV form of payment allows direct debit (ELV) payments.

*(Operation 5.6 from the FOP_CreateFormOfPayment docs)*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\ElementRef;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;
    use Amadeus\Client\RequestOptions\Fop\DataOrSwitch;
    use Amadeus\Client\RequestOptions\Fop\InvoiceInfo;

    $options = new FopCreateFopOptions([
        'transactionCode' => FopCreateFopOptions::TRANS_CREATE_FORM_OF_PAYMENT,
        'fopGroup' => [
            new Group([
                'elementRef' => [
                    new ElementRef([
                        'type' => ElementRef::TYPE_TST_NUMBER,
                        'value' => 1
                    ])
                ],
                'mopInfo' => [
                    new MopInfo([
                        'sequenceNr' => 1,
                        'fopCode' => 'ELV',
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'supplementaryData' => [
                            new DataOrSwitch([
                                'type' => '27',
                                'description' => 'JOHN SMITH'
                            ])
                        ],
                        'payMerchant' => 'AB',
                        'mopPaymentType' => MopInfo::MOP_PAY_TYPE_ACCOUNT_PAYMENT,
                        'transactionDate' => \DateTime::createFromFormat('dmY', '29112015'),
                        'invoiceInfo' => new InvoiceInfo([
                            'formOfPayment' => InvoiceInfo::FOP_ACCOUNT_PAYMENT,
                            'customerAccount' => '12345678',
                            'membershipStatus' => 'ELV',
                            'routingStation' => 'AB'
                        ])
                    ])
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


FOP with structured free flow
=============================

Query with the structured freeflow, in 2 different fields.

*(Operation 5.7 from the FOP_CreateFormOfPayment docs)*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\ElementRef;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;
    use Amadeus\Client\RequestOptions\Fop\DataOrSwitch;
    use Amadeus\Client\RequestOptions\Fop\Payment;

    $options = new FopCreateFopOptions([
        'transactionCode' => FopCreateFopOptions::TRANS_CREATE_FORM_OF_PAYMENT,
        'fopGroup' => [
            new Group([
                'elementRef' => [
                    new ElementRef([
                        'type' => ElementRef::TYPE_TST_NUMBER,
                        'value' => 1
                    ])
                ],
                'mopInfo' => [
                    new MopInfo([
                        'sequenceNr' => 1,
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'fopCode' => 'VI',
                        'supplementaryData' => [
                            new DataOrSwitch([
                                'type' => 'FF1',
                                'description' => '1234'
                            ]),
                            new DataOrSwitch([
                                'type' => 'FF2',
                                'description' => 'ABCD-*12'
                            ]),
                        ],
                        'payMerchant' => 'AY',
                        'payments' => [
                            new Payment([
                                'type' => Payment::TYPE_TOTAL_FARE_AMOUNT,
                                'amount' => 100,
                                'currency' => 'EUR'
                            ])
                        ]
                    ]),
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


FOP with unstructured free flow
===============================

Query with unstructured freeflow.

*(Operation 5.8 from the FOP_CreateFormOfPayment docs)*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\ElementRef;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;

    $options = new FopCreateFopOptions([
        'transactionCode' => FopCreateFopOptions::TRANS_CREATE_FORM_OF_PAYMENT,
        'fopGroup' => [
            new Group([
                'elementRef' => [
                    new ElementRef([
                        'type' => ElementRef::TYPE_TST_NUMBER,
                        'value' => 1
                    ])
                ],
                'mopInfo' => [
                    new MopInfo([
                        'sequenceNr' => 1,
                        'fopCode' => 'VI',
                        'freeFlowText' => 'VI4541000000010016/0919'
                    ]),
                    new MopInfo([
                        'sequenceNr' => 2,
                        'fopCode' => 'VI',
                        'freeFlowText' => 'VI4541000000000024/0919/EUR20'
                    ]),
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


FOP with installments
=====================

Query containing Installments data.

*(Operation 5.9 from the FOP_CreateFormOfPayment docs)*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\ElementRef;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;
    use Amadeus\Client\RequestOptions\Fop\Payment;
    use Amadeus\Client\RequestOptions\Fop\InstallmentsInfo;
    use Amadeus\Client\RequestOptions\Fop\CreditCardInfo;

    $options = new FopCreateFopOptions([
        'fopGroup' => [
            new Group([
                'elementRef' => [
                    new ElementRef([
                        'type' => ElementRef::TYPE_TST_NUMBER,
                        'value' => 1
                    ])
                ],
                'mopInfo' => [
                    new MopInfo([
                        'sequenceNr' => 1,
                        'fopCode' => 'CCVI',
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'payMerchant' => 'QF',
                        'payments' => [
                            new Payment([
                                'type' => Payment::TYPE_FIRST_INSTALMENT_AMOUNT,
                                'amount' => 100,
                                'currency' => 'AUD'
                            ]),
                            new Payment([
                                'type' => Payment::TYPE_FOLLOWING_INSTALMENT_AMOUNT,
                                'amount' => 200,
                                'currency' => 'AUD'
                            ]),
                            new Payment([
                                'type' => Payment::TYPE_INSTALMENT_INTEREST,
                                'amount' => 300,
                                'currency' => 'AUD'
                            ])
                        ],
                        'installmentsInfo' => new InstallmentsInfo([
                            'nrOfInstallments' => 2,
                            'frequency' => InstallmentsInfo::FREQUENCY_MONTHLY,
                            'startDate' => \DateTime::createFromFormat('Ymd', '20130223'),
                            'format' => InstallmentsInfo::FORMAT_YYMMDD
                        ]),
                        'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                        'creditCardInfo' => new CreditCardInfo([
                            'vendorCode' => 'VI',
                            'cardNumber' => '4541000000000016',
                            'expiryDate' => '0913',
                        ])
                    ])
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


Fraud screening
===============

Query with fraud screening data.

*(Operation 5.10 from the FOP_CreateFormOfPayment docs)*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\PaxRef;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;
    use Amadeus\Client\RequestOptions\Fop\InstallmentsInfo;
    use Amadeus\Client\RequestOptions\Fop\FraudScreeningOptions;
    use Amadeus\Client\RequestOptions\Fop\CreditCardInfo;

    $options = new FopCreateFopOptions([
        'fopGroup' => [
            new Group([
                'paxRef' => [
                    new PaxRef([
                        'type' => PaxRef::TYPE_ADULT,
                        'value' => 1
                    ])
                ],
                'mopInfo' => [
                    new MopInfo([
                        'sequenceNr' => 1,
                        'fopCode' => 'VI',
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'payMerchant' => 'AY',
                        'installmentsInfo' => new InstallmentsInfo([
                            'nrOfInstallments' => 1
                        ]),
                        'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                        'creditCardInfo' => new CreditCardInfo([
                            'vendorCode' => 'VI',
                            'cardNumber' => '4541099100010016',
                            'expiryDate' => '0915',
                            'name' => 'CLEMENT DUPONT'
                        ]),
                        'fraudScreening' => new FraudScreeningOptions([
                            'ipAddress' => '111.222.333.444', // /r/itsaunixsystem
                            'firstName' => 'CLEMENT',
                            'lastName' => 'DUPONT',
                            'dateOfBirth' => \DateTime::createFromFormat('dmY', '30101980'),
                            'idDocumentNr' => '25208731592',
                            'idDocumentType' => FraudScreeningOptions::ID_DOC_CPF__BRAZILIAN_SECURITY_NUMBER,
                            'phone' => '(212) 664-7665',
                            'email' => 'test@test.com'
                        ])
                    ])
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


Generic FP - Single FOP
=======================

Query to create a generic FP.

*(Operation 5.12 from the FOP_CreateFormOfPayment docs)*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;

    $options = new FopCreateFopOptions([
        'fopGroup' => [
            new Group([
                'mopInfo' => [
                    new MopInfo([
                        'sequenceNr' => 1,
                        'fopCode' => 'CASH'
                    ])
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


Manual approval code
====================

Following example shows how to input a FOP with manual approval code "12346".

*(Operation 5.14 from the FOP_CreateFormOfPayment docs)*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\PaxRef;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;
    use Amadeus\Client\RequestOptions\Fop\CreditCardInfo;

    $options = new FopCreateFopOptions([
        'transactionCode' => FopCreateFopOptions::TRANS_CREATE_FORM_OF_PAYMENT,
        'fopGroup' => [
            new Group([
                'paxRef' => [
                    new PaxRef([
                        'type' => PaxRef::TYPE_ADULT,
                        'value' => 1
                    ])
                ],
                'mopInfo' => [
                    new MopInfo([
                        'sequenceNr' => 1,
                        'fopCode' => 'VI',
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                        'creditCardInfo' => new CreditCardInfo([
                            'vendorCode' => 'VI',
                            'cardNumber' => '4541099100010016',
                            'expiryDate' => '0913',
                            'approvalCode' => '12346',
                            'sourceOfApproval' => CreditCardInfo::APPROVAL_SOURCE_MANUAL
                        ])
                    ])
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


Multiple FOPs
=============

Scenario with multiple FOP's: It is possible to specify up to three FOPs per FP element.

*(Operation 5.16 from the FOP_CreateFormOfPayment docs)*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\ElementRef;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;
    use Amadeus\Client\RequestOptions\Fop\Payment;
    use Amadeus\Client\RequestOptions\Fop\CreditCardInfo;

    $options = new FopCreateFopOptions([
        'transactionCode' => FopCreateFopOptions::TRANS_CREATE_FORM_OF_PAYMENT,
        'fopGroup' => [
            new Group([
                'elementRef' => [
                    new ElementRef([
                        'type' => ElementRef::TYPE_TST_NUMBER,
                        'value' => 1
                    ])
                ],
                'mopInfo' => [
                    new MopInfo([
                        'sequenceNr' => 1,
                        'fopCode' => 'CASH',
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CASH,
                    ]),
                    new MopInfo([
                        'sequenceNr' => 2,
                        'fopCode' => 'CCVI',
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'payMerchant' => 'AF',
                        'payments' => [
                            new Payment([
                                'type' => Payment::TYPE_TOTAL_FARE_AMOUNT,
                                'amount' => 40,
                                'currency' => 'EUR'
                            ])
                        ],
                        'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                        'creditCardInfo' => new CreditCardInfo([
                            'vendorCode' => 'VI',
                            'cardNumber' => '4541099100010016',
                            'expiryDate' => '1016',
                            'securityId' => '123'
                        ])
                    ]),
                    new MopInfo([
                        'sequenceNr' => 3,
                        'fopCode' => 'CCVI',
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'payMerchant' => 'AF',
                        'payments' => [
                            new Payment([
                                'type' => Payment::TYPE_TOTAL_FARE_AMOUNT,
                                'amount' => 20,
                                'currency' => 'EUR'
                            ])
                        ],
                        'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                        'creditCardInfo' => new CreditCardInfo([
                            'vendorCode' => 'VI',
                            'cardNumber' => '4541099100010024',
                            'expiryDate' => '1016',
                            'securityId' => '123'
                        ])
                    ])
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


Multiple TST - TSM
==================

Query to create an FP element on several TSM.

*(Operation 5.17 from the FOP_CreateFormOfPayment docs)*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\ElementRef;
    use Amadeus\Client\RequestOptions\Fop\PaxRef;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;
    use Amadeus\Client\RequestOptions\Fop\Payment;
    use Amadeus\Client\RequestOptions\Fop\CreditCardInfo;

    $options = new FopCreateFopOptions([
        'transactionCode' => FopCreateFopOptions::TRANS_AUTH_ON_TICKET_MCO_EMD,
        'fopGroup' => [
            new Group([
                'paxRef' => [
                    new PaxRef([
                        'type' => PaxRef::TYPE_ADULT,
                        'value' => 1
                    ])
                ],
                'elementRef' => [
                    new ElementRef([
                        'type' => ElementRef::TYPE_SERVICE_PRICING_RECORD_TATTOO,
                        'value' => 1
                    ]),
                    new ElementRef([
                        'type' => ElementRef::TYPE_SERVICE_PRICING_RECORD_TATTOO,
                        'value' => 2
                    ]),
                    new ElementRef([
                        'type' => ElementRef::TYPE_SPECIAL_SERVICE_REQUEST,
                        'value' => 1
                    ])
                ],
                'mopInfo' => [
                    new MopInfo([
                        'sequenceNr' => 1,
                        'fopCode' => 'CASH'
                    ]),
                    new MopInfo([
                        'sequenceNr' => 2,
                        'fopCode' => 'VI',
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'payMerchant' => 'AY',
                        'payments' => [
                            new Payment([
                                'type' => Payment::TYPE_TOTAL_FARE_AMOUNT,
                                'amount' => 40,
                                'currency' => 'EUR'
                            ])
                        ],
                        'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                        'creditCardInfo' => new CreditCardInfo([
                            'vendorCode' => 'VI',
                            'cardNumber' => '4541099100010016',
                            'expiryDate' => '0916'
                        ])
                    ]),
                    new MopInfo([
                        'sequenceNr' => 3,
                        'fopCode' => 'VI',
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'payMerchant' => 'AY',
                        'payments' => [
                            new Payment([
                                'type' => Payment::TYPE_TOTAL_FARE_AMOUNT,
                                'amount' => 20,
                                'currency' => 'EUR'
                            ])
                        ],
                        'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                        'creditCardInfo' => new CreditCardInfo([
                            'vendorCode' => 'VI',
                            'cardNumber' => '4541099100010024',
                            'expiryDate' => '0916'
                        ])
                    ])
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


OB Fees computation
===================

Query to trigger OB fee calculation with Pricing options.

*(Operation 5.19 from the FOP_CreateFormOfPayment docs)*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopCreateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\ObFeeComputation;
    use Amadeus\Client\RequestOptions\Fop\MopInfo;
    use Amadeus\Client\RequestOptions\Fop\CreditCardInfo;

    $options = new FopCreateFopOptions([
        'transactionCode' => FopCreateFopOptions::TRANS_AUTH_ON_TICKET_MCO_EMD,
        'obFeeCalculation' => true,
        'fopGroup' => [
            new Group([
                'obFeeComputation' => new ObFeeComputation([
                    'option' => ObFeeComputation::OPTION_OB_FEES,
                    'optionInformation' => ObFeeComputation::OPTIONINF_EXEMPT_ALL_OB_FEES,
                    'city' => 'NCE',
                    'departureDate' => \DateTime::createFromFormat('Ymd', '20140412')
                ]),
                'mopInfo' => [
                    new MopInfo([
                        'sequenceNr' => 1,
                        'fopCode' => 'CCVI',
                        'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                        'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                        'payMerchant' => 'AY',
                        'creditCardInfo' => new CreditCardInfo([
                            'vendorCode' => 'VI',
                            'cardNumber' => '4541099100010016',
                            'expiryDate' => '0915'
                        ])
                    ])
                ]
            ])
        ]
    ]);

    $fopResponse = $client->fopCreateFormOfPayment($options);


