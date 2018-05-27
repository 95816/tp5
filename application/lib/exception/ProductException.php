<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/24
 * Time: 11:07
 */

namespace app\lib\exception;


class ProductException extends BaseException
{
    //HTTP 状态码
    public $code = 404;
    //错误具体信息
    public $msg = '请求商品不存在,请检查参数';
    //自定义状态码
    public $errorCode = 20000;
}