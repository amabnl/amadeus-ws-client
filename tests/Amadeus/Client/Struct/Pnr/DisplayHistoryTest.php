<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Test\Amadeus\Client\Struct\Pnr;

use Amadeus\Client\RequestOptions\Pnr\DisplayHistory\Predicate;
use Amadeus\Client\RequestOptions\Pnr\DisplayHistory\PredicateDetail;
use Amadeus\Client\RequestOptions\Pnr\DisplayHistory\PredicateType;
use Amadeus\Client\RequestOptions\PnrDisplayHistoryOptions;
use Amadeus\Client\Struct\Pnr\DisplayHistory;
use Test\Amadeus\BaseTestCase;

/**
 * DisplayHistoryTest
 *
 * @package Test\Amadeus\Client\Struct\Pnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DisplayHistoryTest extends BaseTestCase
{
    public function testCanMakeBasicDisplayEntirePnrHistoryMessage()
    {
        $opt = new PnrDisplayHistoryOptions([
            'recordLocator' => 'ABC123'
        ]);

        $message = new DisplayHistory($opt);

        $this->assertEquals('ABC123', $message->pnrInfo->reservation->controlNumber);
        $this->assertNull($message->pnrInfo->reservation->date);
        $this->assertNull($message->pnrInfo->reservation->time);

        $this->assertEquals(DisplayHistory\SelectionDetails::OPT_STANDARD, $message->redundantElements->selectionDetails->option);

        $this->assertEmpty($message->predicate);
        $this->assertNull($message->scrollingDetails);
    }

    public function testCanMakeDisplayHistoryWithPnrCreationDate()
    {
        $opt = new PnrDisplayHistoryOptions([
            'recordLocator' => 'ABC123',
            'pnrCreateTime' => \DateTime::createFromFormat('dmY H:i:s', '19082017 00:00:00', new \DateTimeZone('UTC'))
        ]);

        $message = new DisplayHistory($opt);

        $this->assertEquals('ABC123', $message->pnrInfo->reservation->controlNumber);
        $this->assertEquals('20170819', $message->pnrInfo->reservation->date);
        $this->assertNull($message->pnrInfo->reservation->time);

        $this->assertEquals(DisplayHistory\SelectionDetails::OPT_STANDARD, $message->redundantElements->selectionDetails->option);

        $this->assertEmpty($message->predicate);
        $this->assertNull($message->scrollingDetails);
    }

    public function testCanMakeDisplayHistoryWithPnrCreationDateTime()
    {
        $opt = new PnrDisplayHistoryOptions([
            'recordLocator' => 'ABC123',
            'pnrCreateTime' => \DateTime::createFromFormat('dmY H:i', '19082017 14:31', new \DateTimeZone('UTC'))
        ]);

        $message = new DisplayHistory($opt);

        $this->assertEquals('ABC123', $message->pnrInfo->reservation->controlNumber);
        $this->assertEquals('20170819', $message->pnrInfo->reservation->date);
        $this->assertEquals('1431', $message->pnrInfo->reservation->time);

        $this->assertEquals(DisplayHistory\SelectionDetails::OPT_STANDARD, $message->redundantElements->selectionDetails->option);

        $this->assertEmpty($message->predicate);
        $this->assertNull($message->scrollingDetails);
    }

    public function testCanMakeDisplayHistoryOnlyRfLines()
    {
        $opt = new PnrDisplayHistoryOptions([
            'recordLocator' => 'ABC123',
            'predicates' => [
                new Predicate([
                    'details' => [
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_KEEP_HISTORY_MATCHING_CRITERION,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_PREDICATE_TYPE
                        ]),
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_DISPLAY_ENVELOPES_CONTAINING_RF_LINE_ONLY,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_MATCH_QUEUE_UPDATE
                        ]),
                    ]
                ])
            ]
        ]);

        $message = new DisplayHistory($opt);

        $this->assertEquals('ABC123', $message->pnrInfo->reservation->controlNumber);
        $this->assertNull($message->pnrInfo->reservation->date);
        $this->assertNull($message->pnrInfo->reservation->time);

        $this->assertEquals(DisplayHistory\SelectionDetails::OPT_STANDARD, $message->redundantElements->selectionDetails->option);

        $this->assertInternalType('array', $message->predicate);
        $this->assertCount(1, $message->predicate);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\DisplayHistory\Predicate', $message->predicate[0]);

        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPT_SELECTION_PREDICATE, $message->predicate[0]->predicateDetails->selectionDetails->option);
        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPTINF_PREDICATE_TYPE, $message->predicate[0]->predicateDetails->selectionDetails->optionInformation);

        $this->assertInternalType('array', $message->predicate[0]->predicateDetails->otherSelectionDetails);
        $this->assertCount(1, $message->predicate[0]->predicateDetails->otherSelectionDetails);

        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPT_DISP_ENVELOPES_CONTAINING_RF, $message->predicate[0]->predicateDetails->otherSelectionDetails[0]->option);
        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPTINF_MATCH_QUEUE_UPDATES, $message->predicate[0]->predicateDetails->otherSelectionDetails[0]->optionInformation);

        $this->assertEmpty($message->predicate[0]->predicateElementType);
        $this->assertNull($message->predicate[0]->predicateEnvRange);
        $this->assertNull($message->predicate[0]->predicateFreeText);

        $this->assertNull($message->scrollingDetails);
    }

    public function testCanMakeDisplayHistoryScrollingMax()
    {
        $opt = new PnrDisplayHistoryOptions([
            'recordLocator' => 'ABC123',
            'scrollingMax' => 200
        ]);

        $message = new DisplayHistory($opt);

        $this->assertEquals('ABC123', $message->pnrInfo->reservation->controlNumber);

        $this->assertEquals(200, $message->scrollingDetails->lastItemsDetails->numberOfItems);
        $this->assertNull($message->scrollingDetails->lastItemsDetails->lastItemIdentifier);
    }

    public function testCanMakeDisplayHistoryWithAirSegmentUpdatesWithoutQueueUpdates()
    {
        $opt = new PnrDisplayHistoryOptions([
            'recordLocator' => '3J6YFG',
            'predicates' => [
                new Predicate([
                    'details' => [
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_KEEP_HISTORY_MATCHING_CRITERION,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_PREDICATE_TYPE
                        ]),
                    ],
                    'types' => [
                        new PredicateType([
                            'elementName' => 'AIR'
                        ])
                    ]
                ]),
                new Predicate([
                    'details' => [
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_DISCARD_HISTORY_MATCHING_CRITERION,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_MATCH_QUEUE_UPDATE
                        ]),
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_DISPLAY_HISTORY_WITH_QUEUEING_UPDATES,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_PREDICATE_TYPE
                        ]),
                    ],
                ])
            ]
        ]);

        $message = new DisplayHistory($opt);

        $this->assertEquals('3J6YFG', $message->pnrInfo->reservation->controlNumber);

        $this->assertInternalType('array', $message->predicate);
        $this->assertCount(2, $message->predicate);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\DisplayHistory\Predicate', $message->predicate[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\DisplayHistory\Predicate', $message->predicate[1]);

        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPT_SELECTION_PREDICATE, $message->predicate[0]->predicateDetails->selectionDetails->option);
        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPTINF_PREDICATE_TYPE, $message->predicate[0]->predicateDetails->selectionDetails->optionInformation);
        $this->assertEmpty($message->predicate[0]->predicateDetails->otherSelectionDetails);
        $this->assertInternalType('array', $message->predicate[0]->predicateElementType);
        $this->assertCount(1, $message->predicate[0]->predicateElementType);
        $this->assertEquals('AIR', $message->predicate[0]->predicateElementType[0]->segmentName);
        $this->assertNull($message->predicate[0]->predicateElementType[0]->reference);
        $this->assertNull($message->predicate[0]->predicateEnvRange);
        $this->assertNull($message->predicate[0]->predicateFreeText);

        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPT_FILTER_PREDICATE, $message->predicate[1]->predicateDetails->selectionDetails->option);
        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPTINF_MATCH_QUEUE_UPDATES, $message->predicate[1]->predicateDetails->selectionDetails->optionInformation);
        $this->assertInternalType('array', $message->predicate[1]->predicateDetails->otherSelectionDetails);
        $this->assertCount(1, $message->predicate[1]->predicateDetails->otherSelectionDetails);

        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPT_MATCH_QUEUE_UPDATE, $message->predicate[1]->predicateDetails->otherSelectionDetails[0]->option);
        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPTINF_PREDICATE_TYPE, $message->predicate[1]->predicateDetails->otherSelectionDetails[0]->optionInformation);

        $this->assertNull($message->scrollingDetails);
    }

    public function testCanMakeDisplayHistoryWithSpecificAirSegmentUpdates()
    {
        $opt = new PnrDisplayHistoryOptions([
            'recordLocator' => '3J6YFG',
            'predicates' => [
                new Predicate([
                    'details' => [
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_KEEP_HISTORY_MATCHING_CRITERION,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_PREDICATE_TYPE
                        ]),
                    ],
                    'types' => [
                        new PredicateType([
                            'elementName' => 'AIR',
                            'reference' => 2,
                            'referenceQualifier' => PredicateType::REFQUAL_SEGMENT_TATTOO
                        ])
                    ]
                ])
            ]
        ]);

        $message = new DisplayHistory($opt);

        $this->assertInternalType('array', $message->predicate);
        $this->assertCount(1, $message->predicate);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\DisplayHistory\Predicate', $message->predicate[0]);

        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPT_SELECTION_PREDICATE, $message->predicate[0]->predicateDetails->selectionDetails->option);
        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPTINF_PREDICATE_TYPE, $message->predicate[0]->predicateDetails->selectionDetails->optionInformation);
        $this->assertEmpty($message->predicate[0]->predicateDetails->otherSelectionDetails);
        $this->assertInternalType('array', $message->predicate[0]->predicateElementType);
        $this->assertCount(1, $message->predicate[0]->predicateElementType);
        $this->assertEquals('AIR', $message->predicate[0]->predicateElementType[0]->segmentName);
        $this->assertEquals(2, $message->predicate[0]->predicateElementType[0]->reference->number);
        $this->assertEquals(DisplayHistory\Reference::QUAL_SEGMENT_TATTOO, $message->predicate[0]->predicateElementType[0]->reference->qualifier);
        $this->assertNull($message->predicate[0]->predicateEnvRange);
        $this->assertNull($message->predicate[0]->predicateFreeText);
    }

    public function testCanMakeDisplayHistoryStartAtEnvelope5()
    {
        $opt = new PnrDisplayHistoryOptions([
            'recordLocator' => '3J6YFG',
            'predicates' => [
                new Predicate([
                    'details' => [
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_KEEP_HISTORY_MATCHING_CRITERION,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_PREDICATE_TYPE
                        ]),
                    ],
                    'rangeMin' => 4
                ])
            ]
        ]);

        $message = new DisplayHistory($opt);

        $this->assertInternalType('array', $message->predicate);

        $this->assertCount(1, $message->predicate);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\DisplayHistory\Predicate', $message->predicate[0]);

        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPT_SELECTION_PREDICATE, $message->predicate[0]->predicateDetails->selectionDetails->option);
        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPTINF_PREDICATE_TYPE, $message->predicate[0]->predicateDetails->selectionDetails->optionInformation);
        $this->assertEmpty($message->predicate[0]->predicateDetails->otherSelectionDetails);
        $this->assertEmpty($message->predicate[0]->predicateElementType);

        $this->assertEquals(4, $message->predicate[0]->predicateEnvRange->rangeDetails->min);
        $this->assertNull($message->predicate[0]->predicateEnvRange->rangeDetails->max);
    }

    public function testCanMakeDisplayHistoryForRemarkElementsWithoutQueueUpdates()
    {
        $opt = new PnrDisplayHistoryOptions([
            'recordLocator' => '3J6YFG',
            'predicates' => [
                new Predicate([
                    'details' => [
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_KEEP_HISTORY_MATCHING_CRITERION,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_PREDICATE_TYPE
                        ]),
                    ],
                    'types' => [
                        new PredicateType([
                            'elementName' => 'RM'
                        ]),
                        new PredicateType([
                            'elementName' => 'RC'
                        ]),
                        new PredicateType([
                            'elementName' => 'RX'
                        ]),
                        new PredicateType([
                            'elementName' => 'RI'
                        ]),
                    ]
                ]),
                new Predicate([
                    'details' => [
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_DISCARD_HISTORY_MATCHING_CRITERION,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_PREDICATE_TYPE
                        ]),
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_DISPLAY_HISTORY_WITH_QUEUEING_UPDATES,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_MATCH_QUEUE_UPDATE
                        ]),
                    ],
                ])
            ]
        ]);

        $message = new DisplayHistory($opt);

        $this->assertInternalType('array', $message->predicate);

        $this->assertCount(2, $message->predicate);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\DisplayHistory\Predicate', $message->predicate[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\DisplayHistory\Predicate', $message->predicate[1]);

        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPT_SELECTION_PREDICATE, $message->predicate[0]->predicateDetails->selectionDetails->option);
        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPTINF_PREDICATE_TYPE, $message->predicate[0]->predicateDetails->selectionDetails->optionInformation);

        $this->assertCount(4, $message->predicate[0]->predicateElementType);
        $this->assertEquals('RM', $message->predicate[0]->predicateElementType[0]->segmentName);
        $this->assertNull($message->predicate[0]->predicateElementType[0]->reference);
        $this->assertEquals('RC', $message->predicate[0]->predicateElementType[1]->segmentName);
        $this->assertNull($message->predicate[0]->predicateElementType[1]->reference);
        $this->assertEquals('RX', $message->predicate[0]->predicateElementType[2]->segmentName);
        $this->assertNull($message->predicate[0]->predicateElementType[2]->reference);
        $this->assertEquals('RI', $message->predicate[0]->predicateElementType[3]->segmentName);
        $this->assertNull($message->predicate[0]->predicateElementType[3]->reference);

        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPT_FILTER_PREDICATE, $message->predicate[1]->predicateDetails->selectionDetails->option);
        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPTINF_PREDICATE_TYPE, $message->predicate[1]->predicateDetails->selectionDetails->optionInformation);
        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPT_MATCH_QUEUE_UPDATE, $message->predicate[1]->predicateDetails->otherSelectionDetails[0]->option);
        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPTINF_MATCH_QUEUE_UPDATES, $message->predicate[1]->predicateDetails->otherSelectionDetails[0]->optionInformation);
    }

    public function testCanMakeDisplayHistoryFindFreeText()
    {
        $opt = new PnrDisplayHistoryOptions([
            'recordLocator' => '3J6YFG',
            'predicates' => [
                new Predicate([
                    'details' => [
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_FIND_HISTORY_CONTAINING_FREETEXT,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_PREDICATE_TYPE
                        ]),
                    ],
                    'freeText' => 'DUMMY'
                ])
            ]
        ]);

        $message = new DisplayHistory($opt);

        $this->assertInternalType('array', $message->predicate);

        $this->assertCount(1, $message->predicate);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\DisplayHistory\Predicate', $message->predicate[0]);

        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPT_FIND_HISTORY_LINES_FREETEXT, $message->predicate[0]->predicateDetails->selectionDetails->option);
        $this->assertEquals(DisplayHistory\PredicateSelectionDetails::OPTINF_PREDICATE_TYPE, $message->predicate[0]->predicateDetails->selectionDetails->optionInformation);

        $this->assertEquals('DUMMY', $message->predicate[0]->predicateFreeText->freeText);
        $this->assertEquals(DisplayHistory\FreeTextDetails::ENCODING_ASCII_8BIT, $message->predicate[0]->predicateFreeText->freeTextDetails->encoding);
        $this->assertEquals(DisplayHistory\FreeTextDetails::SOURCE_AMADEUS, $message->predicate[0]->predicateFreeText->freeTextDetails->source);
        $this->assertEquals(DisplayHistory\FreeTextDetails::QUALIFIER_LITERAL_TEXT, $message->predicate[0]->predicateFreeText->freeTextDetails->textSubjectQualifier);
    }
}
