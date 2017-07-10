<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
//    订单初始状态 创建成功
    const STATUS_INIT = 0;

//     支付成功状态
    const STATUS_PAID = 1;

//    支付失败状态
    const STATUS_FAILED = 2;

//    到账状态
    const STATUS_ARRIVE = 3;

//    支付方式 阿里
    const PAY_ALIPAY = "alipay";

    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;
}
