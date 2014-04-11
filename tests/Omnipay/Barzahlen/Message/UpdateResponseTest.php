<?php

namespace Omnipay\Barzahlen\Message;

use Omnipay\Tests\TestCase;

class UpdateResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('UpdateSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(), 'somePaymentKey');

        /* Check the request details */
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isVerified());
        $this->assertFalse($response->isRedirect());

        /* Check the errors */
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getErrorCode());

        /* Check the transaction details */
        $this->assertSame('68194729', $response->getTransactionReference());
        $this->assertNull($response->getExpirationNotice());
        $this->assertNull($response->getPaymentSlipLink());
        $this->assertNull($response->getInfoText1());
        $this->assertNull($response->getInfoText2());
        $this->assertNull($response->getOriginTransactionId());
        $this->assertNull($response->getRefundTransactionId());
        $this->assertEquals(
            '6339545fc8868e5cb2717891644c29a50dcf8d6a98403b4f8837b341beab5b2404190358b4eabe328cb0f53c3faba58408b2640754e091723faf67c59b4d4a3a',
            $response->getHash()
        );

        /* Check the additional data */
        $this->assertNotNull($response->getRaw());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('UpdateFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(),'somePaymentKey');

        /* Check the request details */
        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->isVerified());
        $this->assertFalse($response->isRedirect());

        /* Check the errors */
        $this->assertSame('order_id already set', $response->getMessage());
        $this->assertSame('8', $response->getErrorCode());

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
