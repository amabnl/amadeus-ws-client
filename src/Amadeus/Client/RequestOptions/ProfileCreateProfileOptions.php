<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 12/09/2018
 * Time: 10:09
 */

namespace Amadeus\Client\RequestOptions;
use Amadeus\Client\RequestOptions;

class ProfileCreateProfileOptions extends Base
{
    public $Version;
    public $UniqueID;
    public $Profile;
    //public $CompanyInfo;
    public $CompanyName;

    /**
     * Construct Update Profile with initialization array
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