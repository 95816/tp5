<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/25
 * Time: 10:30
 */

namespace app\api\service;


use app\lib\exception\WeChatException;
use think\Exception;

class UserToken
{
    protected $code = '';
    protected $app_id = '';
    protected $app_secret = '';
    protected $wxLoginUrl = '';

    public function __construct($code)
    {
        $this->code = $code;
        $this->app_id = config('wx.app_id');
        $this->app_secret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.loginUrl'), $this->app_id, $this->app_secret, $this->code);
    }

    public function get()
    {
        $result = https_request($this->wxLoginUrl);
        //把json格式的转为true返回数组
        $wxResult = json_decode($result, true);
        if (empty($wxResult)) {
            throw new Exception('获取session_key及openID时微信服务器出现异常');
        } else {
            //如果有errcode就证明有错误信息返回
            if (array_key_exists('errcode', $wxResult)) {
                $this->processLoginError($wxResult);
            } else {
                $this->grantToken($wxResult);
            }
        }
    }
    private function grantToken($wxResult)
    {
        //获取到openid
        //查看数据库是否存在openid
        //生成令牌
        //准备值
        //写入缓存
    }

    /**
     * @param $wxResult
     * @throws WeChatException
     */
    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'errorCode' => $wxResult['errcode'],
            'msg' => $wxResult['errmsg']
        ]);
    }


}