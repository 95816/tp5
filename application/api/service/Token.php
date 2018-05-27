<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/5/25
 * Time: 下午11:33
 */

namespace app\api\service;


class Token
{
    public static function generateToken()
    {
        $randStr = getRandChar(32);

        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];

        $token_salt = config('secure.token_salt');

        return md5($randStr . $timestamp . $token_salt);
    }
}