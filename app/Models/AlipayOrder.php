<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlipayOrder extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'alipay_order';

    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;
}
