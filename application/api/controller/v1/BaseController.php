<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/5/31
 * Time: 下午11:15
 */

namespace app\api\controller\v1;


use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller
{
    /**
     * 检测权限的前置操作.用户以上的都可以调用
     * @return void
     * @throws \app\lib\exception\TokenException
     * @throws \app\lib\exception\ForbiddenException
     */
    protected function checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }

    /**
     * 检测权限的前置操作。管理员不可以使用该接口
     * @return void
     * @throws \app\lib\exception\TokenException
     * @throws \app\lib\exception\ForbiddenException
     */
    protected function checkExclusiveScope()
    {
        TokenService::needExclusiveSuper();
    }
}