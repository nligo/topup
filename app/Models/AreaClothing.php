<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaClothing extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'area_clothing';

    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;
}
