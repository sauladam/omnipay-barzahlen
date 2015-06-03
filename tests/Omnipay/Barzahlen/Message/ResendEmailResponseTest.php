<?php

namespace Omnipay\Barzahlen\Message;

use Omnipay\Tests\TestCase;

class ResendEmailResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('ResendEmailSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(), 'somePaymentKey');

        /* Check the request details */
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isVerified());
        $this->assertFalse($response->isRedirect());

        /* Check the errors */
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getErrorCode());

        /* Check the transaction details */
        $this->assertSame('68206013', $response->getTransactionReference());
        $this->assertNull($response->getExpirationNotice());
        $this->assertNull($response->getPaymentSlipLink());
        $this->assertNull($response->getInfoText1());
        $this->assertNull($response->getInfoText2());
        $this->assertNull($response->getOriginTransactionId());
        $this->assertNull($response->getRefundTransactionId());
        $this->assertEquals(
            '1f76e56d1f9f6813d138ec4c1433ce50bb59c6eecd4f216ea7df70d34a679fd1723909209a9a4fde9abef5391a15845f448a59613b254c20ebd6024c8e87ee2a',
            $response->getHash()
        );

        /* Check the additional data */
        $this->assertNotNull($response->getRaw());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('ResendEmailFailure.txt');
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
