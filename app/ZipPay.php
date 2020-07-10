<?php

namespace App;

use Exception;
use Omnipay\Omnipay;

/**
 * Class ZipPay
 * @package App
 */
class ZipPay
{
    /**
     * @return \SecureGateway
     */
    public function gateway()
    {
        $gateway = Omnipay::create('ZipPay_Rest');

        $gateway->getApiKey(config('services.zippay.public_key'));
        $gateway->setKey(config('services.zippay.private_key'));
        $gateway->setTestMode(config('services.zippay.sandbox'));

        return $gateway;
    }

    /**
     * @param array $parameters
     * @return $response
     */
    public function authorize(array $parameters)
    {
        try {
            $response = $this->gateway()
                ->authorize($parameters);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $amount
     */
    public function formatAmount($amount)
    {
        return number_format($amount, 2, '.', '');
    }

    /**
     * @param $order
     */
    public function getFailedUrl($order)
    {
        return route('checkout.payment.zippay.failed', $order->id);
    }

    /**
     * @param $order
     */
    public function getReturnUrl($order)
    {
        return route('checkout.payment.zippay.completed', $order->id);
    }
}
