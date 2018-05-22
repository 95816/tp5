<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/16
 * Time: 9:56
 */

namespace app\lib\exception;
use think\Exception;

/**
 * 公共异常类
 * Class BaseException
 * @package app\lib\exception
 */
class BaseException extends Exception
{
    //HTTP 状态码
    public $code = 400;
    //错误具体信息
    public $msg = '参数错误';
    //自定义状态码
    public $errorCode = 10000;


    public function __construct($params=[])
    {
        if (!is_array($params)){
            throw new Exception('参数必须是数组!');
        }
        if (array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if (array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
        if (array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }
    }
}