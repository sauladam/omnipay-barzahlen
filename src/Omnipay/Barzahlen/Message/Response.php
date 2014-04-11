<?php

namespace Omnipay\Barzahlen\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Barzahlen Response
 */
class Response extends AbstractResponse
{
    protected $request;
    protected $data;
    protected $paymentKey;

    public function __construct(RequestInterface $request, $data, $paymentKey)
    {
        $this->request = $request;
        $this->data = simplexml_load_string($data);
        $this->paymentKey = $paymentKey;
    }

    public function getRaw()
    {
        return $this->data;
    }

    public function isSuccessful()
    {
        $xmlValue = $this->data->result;

        return $this->extractValue($xmlValue) === '0';
    }

    public function isVerified()
    {
        if (!$this->isSuccessful()) {
            return null;
        }

        $hashableData = $this->getHashableData();

        $hash = AbstractRequest::createHashFromArray($hashableData, $this->paymentKey);

        return $hash === $this->getHash();
    }

    public function getHashableData()
    {
        $dataArray = $this->arrayFromXml($this->data);

        $ignoreKeys = ['hash'];

        return array_diff_key($dataArray, array_flip($ignoreKeys));
    }

    protected function arrayFromXml($xml)
    {
        $dataArray = json_decode(json_encode($this->data), true);

        foreach ($dataArray as &$item) {
            $item = is_array($item) ? '' : $item;
        }

        return $dataArray;
    }

    public function getTransactionReference()
    {
        $xmlValue = $this->data->{'transaction-id'};

        return $this->extractValue($xmlValue);
    }

    public function getPaymentSlipLink()
    {
        $xmlValue = $this->data->{'payment-slip-link'};

        return $this->extractValue($xmlValue);
    }

    public function getExpirationNotice()
    {
        $xmlValue = $this->data->{'expiration-notice'};

        return $this->extractValue($xmlValue);
    }

    public function getInfoText1()
    {
        $xmlValue = $this->data->{'infotext-1'};

        return $this->extractValue($xmlValue);
    }

    public function getInfoText2()
    {
        $xmlValue = $this->data->{'infotext-2'};

        return $this->extractValue($xmlValue);
    }

    public function getHash()
    {
        $xmlValue = $this->data->{'hash'};

        return $this->extractValue($xmlValue);
    }

    public function getOriginTransactionId()
    {
        $xmlValue = $this->data->{'origin-transaction-id'};

        return $this->extractValue($xmlValue);
    }

    public function getRefundTransactionId()
    {
        $xmlValue = $this->data->{'refund-transaction-id'};

        return $this->extractValue($xmlValue);
    }

    public function getMessage()
    {
        $xmlValue = $this->data->{'error-message'};

        return $this->extractValue($xmlValue);
    }

    public function getErrorCode()
    {
        $xmlValue = $this->data->result;

        $code = $this->extractValue($xmlValue);

        if ($code !== '0') {
            return $code;
        }

        return null;
    }

    protected function extractValue($xmlValue)
    {
        if (null !== $xmlValue) {
            $value = $xmlValue->__toString();

            if ('' !== $value) {
                return $value;
            }
        }

        return null;
    }
}
