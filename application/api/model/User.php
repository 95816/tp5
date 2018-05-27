<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/25
 * Time: 10:20
 */

namespace app\api\model;


class User extends BaseModel
{
    public static function getUserByOpenID($openid)
    {
        $user = self::where('openid', $openid)->find();
        return $user;
    }
}