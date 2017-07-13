<?php
namespace App\Service;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class CashInfo extends Base
{
    public function writeCashInfo($account = "",$amount = 0,$areaId = 0)
    {
        $connect = $this->switchDataConnect($areaId);
        if(empty($connect))
        {
            return $this->response(false,'充值失败,不存在该区',[]);
        }
        $cashInfo = $connect->table("cash_info")->where("login",$account)->first();
        if(!empty($cashInfo))
        {
            $result = $connect->table("cash_info")->where("login",$account)->update(['cash' => $cashInfo->cash+$amount]);
            if($result)
            {
                Log::info("充值到账成功",['table' => 'cash_info','account' => $account]);
                return $this->response(true,'记录成功',[]);
            }
            else
            {
                Log::error("充值失败",['table' => 'cash_info','account' => $account]);
                return $this->response(false,'充值失败',[]);
            }
        }
        else
        {
            $connect->table("cash_info")->insert(['cash' => $amount,'login' => $account]);
            Log::info("充值到账成功",['table' => 'cash_info','account' => $account]);
            return $this->response(true,'写入成功',[]);
        }

    }

    protected function switchDataConnect($areaId = 0)
    {
        $areaId = intval($areaId);
        $connect = "";
        switch ($areaId)
        {
            case 1:
                $connect = DB::connection('metin1');
                break;
            case 2:
                $connect = DB::connection('metin2');
                break;
        }
        return $connect;
    }


}