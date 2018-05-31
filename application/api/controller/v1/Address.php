<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/29
 * Time: 17:15
 */

namespace app\api\controller\v1;


use app\api\model\User as UserModel;
use app\api\service\Token as TokenService;
use app\api\validate\AddressNew;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\SuccessException;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;
use think\Controller;

class Address extends Controller
{
    //指定前置操作方法
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createorupdateaddress'],
    ];

    /**
     * 检测权限的前置操作
     * @return bool
     * @throws ForbiddenException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function checkPrimaryScope()
    {
        $scope = TokenService::getCurrentScope();
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

    /**
     * @return SuccessException
     * @throws UserException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function createOrUpdateAddress()
    {
        $addressValidate = new AddressNew();
        $addressValidate->goCheck();

        //1.根据token来获取uid
        $uid = TokenService::getCurrentUid();
        //2.根据uid查找用户数据,判断用户是否存在,若不存在则抛出异常
        $user = UserModel::get($uid, 'address');
        if (!$user) {
            throw new UserException();
        }
        //3.获取用户从客户端提交的地址信息并且过滤
        $dataArr = $addressValidate->getDataByRule(input('post.'));
        //4.根据用户的信息是否存在,判断是更新还是新增
        $userAddress = $user->address;
        if (!$userAddress) {
            $user->address()->save($dataArr);
        } else {
            $user->address->save($dataArr);
        }

        return json(new SuccessException(), 201);

    }
}