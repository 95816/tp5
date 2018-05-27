<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/16
 * Time: 13:42
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 40000;
}