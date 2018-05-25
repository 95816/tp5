<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/22
 * Time: 18:56
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
    public $code = '400';
    public $errorCode = 30000;
    public $msg = '请求主题不存在,请检查主题ID';
}