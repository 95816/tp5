<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/5/29
 * Time: 下午10:04
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = '用户不存在！';
    public $errorCode = 60000;
}