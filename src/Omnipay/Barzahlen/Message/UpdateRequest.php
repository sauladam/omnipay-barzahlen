<?php

namespace Omnipay\Barzahlen\Message;

/**
 * Barzahlen Update Request
 */
class UpdateRequest extends AbstractRequest
{
    protected $action = 'update';

    public function getData()
    {
        $this->validate('shopId', 'transactionId', 'orderId');

        $requestArray = array();

        $requestArray['shop_id'] = $this->getShopId();
        $requestArray['transaction_id'] = $this->getTransactionId();
        $requestArray['order_id'] = $this->getOrderId();

        return $this->prepareForSending($requestArray);
    }

    public function getEndpoint()
    {
        $endpoint = parent::getEndpoint();

        return "{$endpoint}/{$this->action}";
    }
}
