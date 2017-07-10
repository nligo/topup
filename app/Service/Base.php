<?php

namespace App\Service;


class Base
{
    protected function response($status = false,$msg = "提示信息",$data = [])
    {
        return [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];
    }
}