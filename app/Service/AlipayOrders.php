<?php

namespace App\Service;

use App\Models\Orders as Order;
use App\Models\AlipayOrder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AlipayOrders extends Base
{
    public function createAlipayOrder(array $data)
    {
        $orderSn = isset($data['out_trade_no']) ? $data['out_trade_no'] : '';
        $alipayInfo = AlipayOrder::where('out_trade_no', $orderSn)->first();
        if (!empty($alipayInfo)) {
            return $this->response(false, '订单已经写入，请勿重复提交');
        }
        DB::beginTransaction();
        try {
            $alipayOrder = new AlipayOrder();
            foreach ($data as $key => $item) {
                $alipayOrder->$key = $item;
            }
            $order = \App\Models\Orders::where('order_sn', $orderSn)->first();
            $order->order_status = \App\Models\Orders::STATUS_PAID;
            $order->pay_price = $data['total_fee'];
            $order->pay_at = $data['notify_time'];
            $alipayOrder->order_id = $order->id;
            $alipayOrder->save();
            $logs = new Logs();
            $logs->writeOrderLog(Order::STATUS_PAID, '支付成功', $order->id);
            if(empty($order->area_clothing_id))
            {
                $passportUser = new PassportUser();
                $passportUser->addAmount($order->id);
            }
            else
            {
                $cashInfo = new CashInfo();
                $cashInfo->writeCashInfo($order->account,$order->order_price,$order->area_clothing_id);
                $order->order_status = \App\Models\Orders::STATUS_ARRIVE;
            }
            $order->save();
            DB::commit();
            return $this->response(true, '操作成功', $alipayOrder);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->response(false, $e->getMessage());
        }
    }
}
