<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/25
 * Time: 16:56
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    //HTTP 状态码
    public $code = 400;
    //错误具体信息
    public $msg = '请求微信服务器失败,';
    //自定义状态码
    public $errorCode = 999;
}