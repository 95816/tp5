<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/29
 * Time: 17:38
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' => 'require|isMobile',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty',
    ];

    protected $message = [
        'name' => '姓名必须存在不能为空',
        'mobile' => '电话号码格式不正确',
        'province' => '省份必须存在不能为空',
        'city' => '城市必须存在不能为空',
        'country' => '乡镇地址必须存在不能为空',
        'detail' => '详细地址必须存在不能为空'
    ];

    protected function isMobile($value)
    {
        if (preg_match("/^1[34578]{1}\d{9}$/", $value)) {
            return true;
        } else {
            return false;
        }
    }

}