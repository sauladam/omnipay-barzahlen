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
