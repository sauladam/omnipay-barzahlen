<?php

namespace Omnipay\Barzahlen\Message;

use Omnipay\Barzahlen\Hasher;

/**
 * Barzahlen create Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $action = 'create';

    public function getData()
    {
        $this->validate('amount', 'currency', 'customerDetails');

        $customerDetails = $this->getCustomerDetails();
        $customVars = $this->getCustomVars();

        /**
         * The order the values are in matters because a different order
         * would result in a different hash!
         */
        $requestArray = array();

        $requestArray['shop_id'] = $this->getShopId();
        $requestArray['customer_email'] = $customerDetails['customer_email'];
        $requestArray['amount'] = $this->getAmount();
        $requestArray['currency'] = $this->getCurrency();
        $requestArray['language'] = $this->getLanguage();
        $requestArray['order_id'] = $this->getOrderId();
        $requestArray['customer_street_nr'] = $customerDetails['customer_street_nr'];
        $requestArray['customer_zipcode'] = $customerDetails['customer_zipcode'];
        $requestArray['customer_city'] = $customerDetails['customer_city'];
        $requestArray['customer_country'] = $customerDetails['customer_country'];
        $requestArray['custom_var_0'] = $customVars['custom_var_0'];
        $requestArray['custom_var_1'] = $customVars['custom_var_1'];
        $requestArray['custom_var_2'] = $customVars['custom_var_2'];

        //$requestArray['hash'] = Hasher::fromArray($requestArray, $this->getPaymentKey());

        $requestArray['due_date'] = $this->getDueDate();

        return $this->prepareForSending($requestArray);
    }

    public function getEndpoint()
    {
        $endpoint = parent::getEndpoint();

        return "{$endpoint}/{$this->action}";
    }
}
