<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/25
 * Time: 10:22
 */

namespace app\api\validate;


class TokenValidate extends BaseValidate
{
    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];
    protected $message = [
        'code' => '请求参数code必须存在且不为空!'
    ];
}