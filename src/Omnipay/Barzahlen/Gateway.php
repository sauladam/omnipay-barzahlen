<?php

namespace Omnipay\Barzahlen;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Barzahlen';
    }

    public function getDefaultParameters()
    {
        return array(
            'shopId' => '',
            'paymentKey' => '',
            'language' => 'de',
            'testMode' => true,
        );
    }

    public function getShopId()
    {
        return $this->getParameter('shopId');
    }

    public function setShopId($value)
    {
        return $this->setParameter('shopId', $value);
    }

    public function getPaymentKey()
    {
        return $this->getParameter('paymentKey');
    }

    public function setPaymentKey($value)
    {
        return $this->setParameter('paymentKey', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function update(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Barzahlen\Message\UpdateRequest', $parameters);
    }

    public function resendEmail(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Barzahlen\Message\ResendEmailRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Barzahlen\Message\RefundRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Barzahlen\Message\VoidRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Barzahlen\Message\PurchaseRequest', $parameters);
    }
}
