<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account' => 'required',
            'phone' => 'required',
            'topupAmount' => 'required',
            'topupNum' => 'required',
            'payType' => 'required',
        ];
    }

    /**
     * 获取已定义验证规则的错误消息。
     *
     * @return array
     */
    public function messages()
    {
        return [
            'account.required' => '请输入账号',
            'body.required'  => '请输入手机号',
            'topupAmount.required'  => '请选择充值金额',
            'topupNum.required'  => '请选择充值数量',
            'payType.required'  => '请选择支付类型',
        ];
    }


}
