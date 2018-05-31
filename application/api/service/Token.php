<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/5/25
 * Time: 下午11:33
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken()
    {
        $randStr = getRandChar(32);

        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];

        $token_salt = config('secure.token_salt');

        return md5($randStr . $timestamp . $token_salt);
    }

    /**
     * @param $key
     * @return cacheValue
     * @throws Exception
     * @throws TokenException
     */
    private static function getCurrentTokenVar($key)
    {
        $token = Request::instance()->header('token');
        $vars = cache($token);
        if (!$vars) {
            throw new TokenException();
        } else {
            if (!is_array($vars)) {
                $vars = json_decode($vars, true);
            }
            if (array_key_exists($key, $vars)) {
                return $vars[$key];
            } else {
                throw new Exception('尝试获取Token的变量不存在');
            }
        }
    }

    /**
     * @return cacheValue
     * @throws Exception
     * @throws TokenException
     */
    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    /**
     * @return cacheValue
     * @throws Exception
     * @throws TokenException
     */
    public static function getCurrentScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        return $scope;
    }

    // 用户和管理员都可以访问的权限
    public static function needPrimaryScope()
    {
        $scope = self::getCurrentScope();
        if ($scope) {
            if ($scope >= ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

    // 只有用户可以访问的权限
    public static function needExclusiveSuper()
    {
        $scope = self::getCurrentScope();
        if ($scope) {
            if ($scope == ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

}