<?php

namespace Amadeus\Client\RequestOptions;

/**
 * Class ReferenceGroup
 * @package Amadeus\Client\RequestOptions
 */
class ReferenceGroup
{
    /**
     * @var Reference[]
     */
    private $references;

    /**
     * ReferenceGroup constructor.
     * @param Reference[] $references
     */
    public function __construct(array $references)
    {
        $this->references = $references;
    }

    /**
     * @return Reference[]
     */
    public function getReferences()
    {
        return $this->references;
    }
}
