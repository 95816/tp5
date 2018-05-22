<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/16
 * Time: 9:59
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的banner不存在';
    public $errorCode = 40000;

}