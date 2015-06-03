<?php

namespace Omnipay\Barzahlen\Message;

use Omnipay\Tests\TestCase;

class VoidResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('VoidSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(), 'somePaymentKey');

        /* Check the request details */
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isVerified());
        $this->assertFalse($response->isRedirect());

        /* Check the errors */
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getErrorCode());

        /* Check the transaction details */
        $this->assertSame('68209959', $response->getTransactionReference());
        $this->assertNull($response->getExpirationNotice());
        $this->assertNull($response->getPaymentSlipLink());
        $this->assertNull($response->getInfoText1());
        $this->assertNull($response->getInfoText2());
        $this->assertNull($response->getOriginTransactionId());
        $this->assertNull($response->getRefundTransactionId());
        $this->assertEquals(
            'e046597ffb8adbd9e9bf23c91d62b7c212db8251f35eed1448b9c7678c002c20fe13ff6def9a31fb54bcb6ee97c80c1533168ff7006d42aa830d8dc472e2d67c',
            $response->getHash()
        );

        /* Check the additional data */
        $this->assertNotNull($response->getRaw());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('VoidFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(), 'somePaymentKey');

        /* Check the request details */
        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->isVerified());
        $this->assertFalse($response->isRedirect());

        /* Check the errors */
        $this->assertSame('transaction not found', $response->getMessage());
        $this->assertSame('11', $response->getErrorCode());

        /* Check the transaction details */
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getExpirationNotice());
        $this->assertNull($response->getPaymentSlipLink());
        $this->assertNull($response->getInfoText1());
        $this->assertNull($response->getInfoText2());
        $this->assertNull($response->getRefundTransactionId());
        $this->assertNull($response->getOriginTransactionId());
        $this->assertNull($response->getHash());

        /* Check the additional data */
        $this->assertNotNull($response->getRaw());
    }
}
