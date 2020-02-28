<?php

namespace Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\FareGetFareFamilyDescriptionOptions;
use Amadeus\Client\RequestOptions\Reference;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription\ReferenceDetails;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription\ReferenceInformation;

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
    }
}
