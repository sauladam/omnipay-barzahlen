<?php

namespace Omnipay\Barzahlen\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    private $request;

    private $options;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();

        $this->request = new PurchaseRequest($client, $request);

        $this->options = array(

            'shopId' => 'someShopId',
            'paymentKey' => 'somePaymentKey',

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

        $this->request->initialize($this->options);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->options['shopId'], $data['shop_id']);
        $this->assertSame($this->options['paymentKey'], $this->request->getPaymentKey());
        $this->assertSame($this->options['currency'], $data['currency']);
        $this->assertSame($this->options['amount'], $data['amount']);
        $this->assertSame($this->options['orderId'], $data['order_id']);
        $this->assertSame($this->options['customerDetails']['email'], $data['customer_email']);
        $this->assertSame($this->options['customerDetails']['street_nr'], $data['customer_street_nr']);
        $this->assertSame($this->options['customerDetails']['zipcode'], $data['customer_zipcode']);
        $this->assertSame($this->options['customerDetails']['city'], $data['customer_city']);
        $this->assertSame($this->options['customerDetails']['country'], $data['customer_country']);
        $this->assertSame($this->options['customVars'][0], $data['custom_var_0']);
        $this->assertSame($this->options['customVars'][1], $data['custom_var_1']);
        $this->assertFalse(isset($data['custom_var_2']));

        $expectedHash = AbstractRequest::createHashFromArray(
            $this->request->getHashableData(),
            $this->options['paymentKey']
        );

        $this->assertSame($data['hash'], $expectedHash);
    }

    public function testSend()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isVerified());
        $this->assertEquals('68192594', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }
}
