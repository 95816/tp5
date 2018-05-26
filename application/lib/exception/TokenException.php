<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/5/26
 * Time: 上午12:05
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    //HTTP 状态码
    public $code = 401;
    //错误具体信息
    public $msg = 'Token已过期或无效的Token';
    //自定义状态码
    public $errorCode = 10001;
}