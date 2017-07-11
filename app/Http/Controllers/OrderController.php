<?php

namespace App\Http\Controllers;

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
        Session::put('form_token',time());
        return view('order.request');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account' => 'required|alpha_dash|max:255',
            'phone' => 'alpha_num|required',
            'topupAmount' => 'required',
            'topupNum' => 'required|alpha_num',
            'payType' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('order/request')
                ->withErrors($validator)
                ->withInput();
        }
        $formToken = $request->request->get('form_token','');
        $sessionFormToken = Session::get('form_token');
        if($formToken == $sessionFormToken)
        {
            $account = $request->request->get('account', '');
            $topupAmount = $request->request->get('topupAmount', '');
            $topupNum = $request->request->get('topupNum', '');
            $phone = $request->request->get('phone', '');
            $orderPrice = $topupAmount * $topupNum;
            $data = [
                'account' => $account,
                'topup_amount' => $topupAmount,
                'topup_num' => $topupNum,
                'order_price' => $orderPrice,
                'topup_amount' => $orderPrice,
                'pay_type' => \App\Models\Orders::PAY_ALIPAY,
                'phone' => $phone
            ];
            $orders = new Orders();
            $result = $orders->createOrder($data);
            Session::forget('form_token');
            if ($result['status']) {
                return redirect()->route('order_pay',['orderId' => $result['data']->id]);
            }
        }
        return redirect('order/request');
    }


    public function pay($orderId,Request $request)
    {
        $order = \App\Models\Orders::find($orderId);
        $alipay = app('alipay.web');
        $alipay->setOutTradeNo($order->order_sn);
        $alipay->setTotalFee($order->order_price);
        $alipay->setSubject("游狗网络账号充值");
        $alipay->setBody('游狗网络账号充值');
        return redirect()->to($alipay->getPayLink());
    }

    public function notify(Request $request)
    {
        // 验证请求。
        if (! app('alipay.web')->verify()) {
            Log::notice('Alipay return query data verification fail.');
            return view('alipay.fail');
        }

        // 判断通知类型。
        switch (Input::get('trade_status')) {
            case 'TRADE_SUCCESS':
            case 'TRADE_FINISHED':
                // TODO: 支付成功，取得订单号进行其它相关操作。
                $result = (array)$request->request->getIterator();
                Log::debug(json_encode($result));


                $alipayOrder = new AlipayOrders();
                $response = $alipayOrder->createAlipayOrder($result);
                if($response['status'])
                {
                    return "success";
                }
                break;

        }

        return "failed";

    }

    /**
     * 同步通知
     */
    public function webReturn(Request $request)
    {

        // 验证请求。
        if (! app('alipay.web')->verify()) {
            Log::notice('Alipay return query data verification fail.');
            return view('alipay.fail');
        }

        // 判断通知类型。
        switch (Input::get('trade_status')) {
            case 'TRADE_SUCCESS':
            case 'TRADE_FINISHED':
                // TODO: 支付成功，取得订单号进行其它相关操作。
//                $result = (array)$request->query->getIterator();
//
//                $alipayOrder = new AlipayOrders();
//                $response = $alipayOrder->createAlipayOrder($result);
//                if($response['status'])
//                {
//                    return view('order.success');
//                }
                break;
        }

        return view('order.success');
    }
}
