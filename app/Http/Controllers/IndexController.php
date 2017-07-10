<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayRequest;
use App\Models\AlipayOrder;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function test(Request $request)
    {
        Session::put('form_token',time());
        //下面增加两行，顺便看看Request::get的使用
        return view("pay.request");
    }

    public function pay(PayRequest $request)
    {
        if ($request->isMethod('post')) {
            $formToken = $request->request->get('form_token','');
            $sessionFormToken = Session::get('form_token');
            if($formToken == $sessionFormToken)
            {
                $account = $request->request->get('account','');
                $topupAmount = $request->request->get('topupAmount','');
                $topupNum = $request->request->get('topupNum','');
                $phone = $request->request->get('phone','');
                $orderPrice = $topupAmount*$topupNum;
                $data = [
                    'account' => $account,
                    'topup_amount' => $topupAmount,
                    'topup_num' => $topupNum,
                    'order_price' => $orderPrice,
                    'topup_amount' => $orderPrice,
                    'pay_type' => Orders::PAY_ALIPAY,
                    'phone' => $phone
                ];
                $orders = new \App\Service\Orders();
                $result = $orders->createOrder($data);
                Session::forget('form_token');
                if($result['status'])
                {
                    $alipay = app('alipay.web');
                    $alipay->setOutTradeNo($result['data']->order_sn);
                    $alipay->setTotalFee($result['data']->order_price);
                    $alipay->setSubject("游狗网络账号充值");
                    $alipay->setBody('游狗网络账号充值');
                    return redirect()->to($alipay->getPayLink());
                }
            }
            else
            {
                echo 'session 失效';exit;
            }

            echo '失败';exit;

        }


    }

    /**
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PayRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'account' => 'required',
            'user' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('/test')
                ->withErrors($validator)
                ->withInput();
        }
        echo 123;exit;
    }


    /**
     * 同步通知
     */
    public function webReturn(Request $request)
    {

        // 验证请求。
//        if (! app('alipay.web')->verify()) {
//            Log::notice('Alipay return query data verification fail.', [
//                'data' => Request::getQueryString()
//            ]);
//            return view('alipay.fail');
//        }

        // 判断通知类型。
        switch (Input::get('trade_status')) {
            case 'TRADE_SUCCESS':
            case 'TRADE_FINISHED':
                // TODO: 支付成功，取得订单号进行其它相关操作。
            $result = (array)$request->query->getIterator();
            $orderSn = Input::get('out_trade_no');
            $orderInfo = Orders::where('order_sn',$orderSn)->get();
            if(!empty($orderInfo[0]))
            {
                $orderInfo[0]->order_status = Orders::STATUS_PAID;
                $orderInfo[0]->save();
            }
            break;

        }

        return view('alipay.success');
    }
}
