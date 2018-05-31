<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/31
 * Time: 18:16
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    //HTTP 状态码
    public $code = 403;
    //错误具体信息
    public $msg = '权限不够';
    //自定义状态码
    public $errorCode = 10001;
}