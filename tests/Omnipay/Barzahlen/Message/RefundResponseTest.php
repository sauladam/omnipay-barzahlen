<?php

namespace Omnipay\Barzahlen\Message;

use Omnipay\Tests\TestCase;

class RefundResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('RefundSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(), 'somePaymentKey');

        /* Check the request details */
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isVerified());
        $this->assertFalse($response->isRedirect());

        /* Check the errors */
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getErrorCode());

        /* Check the transaction details */
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getExpirationNotice());
        $this->assertNull($response->getPaymentSlipLink());
        $this->assertNull($response->getInfoText1());
        $this->assertNull($response->getInfoText2());
        $this->assertSame('22780174', $response->getOriginTransactionId());
        $this->assertSame('22791842', $response->getRefundTransactionId());
        $this->assertEquals(
            'b89f4080f45348a56851b5e8c5d66c49bb2023f989caf40a62c411b7837a207b15530afcc3844883c14d8fdb2b0dba4bda60dfb5d3870b79d880b4cce148209f',
            $response->getHash()
        );

        /* Check the additional data */
        $this->assertNotNull($response->getRaw());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('RefundFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(),'somePaymentKey');

        /* Check the request details */
        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->isVerified());
        $this->assertFalse($response->isRedirect());

        /* Check the errors */
        $this->assertSame('currency not accepted', $response->getMessage());
        $this->assertSame('20', $response->getErrorCode());

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
