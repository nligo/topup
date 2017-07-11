<?php
namespace App\Service;

use App\Models\Orders as Order;
use App\Models\AlipayOrder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Service\OrdersLog;
class Orders extends Base
{
    public function createOrder(array $data)
    {
        DB::beginTransaction();
        $orderSn = $this->generateOrderSn();
        try
        {
            $order = new Order();
            $order->order_sn = $orderSn;
            $order->ip_address = $this->getIp();
            foreach ($data as $key=>$item)
            {
                $order->$key = $item;
            }
            $order->order_status = Order::STATUS_INIT;
            $order->save();
            $log = new Logs();
            $log->writeOrderLog(Order::STATUS_INIT,'下单成功',$order->id);
            DB::commit();
            return $this->response(true,'操作成功',$order);
        }
        catch (\Exception $e)
        {
            Log::debug($e->getMessage());
            DB::rollBack();
            return $this->response(false,$e->getMessage());
        }

    }


    public function findOrderByStatus($status = Order::STATUS_INIT)
    {
        $orderList = Order::where("order_status",$status)->get();
        dump($orderList);exit;
        return $orderList;
    }


    protected function generateOrderSn()
    {
        return 'UGO'.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    protected function getIp()
    {
        $unknown = 'unknown';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        /*
        处理多层代理的情况
        或者使用正则方式：$ip = preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown;
        */
        if (false !== strpos($ip, ','))
            $ip = reset(explode(',', $ip));
        return $ip;
    }
}