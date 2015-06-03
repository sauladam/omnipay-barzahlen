<?php

namespace Omnipay\Barzahlen\Message;

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(), 'somePaymentKey');

        /* Check the request details */
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isVerified());
        $this->assertFalse($response->isRedirect());

        /* Check the errors */
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getErrorCode());

        /* Check the transaction details */
        $this->assertSame('68192594', $response->getTransactionReference());
        $this->assertContains(
            'Ihr Zahlschein ist bis zum 24.04.2014 g&uuml;ltig.',
            $response->getExpirationNotice()
        );
        $this->assertContains(
            'In der Partnerfiliale wird der Zahlcode in die Kasse eingegeben',
            $response->getInfoText1()
        );
        $this->assertNull($response->getInfoText2());
        $this->assertNull($response->getRefundTransactionId());
        $this->assertNull($response->getOriginTransactionId());
        $this->assertEquals(
            '5245687cc8921fe35229ecf220581562184ea5c54c5ac011a098bbd35ac61244af9ec612c9157ad97c4c0fd4898dd4d3f411857ea81874ce41f453c88a2da5c2',
            $response->getHash()
        );

        /* Splitted the parts of the whole url for better readability */
        $this->assertContains('https://payment-sandbox.barzahlen.de/', $response->getPaymentSlipLink());
        $this->assertContains('download/', $response->getPaymentSlipLink());
        $this->assertContains('4053361252540/', $response->getPaymentSlipLink());
        $this->assertContains(
            'cd331996e17f746e2aa91ee6d20a0f88d7a2624aba8e478e6fedc5d6f751b765/',
            $response->getPaymentSlipLink()
        );
        $this->assertContains('Zahlschein_Barzahlen_68192594.pdf', $response->getPaymentSlipLink());

        /* Check the additional data */
        $this->assertNotNull($response->getRaw());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(), 'somePaymentKey');

        /* Check the request details */
        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->isVerified());
        $this->assertFalse($response->isRedirect());

        /* Check the errors */
        $this->assertSame('amount not valid', $response->getMessage());
        $this->assertSame('22', $response->getErrorCode());

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
