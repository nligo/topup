<?php
namespace App\Service;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\OrdersLogs;

class OrdersLog
{
    public function writeLog($status = 0,$msg = "",$orderId)
    {
        $orderLogs = new OrdersLogs();
        $orderLogs->order_status = $status;
        $orderLogs->msg = $msg;
        $orderLogs->order_id = $orderId;
        $orderLogs->save();
        return $orderLogs;
    }
}