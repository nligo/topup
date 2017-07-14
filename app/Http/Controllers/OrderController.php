<?php

namespace App\Http\Controllers;

use App\Models\AreaClothing;
use App\Service\AlipayOrders;
use App\Service\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function request()
    {
        Session::put('form_token', time());
        $areaList = AreaClothing::where('status', 0)->get();

        return view('order.request_bat', ['arealist' => $areaList]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account' => 'required|alpha_dash|max:255',
            'phone' => 'alpha_num|required',
            'topupAmount' => 'required',
            'topupNum' => 'required|alpha_num',
            'payType' => 'required',
            'areaClothingId' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('order/request')
                ->withErrors($validator)
                ->withInput();
        }
        $formToken = $request->request->get('form_token', '');
        $sessionFormToken = Session::get('form_token');
        if ($formToken == $sessionFormToken) {
            $account = $request->request->get('account', '');
            $topupAmount = $request->request->get('topupAmount', '');
            $topupNum = $request->request->get('topupNum', '');
            $phone = $request->request->get('phone', '');
            $areaClothingId = $request->request->getInt('areaClothingId', 0);
            $orderPrice = $topupAmount * $topupNum;
            $data = [
                'account' => $account,
                'topup_amount' => $topupAmount,
                'topup_num' => $topupNum,
                'order_price' => $orderPrice,
                'topup_amount' => $orderPrice,
                'pay_type' => \App\Models\Orders::PAY_ALIPAY,
                'phone' => $phone,
                'area_clothing_id' => $areaClothingId,
            ];
            $orders = new Orders();
            $result = $orders->createOrder($data);
            Session::forget('form_token');
            if ($result['status']) {
                return redirect()->route('order_pay', ['orderId' => $result['data']->id]);
            }
        }

        return redirect('order/request');
    }

    public function pay($orderId, Request $request)
    {
        $order = \App\Models\Orders::find($orderId);
        $alipay = app('alipay.web');
        $alipay->setOutTradeNo($order->order_sn);
        $alipay->setTotalFee($order->order_price);
        $alipay->setSubject('游狗网络账号充值');
        $alipay->setBody('游狗网络账号充值');

        return redirect()->to($alipay->getPayLink());
    }

    public function notify(Request $request)
    {
        // 验证请求。
        if (!app('alipay.web')->verify()) {
            Log::notice('Alipay return query data verification fail.');
            return view('order.fail');
        }

        // 判断通知类型。
        switch (Input::get('trade_status')) {
            case 'TRADE_SUCCESS':
            case 'TRADE_FINISHED':
                $data = [
                    'discount'      =>  $request->request->get('discount', 0.00),
                    'payment_type'  =>  $request->request->get('payment_type', 1),
                    'trade_no'      =>  $request->request->get('trade_no', ''),
                    'subject'       =>  $request->request->get('subject', ''),
                    'gmt_create'    =>  $request->request->get('gmt_create'),
                    'notify_type'   =>  $request->request->get('notify_type', ''),
                    'quantity'      =>  $request->request->get('quantity', ''),
                    'out_trade_no'  =>  $request->request->get('out_trade_no', ''),
                    'seller_id'     =>  $request->request->get('seller_id', ''),
                    'notify_time'   =>  $request->request->get('notify_time'),
                    'buyer_email'   =>  $request->request->get('buyer_email', ''),
                    'body'          =>  $request->request->get('body', ''),
                    'trade_status'  =>  $request->request->get('trade_status', ''),
                    'total_fee'     =>  $request->request->get('total_fee', 0.00),
                    'gmt_payment'   =>  $request->request->get('gmt_payment'),
                    'seller_email'  =>  $request->request->get('seller_email', ''),
                    'price'         =>  $request->request->get('price', 0.00),
                    'buyer_id'      =>  $request->request->get('buyer_id', ''),
                    'notify_id'     =>  $request->request->get('notify_id', ''),
                    'use_coupon'    =>  $request->request->get('use_coupon', ''),
                    'sign_type'     =>  $request->request->get('sign_type', ''),
                    'sign'          =>  $request->request->get('sign', ''),
                    'is_total_fee_adjust' =>    $request->request->get('is_total_fee_adjust', ''),
                ];
                $alipayOrder = new AlipayOrders();
                $response = $alipayOrder->createAlipayOrder($data);
                if ($response['status']) {
                    return 'success';
                }
                break;
        }

        return 'failed';
    }

    /**
     * 同步通知.
     */
    public function webReturn(Request $request)
    {
        // 验证请求。
        if (!app('alipay.web')->verify()) {
            Log::notice('Alipay return query data verification fail.');

            return view('order.fail');
        }

        // 判断通知类型。
        switch (Input::get('trade_status')) {
            case 'TRADE_SUCCESS':
            case 'TRADE_FINISHED':
            return view('order.success');
        }

        return view('order.success');
    }
}
