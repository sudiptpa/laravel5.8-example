<?php

namespace App\Http\Controllers;

use App\Order;
use App\ZipPay;
use Exception;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;

/**
 * Class ZipPayController
 * @package App\Http\Controllers
 */
class ZipPayController extends Controller
{
    /**
     * @param Request $request
     */
    public function checkout(Request $request)
    {
        $order = Order::findOrFail(mt_rand(1, 20));

        return view('zippay.checkout', compact('order'));
    }

    /**
     * @param $order_id
     * @param Request $request
     */
    public function payment($order_id, Request $request)
    {
        $order = Order::findOrFail($order_id);

        $gateway = with(new ZipPay);

        $card = new CreditCard([
            'billingFirstName' => 'Sujip',
            'billingLastName' => 'Thapa',
            'email' => 'sudiptpa@gmail.com',
            'billingAddress1' => '6 Ct',
            'billingCity' => 'Geelong',
            'billingState' => 'VIC',
            'billingPostcode' => '3216',
            'billingCountry' => 'AU',
        ]);

        try {
            $response = $gateway->authorize([
                'amount' => 20.45,
                'reference' => 'B100677',
                'currency' => 'AUD',
                'card' => $card,
                'returnUrl' => $gateway->getReturnUrl($order),
            ])->send();

            return $response->getCode();

        } catch (Exception $e) {
            $order->update(['payment_status' => Order::PAYMENT_PENDING]);

            return redirect()
                ->route('checkout.payment.zippay.failed', [$order->id])
                ->with('message', sprintf("Your payment failed with error: %s", $e->getMessage()));
        }

        if ($response->isRedirect()) {
            $response->redirect();
        }

        return redirect()->back()->with([
            'message' => "We're unable to process your payment at the moment, please try again !",
        ]);
    }

    /**
     * @param $order_id
     * @param Request $request
     */
    public function completed($order_id, Request $request)
    {
        $order = Order::findOrFail($order_id);

        $gateway = with(new ZipPay);

        $response = $gateway->verifyPayment([
            'amount' => $gateway->formatAmount($order->amount),
            'referenceNumber' => $request->get('refId'),
            'productCode' => $request->get('oid'),
        ], $request);

        if ($response->isSuccessful()) {
            $order->update([
                'transaction_id' => $request->get('refId'),
                'payment_status' => Order::PAYMENT_COMPLETED,
            ]);

            return redirect()->route('checkout.payment.zippay')->with([
                'message' => 'Thank you for your shopping, Your recent payment was successful.',
            ]);
        }

        return redirect()->route('checkout.payment.zippay')->with([
            'message' => 'Thank you for your shopping, However, the payment has been declined.',
        ]);
    }

    /**
     * @param $order_id
     * @param Request $request
     */
    public function failed($order_id, Request $request)
    {
        $order = Order::findOrFail($order_id);

        return view('zippay.checkout', compact('order'));
    }
}
