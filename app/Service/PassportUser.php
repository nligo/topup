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

    public function __construct()
    {
        $this->log = new Logs();

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

    public function handleTopupAmount()
    {
        $orderService = new Orders();
        $orderList = $orderService->findOrderByStatus(\App\Models\Orders::STATUS_PAID);
        if(empty($orderList))
        {
            Log::debug("等待充值订单为空");
            return $this->response(false,'等待充值订单为空');
        }

        DB::beginTransaction();
        try
        {
            foreach ($orderList as $item)
            {
                $userId = $item->user_id;
                $result = $this->addAmount($userId,$item->order_price);
                if(!$result['status'])
                {
                    return;
                }
            }
            DB::commit();
            Log::info("用户充值到账处理成功");
            return $this->response(true,"用户充值到账处理成功");

        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            DB::rollBack();
            return $this->response(false,$e->getMessage());
        }

    }

    public function addAmount($userId = 0,$amount = 0.00)
    {
        $result = $this->checkUserIsExits($userId);
        if($result['status'])
        {
            $user = $result['data'];
            $user->total_amount = $user->total_amount+$amount;
            $this->log->writeTopupLog($amount,0,'充值到账提醒',$userId);
            $user->save();
            Log::info("用户充值金额到账提醒",['userId' => $userId]);
            return $this->response(true,'充值到账提醒',$user);
        }
        return $result;
    }


    public function reduceTotalAmount()
    {

    }


}