<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/6/7
 * Time: 下午10:35
 */

namespace app\api\controller\v1;

use app\api\service\Pay as PayService;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;

class Pay extends BaseController
{
    //指定前置操作方法
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getpreorder'],
    ];

    //获取微信预订单
    public function getPreOrder($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();
    }

    //微信支付回调地址
    public function receiveNotify()
    {
        $notify = new WxNotify();
        $notify->Handle();
    }
}