<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PassportUser extends Model
{
    protected $table = 'user';

    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;
}
