<?php
namespace App\Service;

use App\Models\UserTopupLogs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\PassportUser as User;
use App\Service\Logs;

class PassportUser extends Base
{
    protected $log;

    protected $orderService;

    public function __construct()
    {
        $this->log = new Logs();
        $this->orderService = new Orders();

    }

    public function checkUserIsExits($userId = 0)
    {
        $user = User::find($userId);
        if(empty($user))
        {
            return $this->response(false,'用户不存在');
        }
        return $this->response(true,'',$user);
    }


    public function addAmount($orderId = 0)
    {

        $orderInfo = $this->orderService->findOrderById($orderId);
        if(empty($orderInfo))
        {
            Log::debug("订单不存在");
            return $this->response(false,'订单不存在');
        }
        $userId = $orderInfo->user_id;
        $result = $this->checkUserIsExits($userId);
        if($result['status'])
        {
            $user = $result['data'];

            try
            {
                $user->total_amount = $user->total_amount+$orderInfo->order_price;
                $this->log->writeTopupLog($orderInfo->order_price,0,'充值到账提醒',$userId);
                $user->save();
                $orderInfo->order_status = \App\Models\Orders::STATUS_ARRIVE;
                $this->log->writeOrderLog(1,'用户充值到账提醒',$orderInfo->id);
                $orderInfo->save();
                Log::info("用户充值到账处理成功",['userId' => $userId,'orderId' => $orderInfo->id]);
                return $this->response(true,"用户充值到账处理成功");
            }
            catch (\Exception $e)
            {
                Log::error($e->getMessage());
                return $this->response(false,$e->getMessage());
            }
        }
        return $result;
    }


    public function reduceTotalAmount()
    {

    }


}