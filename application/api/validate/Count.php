<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/24
 * Time: 10:56
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'isPositiveInteger|between:1,30'
    ];
}