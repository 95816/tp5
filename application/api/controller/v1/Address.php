<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/29
 * Time: 17:15
 */

namespace app\api\controller\v1;


use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;

class address
{
    public function createOrUpdateAddress()
    {
        (new AddressNew())->goCheck();

        //根据token来获取uid
        //根据uid查找用户数据,判断用户是否存在,若不存在则抛出异常
        //获取用户从客户端提交的地址信息
        //根据用户的信息是否存在,判断是更新还是新增

        $uid = TokenService::generateToken();
    }
}