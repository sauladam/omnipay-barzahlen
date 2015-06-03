<?php

namespace Omnipay\Barzahlen\Message;

/**
 * Barzahlen Void Request
 */
class VoidRequest extends AbstractRequest
{
    protected $action = 'cancel';

    public function getData()
    {
        $this->validate('shopId', 'transactionId');

        $requestArray = array();

        $requestArray['shop_id'] = $this->getShopId();
        $requestArray['transaction_id'] = $this->getTransactionId();
        $requestArray['language'] = $this->getLanguage();

        return $this->prepareForSending($requestArray);
    }

    public function getEndpoint()
    {
        $endpoint = parent::getEndpoint();

        return "{$endpoint}/{$this->action}";
    }
}
