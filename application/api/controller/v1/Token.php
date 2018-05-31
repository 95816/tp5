<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/25
 * Time: 10:09
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\TokenValidate;

class Token
{
    /**
     * @param string $code
     * @return string|void
     * @throws \app\lib\exception\ParameterException
     * @throws \think\Exception
     */
    public function getToken($code = '')
    {
        (new TokenValidate())->goCheck();
        $ut = new UserToken($code);
        $token = $ut->get();
        $token = json_encode([
            'token' => $token
        ]);
        return $token;
    }

}