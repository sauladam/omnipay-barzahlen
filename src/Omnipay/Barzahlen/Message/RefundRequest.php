<?php

namespace Omnipay\Barzahlen\Message;

/**
 * Barzahlen Refund Request
 */
class RefundRequest extends AbstractRequest
{
    protected $action = 'refund';

    public function getData()
    {
        $this->validate('shopId', 'transactionId', 'amount', 'currency');

        $requestArray = array();

        $requestArray['shop_id'] = $this->getShopId();
        $requestArray['transaction_id'] = $this->getTransactionId();
        $requestArray['amount'] = $this->getAmount();
        $requestArray['currency'] = $this->getCurrency();
        $requestArray['language'] = $this->getLanguage();

        return $this->prepareForSending($requestArray);
    }

    public function getEndpoint()
    {
        $endpoint = parent::getEndpoint();

        return "{$endpoint}/{$this->action}";

    }
}
