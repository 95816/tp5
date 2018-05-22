<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/14
 * Time: 14:50
 */

namespace app\api\validate;

class ApiValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];

    public function isPositiveInteger($value, $field = '', $data = '', $rule = '')
    {
        if (is_numeric($value) && is_integer($value + 0) && (($value + 0) > 0)) {
            return true;
        } else {
            return $field . '必须是正整数!';
        }

    }

}