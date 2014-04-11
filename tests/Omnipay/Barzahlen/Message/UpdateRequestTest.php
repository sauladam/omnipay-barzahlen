<?php

namespace Omnipay\Barzahlen\Message;

use Omnipay\Tests\TestCase;

class UpdateRequestTest extends TestCase
{
    private $request;

    private $options;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();

        $this->request = new UpdateRequest($client, $request);

        $this->options = array(
            'shopId' => 'someShopId',
            'paymentKey' => 'somePaymentKey',
            'transactionId' => 'someTxnId',
            'orderId' => '12345',
        );

        $this->request->initialize($this->options);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->options['shopId'], $data['shop_id']);
        $this->assertSame($this->options['paymentKey'], $this->request->getPaymentKey());
        $this->assertSame($this->options['transactionId'], $data['transaction_id']);
        $this->assertSame($this->options['orderId'], $data['order_id']);

        $expectedHash = AbstractRequest::createHashFromArray(
            $this->request->getHashableData(),
            $this->options['paymentKey']
        );

        $this->assertSame($data['hash'], $expectedHash);
    }

    public function testSend()
    {
        $this->setMockHttpResponse('UpdateSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isVerified());
        $this->assertSame('68194729', $response->getTransactionReference());
        $this->assertNull($response->getMessage());

        $expectedHash = AbstractRequest::createHashFromArray(
            $response->getHashableData(),
            $this->options['paymentKey']
        );

        $this->assertSame($response->getHash(), $expectedHash);
    }
}
