<?php

namespace Omnipay\Barzahlen\Message;

use Omnipay\Barzahlen\Hasher;
use Omnipay\Tests\TestCase;

class ResendEmailRequestTest extends TestCase
{
    /**
     * @var ResendEmailRequest
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

        $this->request = new ResendEmailRequest($client, $request);

        $this->options = array(
            'shopId' => 'someShopId',
            'paymentKey' => 'somePaymentKey',
            'transactionId' => 'someTxnId',
            'language' => 'de',
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

        $expectedHash = Hasher::fromArray(
            $data,
            $this->options['paymentKey']
        );

        $this->assertSame($data['hash'], $expectedHash);
    }

    public function testSend()
    {
        $this->setMockHttpResponse('ResendEmailSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isVerified());
        $this->assertSame('68206013', $response->getTransactionReference());
        $this->assertNull($response->getMessage());

        $expectedHash = Hasher::fromArray(
            $response->asArray(),
            $this->options['paymentKey']
        );

        $this->assertSame($response->getHash(), $expectedHash);
    }
}
