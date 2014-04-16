# Omnipay: Barzahlen

**Barzahlen driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/sauladam/omnipay-barzahlen.svg?branch=master)](https://travis-ci.org/sauladam/omnipay-barzahlen)
[![Total Downloads](https://poser.pugx.org/sauladam/omnipay-barzahlen/downloads.png)](https://packagist.org/packages/sauladam/omnipay-barzahlen)

This is non-official Omnipay-driver for the German payment gateway provider [Barzahlen](https://www.barzahlen.de/).
In order to use it the Omnipay-Framework is required.

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements barzahlen support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "sauladam/omnipay-barzahlen": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* Barzahlen

For general usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

Basically it's pretty straight forward:

### Purchase (create a transaction):

```php
require 'vendor/autoload.php';

use Omnipay\Omnipay;

$gateway = Omnipay::create('Barzahlen');

// Testmode is on by default until you explicitly switch it off.
// You can either do this here on the gateway level or for each request.
$gateway->setTestMode(false);
$gateway->setShopId('yourShopid');
$gateway->setPaymentKey('yourPaymentKey');

$request = $gateway->purchase(
	array(
		'currency' => 'EUR',
		// Amounts higher than 999.99 will not be accepted
		'amount' => '111.59',
		// The order_id is not mandatory, you can set
		// it later in another request if you want.
		'orderId' => '123456', 
		'customerDetails' => array(
			'email' => 'test@test.com',
			'street_nr' => 'Testsreet 10a',
			'zipcode' => '12345',
			'city' => 'Test City',
			'country' => 'DE',
		),
		// If you want to pass in any custom vars (not mandatory),
        // make sure you pass them in the right order
		'customVars' => array(
			'some var 0',
			'some var 1',
		),
	)
);

$response = $request->send();

if ($response->isSuccessful() && $response->isVerified())
{
	$transactionId = $response->getTransactionReference();
}
```

All the other Requests work accordingly:

### Update (update the order-id)

```php
$request = $gateway->update(array(
	'transactionId' => $transactionId,
	'orderId' => 'n3w0rd3r1d',
));
```

### Resend Email (resend the email to the customer)

```php
$request = $gateway->resendEmail(array(
	'transactionId' => $transactionId,
	'language' => 'de', // not mandatory
));
```

### Void (cancel the transaction so the customer will stop receiving payment reminders)

```php
$request = $gateway->void(array(
	'transactionId' => $transactionId,
	'language' => 'de', // not mandatory
));
```

### Refund (give them their money back...)

```php
$request = $gateway->refund(array(
	'transactionId' => $transactionId,
	'amount' => '20.00',
	'currency' => 'EUR',
	'language' => 'de', // not mandatory
));
```

You can find a really great API documentation at https://integration.barzahlen.de/de/api

Please note that this is **not the official** API implementation! You can find officially supportet Barzahlen API libraries at https://integration.barzahlen.de/de/api/api-bibliotheken

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/sauladam/omnipay-barzahlen/issues),
or better yet, fork the library and submit a pull request.
