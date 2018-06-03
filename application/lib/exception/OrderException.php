<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/6/3
 * Time: 下午11:33
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $message = '订单不存在，请检查参数';
    public $errorCode = 80000;
}