# amadeus-ws-client: PHP client for the Amadeus GDS SOAP Web Service interface

[![Build Status](https://travis-ci.org/amabnl/amadeus-ws-client.svg?branch=master)](https://travis-ci.org/amabnl/amadeus-ws-client)

[![Coverage Status](https://coveralls.io/repos/github/amabnl/amadeus-ws-client/badge.svg?branch=master)](https://coveralls.io/github/amabnl/amadeus-ws-client?branch=master)

This client library provides access to the Amadeus GDS SOAP Web Service interface. 

To use this client, you must first obtain your personal access to the Web Service interface through an Amadeus Sales channel of your choice.

The Amadeus documentation portal can be found at https://webservices.amadeus.com/
 
![](http://i.imgur.com/7ZcCgnj.jpg)

# Installation

Install amadeus-ws-client through [Composer](http://getcomposer.org).

```bash
curl -sS https://getcomposer.org/installer | php
```

Now require the amadeus web service client: 

```bash
composer.phar require amabnl/amadeus-ws-client
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

Update composer to get the client:

 ```bash
composer.phar update
 ```

# Usage

[See docs/usage](docs/usage.rst)