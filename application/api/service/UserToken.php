<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/25
 * Time: 10:30
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\WeChatException;
use think\Exception;

use app\api\model\User as UserModel;

class UserToken extends Token
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
                return $this->grantToken($wxResult);
            }
        }
    }

    private function grantToken($wxResult)
    {
        //获取到openid
        $openid = $wxResult['openid'];
        //查看数据库是否存在openid
        $user = UserModel::getUserByOpenID($openid);
        if ($user) {
            $uid = $user['id'];
        } else {
            $uid = $this->newUser($openid);
        }

        //准备值
        $cachedValue = $this->prepareCachedValue($wxResult, $uid);

        //写入缓存
        $token = $this->saveToCache($cachedValue);
        //返回令牌
        return $token;
    }

    private function saveToCache($cachedValue)
    {
        //生成令牌
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expire_in = config('setting.token_expire_time');
        if (!cache($key, $value, $expire_in)) {
            throw new TokenException([
                'msg' => '缓存服务器异常',
                'errorCode' => '10005'
            ]);
        }
        return $key;
    }

    private function prepareCachedValue($wxResult, $uid)
    {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = ScopeEnum::User;
        return $cachedValue;
    }

    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user->id;
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