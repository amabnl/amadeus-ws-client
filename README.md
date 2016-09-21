# amadeus-ws-client: PHP client for the Amadeus GDS SOAP Web Service interface

[![Latest Stable Version](https://poser.pugx.org/amabnl/amadeus-ws-client/v/stable)](https://packagist.org/packages/amabnl/amadeus-ws-client) [![Build Status](https://travis-ci.org/amabnl/amadeus-ws-client.svg?branch=master)](https://travis-ci.org/amabnl/amadeus-ws-client) [![Coverage Status](https://coveralls.io/repos/github/amabnl/amadeus-ws-client/badge.svg?branch=master)](https://coveralls.io/github/amabnl/amadeus-ws-client?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/amabnl/amadeus-ws-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/amabnl/amadeus-ws-client/?branch=master) [![Style Status - PSR-2](https://styleci.io/repos/49078536/shield?branch=master)](https://styleci.io/repos/49078536)

This client library provides access to the Amadeus GDS SOAP Web Service interface. 

To use this client, you must first obtain your personal access to the Web Service interface through an Amadeus Sales channel of your choice.

The Amadeus documentation portal can be found at https://webservices.amadeus.com/
 
![](http://i.imgur.com/7ZcCgnj.jpg)

We currently support a full AIR booking flow based on MasterPricer, as well as some additional messages useful for automation. See the full list of supported messages [here](docs/list-of-supported-messages.rst).

# Purpose

This library makes it much easier for developers to integrate content from the Amadeus Web Services into their projects. It takes care of:

- Session management - setting the correct SOAP headers both for Soap Header 2 and 4.
- Supports all features of Soap Header 4 such as Stateful and Stateless messages.
- Building the correct request message based on the provided options
- Handling exceptions and checking for error messages in the response
- Dealing with different versions of messages
- Dealing with multiple WSDL files in a WSAP

# Requirements

* PHP 5.4+
* SOAP, XSL and DOM extensions activated
* A WSDL & authentication details from Amadeus _(SoapHeader 4 or SoapHeader 2)_

# Installation

Install amadeus-ws-client through [Composer](http://getcomposer.org).

```bash
composer require amabnl/amadeus-ws-client
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

Update composer to get the client:

 ```bash
composer update
 ```

Once the client is installed, read the [About &amp; Get Started](docs/about-get-started.rst) page.

# Release history

The current release is the first stable version of the library, 1.0.0. 

[Changelog](CHANGELOG.md)

# Usage

- [About &amp; Get Started](docs/about-get-started.rst)
- [List of supported messages](docs/list-of-supported-messages.rst)
- [How to](docs/how-to.rst)
- [Examples for sending specific messages](docs/samples.rst)

---------------------------------------


See some [example applications](docs/sample-applications.rst) built with this library.
