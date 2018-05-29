<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/5/29
 * Time: 下午10:43
 */

namespace app\lib\exception;


class SuccessException extends BaseException
{
    public $code = 201;
    public $msg = 'ok';
    public $errorCode = 0;
}