<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 03/09/2018
 * Time: 11:19
 */

namespace Amadeus\Client\RequestOptions;
use Amadeus\Client\RequestOptions;

class ProfileReadProfileOptions extends Base
{
    public $Version;
    public $UniqueID;
    public $ReadRequests;

    /**
     * Construct Read profile with initialization array
     *
     * @param array $params Initialization parameters
     */
    public function __construct($params = [])
    {
        foreach ($params as $propName => $propValue) {
            if (property_exists($this, $propName)) {
                $this->$propName = $propValue;
            }
        }
    }
}