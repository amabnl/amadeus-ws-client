<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * PaxJourney
 *
 * A collection of segments satisfying transportation for a given
 * origin/destination (PaxJourneyType).
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class PaxJourney
{
    /**
     * @var string|null
     */
    public $PaxJourneyID;

    /**
     * @var PaxSegment[]
     */
    public $PaxSegment;

    /**
     * @param string|null $paxJourneyId
     * @param PaxSegment[] $paxSegments
     */
    public function __construct($paxJourneyId, array $paxSegments)
    {
        if ($paxJourneyId !== null) {
            $this->PaxJourneyID = $paxJourneyId;
        }

        $this->PaxSegment = $paxSegments;
    }
}
