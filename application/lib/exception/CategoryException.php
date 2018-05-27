<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/24
 * Time: 15:06
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    //HTTP 状态码
    public $code = 404;
    //错误具体信息
    public $msg = '请求分类不存在,请检查参数';
    //自定义状态码
    public $errorCode = 50000;
}