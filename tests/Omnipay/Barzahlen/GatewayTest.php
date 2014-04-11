<?php

namespace Omnipay\Barzahlen;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase()
    {
        $options = array(

            'currency' => 'EUR',
            'amount' => '111.59',
            'orderId' => '12345',

            'customerDetails' => array(
                'email' => 'test@test.com',
                'street_nr' => 'Testsreet 10a',
                'zipcode' => '12345',
                'city' => 'Test City',
                'country' => 'DE',
            ),

            'customVars' => array(
                'some var 0',
                'some var 1',
            ),
        );

        $request = $this->gateway->purchase($options);

        $this->assertInstanceOf('Omnipay\Barzahlen\Message\PurchaseRequest', $request);

        $customerDetails = $request->getCustomerDetails();
        $customVars = $request->getCustomVars();

        $this->assertSame($options['currency'], $request->getCurrency());
        $this->assertSame($options['amount'], $request->getAmount());
        $this->assertSame($options['orderId'], $request->getOrderId());
        $this->assertSame($options['customerDetails']['email'], $customerDetails['customer_email']);
        $this->assertSame($options['customerDetails']['street_nr'], $customerDetails['customer_street_nr']);
        $this->assertSame($options['customerDetails']['zipcode'], $customerDetails['customer_zipcode']);
        $this->assertSame($options['customerDetails']['city'], $customerDetails['customer_city']);
        $this->assertSame($options['customerDetails']['country'], $customerDetails['customer_country']);
        $this->assertSame($options['customVars'][0], $customVars['custom_var_0']);
        $this->assertSame($options['customVars'][1], $customVars['custom_var_1']);
        $this->assertSame('', $customVars['custom_var_2']);
        $this->assertTrue($request->getTestMode());
    }

    public function testRefund()
    {
        $options = array(
            'transactionId' => 'some_txn_id',
            'amount' => '20.00',
            'currency' => 'EUR',
            'language' => 'de',
        );

        $request = $this->gateway->refund($options);

        $this->assertInstanceOf('Omnipay\Barzahlen\Message\RefundRequest', $request);

        $this->assertSame($options['transactionId'], $request->getTransactionId());
        $this->assertSame($options['amount'], $request->getAmount());
        $this->assertSame($options['currency'], $request->getCurrency());
        $this->assertSame($options['language'], $request->getLanguage());
        $this->assertTrue($request->getTestMode());
    }

    public function testResendEmail()
    {
        $options = array(
            'transactionId' => 'some_txn_id',
            'language' => 'de',
        );

        $request = $this->gateway->resendEmail($options);

        $this->assertInstanceOf('Omnipay\Barzahlen\Message\ResendEmailRequest', $request);

        $this->assertSame($options['transactionId'], $request->getTransactionId());
        $this->assertSame($options['language'], $request->getLanguage());
        $this->assertTrue($request->getTestMode());
    }

    public function testUpdate()
    {
        $options = array(
            'transactionId' => 'some_txn_id',
            'language' => 'de',
        );

        $request = $this->gateway->update($options);

        $this->assertInstanceOf('Omnipay\Barzahlen\Message\UpdateRequest', $request);

        $this->assertSame($options['transactionId'], $request->getTransactionId());
        $this->assertSame($options['language'], $request->getLanguage());
        $this->assertTrue($request->getTestMode());
    }

    public function testVoid()
    {
        $options = array(
            'transactionId' => 'some_txn_id',
            'language' => 'de',
        );

        $request = $this->gateway->void($options);

        $this->assertInstanceOf('Omnipay\Barzahlen\Message\VoidRequest', $request);

        $this->assertSame($options['transactionId'], $request->getTransactionId());
        $this->assertSame($options['language'], $request->getLanguage());
        $this->assertTrue($request->getTestMode());
    }

    /**
     * We have to override some of the built in test-methods from
     * \Omnipay\Tests\GatewayTestCase because we want to silently default the
     * language to 'de' if any other language than ['de', 'en'] is
     * attempted to be set.
     */

    public function testVoidParameters()
    {
        if ($this->gateway->supportsVoid()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);
                $value = uniqid();
                $this->gateway->$setter($value);

                // request should have matching property, with correct value
                $request = $this->gateway->void();

                if ($key == 'language' && !in_array($value, array('de', 'en'))) {
                    $this->assertSame('de', $request->$getter());
                } else {
                    $this->assertSame($value, $request->$getter());
                }
            }
        }
    }

    public function testPurchaseParameters()
    {
        foreach ($this->gateway->getDefaultParameters() as $key => $default) {
            // set property on gateway
            $getter = 'get'.ucfirst($key);
            $setter = 'set'.ucfirst($key);
            $value = uniqid();
            $this->gateway->$setter($value);

            // request should have matching property, with correct value
            $request = $this->gateway->purchase();

            if ($key == 'language' && !in_array($value, array('de', 'en'))) {
                $this->assertSame('de', $request->$getter());
            } else {
                $this->assertSame($value, $request->$getter());
            }
        }
    }

    public function testRefundParameters()
    {
        if ($this->gateway->supportsRefund()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);
                $value = uniqid();
                $this->gateway->$setter($value);

                // request should have matching property, with correct value
                $request = $this->gateway->refund();

                if ($key == 'language' && !in_array($value, array('de', 'en'))) {
                    $this->assertSame('de', $request->$getter());
                } else {
                    $this->assertSame($value, $request->$getter());
                }
            }
        }
    }
}
