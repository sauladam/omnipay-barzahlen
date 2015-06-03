<?php

namespace Omnipay\Barzahlen\Message;

use Omnipay\Barzahlen\Hasher;
use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    /**
     * @var RefundRequest
     */
    protected $request;

    /**
     * @var array
     */
    protected $options;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();

        $this->request = new RefundRequest($client, $request);

        $this->options = array(
            'shopId' => 'someShopId',
            'paymentKey' => 'somePaymentKey',
            'transactionId' => 'someTxnId',
            'language' => 'de',
            'currency' => 'EUR',
            'amount' => '111.59',
        );

        $this->request->initialize($this->options);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->options['shopId'], $data['shop_id']);
        $this->assertSame($this->options['paymentKey'], $this->request->getPaymentKey());
        $this->assertSame($this->options['transactionId'], $data['transaction_id']);
        $this->assertSame($this->options['language'], $data['language']);
        $this->assertSame($this->options['currency'], $data['currency']);
        $this->assertSame($this->options['amount'], $data['amount']);

        $expectedHash = Hasher::fromArray(
            $data,
            $this->options['paymentKey']
        );

        $this->assertSame($data['hash'], $expectedHash);
    }

    public function testSend()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isVerified());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('22780174', $response->getOriginTransactionId());
        $this->assertSame('22791842', $response->getRefundTransactionId());
        $this->assertNull($response->getMessage());

        $expectedHash = Hasher::fromArray(
            $response->asArray(),
            $this->options['paymentKey']
        );

        $this->assertSame($response->getHash(), $expectedHash);
    }
}
