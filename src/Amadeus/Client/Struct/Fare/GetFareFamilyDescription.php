<?php

namespace Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\FareGetFareFamilyDescriptionOptions;
use Amadeus\Client\RequestOptions\Reference;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription\BookingDateInformation;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription\CarrierInformation;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription\FareInformation;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription\ItineraryInformation;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription\ReferenceDetails;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription\ReferenceInformation;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription\StandaloneDescriptionRequest;

/**
 * Class GetFareFamilyDescription
 * @package Amadeus\Client\Struct\Fare
 */
class GetFareFamilyDescription extends BaseWsMessage
{
    /**
     * @var ReferenceInformation[]|array
     */
    public $referenceInformation;

    /** @var BookingDateInformation */
    public $bookingDateInformation;

    /** @var StandaloneDescriptionRequest[] */
    public $standaloneDescriptionRequest;

    /**
     * GetFareFamilyDescription constructor.
     *
     * @param FareGetFareFamilyDescriptionOptions|null $options
     */
    public function __construct($options)
    {
        foreach ($options->referenceGroups as $referenceGroup) {
            $references = array_map(function (Reference $reference) {
                return new ReferenceDetails(
                    $reference->getType(),
                    $reference->getValue()
                );
            }, $referenceGroup->getReferences());

            $this->referenceInformation[] = new ReferenceInformation($references);
        }

        if ($bookingDateInformation = $options->bookingDateInformation) {
            $this->bookingDateInformation = new BookingDateInformation($bookingDateInformation);
        }

        if ($standaloneDescriptionRequest = $options->standaloneDescriptionRequest) {
            foreach ($standaloneDescriptionRequest->items as $standaloneDescriptionRequestOption) {
                $this->standaloneDescriptionRequest[] = new StandaloneDescriptionRequest(
                    new FareInformation($standaloneDescriptionRequestOption->fareInfo),
                    new ItineraryInformation($standaloneDescriptionRequestOption->itineraryInfo),
                    new CarrierInformation($standaloneDescriptionRequestOption->carrierInfo)
                );
            }
        }
    }
}
