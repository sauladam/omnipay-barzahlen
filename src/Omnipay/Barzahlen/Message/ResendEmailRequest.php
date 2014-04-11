<?php

namespace Omnipay\Barzahlen\Message;

/**
 * Barzahlen Resend Email Request
 */
class ResendEmailRequest extends AbstractRequest
{
    protected $action = 'resend_email';

    public function getData()
    {
        $this->validate('shopId', 'transactionId');

        $requestArray = array();

        $requestArray['shop_id'] = $this->getShopId();
        $requestArray['transaction_id'] = $this->getTransactionId();
        $requestArray['language'] = $this->getLanguage();

        $this->setHashableData($requestArray);

        $requestArray['hash'] = self::createHashFromArray(
            $this->getHashableData(),
            $this->getPaymentKey()
        );

        $this->removeEmptyValues($requestArray);

        return $requestArray;
    }

    public function getEndpoint()
    {
        $endpoint = parent::getEndpoint();

        return "{$endpoint}/{$this->action}";
    }
}
