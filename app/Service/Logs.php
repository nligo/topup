<?php
namespace App\Service;

use App\Models\UserTopupLogs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\OrdersLogs;

class Logs
{
    public function writeOrderLog($status = 0,$msg = "",$orderId)
    {
        $orderLogs = new OrdersLogs();
        $orderLogs->order_status = $status;
        $orderLogs->msg = $msg;
        $orderLogs->order_id = $orderId;
        $orderLogs->save();
        return $orderLogs;
    }

    public function writeTopupLog($amount = 0.00 ,$type = 0,$msg = "",$userId = 0)
    {
        $log = new UserTopupLogs();
        $log->type = $type;
        $log->amount = $amount;
        $log->msg = $msg;
        $log->user_id = $userId;
        $log->save();
        return $log;
    }
}