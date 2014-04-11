<?php

namespace Omnipay\Barzahlen\Message;

/**
 * Barzahlen Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const HASHALGO = 'sha512'; // hash algorithm
    const SEPARATOR = ';'; // separator character
    const MAXATTEMPTS = 2; // maximum of allowed connection attempts

    protected $liveEndpoint = 'https://api.barzahlen.de/v1/transactions';
    protected $testEndpoint = 'https://api-sandbox.barzahlen.de/v1/transactions';

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
        $supported = array('de', 'en');

        $isSupported = in_array(strtolower($value), $supported);

        $language = $isSupported ? $value : 'de';

        return $this->setParameter('language', $language);
    }

    public function getCustomerDetails()
    {
        $array = $this->getParameter('customerDetails');

        return $this->correctArrayKeys($array, 'customer_');
    }

    public function setCustomerDetails($value)
    {
        return $this->setParameter('customerDetails', $value);
    }

    public function getHashableData()
    {
        return $this->getParameter('hashableData');
    }

    /**
     * This one is protected because we don't want the data
     * to be set from outside. This is basically just a helper
     * method for better testing.
     */
    protected function setHashableData($value)
    {
        return $this->setParameter('hashableData', $value);
    }

    public function getCustomVars()
    {
        /**
         * We need to make sure that all three vars are set
         * before hashing, even though the user passed in less.
         * After that, the empty ones will be removed again.
         */
        $default = array(
            'custom_var_0' => '',
            'custom_var_1' => '',
            'custom_var_2' => '',
        );

        $array = (array) $this->getParameter('customVars');

        $array = $this->correctArrayKeys($array, 'custom_var_');

        return array_merge($default, $array);
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    public function getOrderId()
    {
        return $this->getValue('orderId');
    }

    public function setDueDate($value)
    {
        return $this->setParameter('dueDate', $value);
    }

    public function getDueDate()
    {
        return $this->getValue('dueDate');
    }

    public function setCustomVars($value)
    {
        return $this->setParameter('customVars', $value);
    }

    public function getTransactionId()
    {
        return $this->getParameter('transactionId');
    }

    public function setTransactionId($value)
    {
        return $this->setParameter('transactionId', $value);
    }

    protected function getValue($key)
    {
        $value = $this->getParameter($key);

        return null === $value ? '' : $value;
    }

    protected function correctArrayKeys($array, $keyPrefix = '')
    {
        $results = array();

        foreach ($array as $key => $value) {
            $newKey = $keyPrefix.$key;
            $results[$newKey] = $value;
        }

        return $results;
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    protected function removeEmptyValues(array &$array)
    {
        foreach ($array as $key => $value) {
            if ($value == '') {
                unset($array[$key]);
            }
        }
    }

    public static function createHashFromArray($hashArray, $paymentKey)
    {
        $hashArray[] = $paymentKey;

        $hashString = implode(self::SEPARATOR, $hashArray);

        return hash(self::HASHALGO, $hashString);
    }

    public function sendData($data)
    {
        $url = $this->getEndpoint();

        /**
         * A "400 Bad Request" or a "404 Not Found"-response from the API
         * is perfectly valid and contains useful information about what's wrong,
         * so we have to prevent Guzzle from spitting Exceptions because
         * it thinks the request failed.
         * 500, however, indicates that something is wrong with the server,
         * so throwing an exception is appropriate
         */
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function (\Guzzle\Common\Event $event) {
                if (in_array($event['response']->getStatusCode(), array(400, 404))) {
                    $event->stopPropagation();
                }
            }
        );

        $httpResponse = $this->httpClient->post($url, null, $data)->send();

        return $this->createResponse($httpResponse->getBody());
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data, $this->getPaymentKey());
    }
}
