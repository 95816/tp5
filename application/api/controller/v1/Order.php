<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/5/31
 * Time: 下午9:54
 */

namespace app\api\controller\v1;

use app\api\validate\PlaceOrder;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;

class Order extends BaseController
{
    // 用户在选择商品后，向API提交包含他所选择商品的相关信息
    // API接受到信息后。需要检查订单商品的库存量
    // 有库存则将订单信息写入数据库=下单成功，返回客服端信息，告诉客服端可以支付了
    // 调用预订单支付接口，进行支付
    // 获取prepay_id支付前再次检查库存量
    // 服务器调用支付接口进行微信支付
    // 小程序此时可以根据返回结果拉起微信支付
    // 微信返回一个支付结果（异步）
    // 成功扣除库存量前再次检测，因为有一种情况，在支付结果返回前商品卖完了
    // 成功：进行库存量扣除

    //指定前置操作方法
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeorder'],
    ];

    /**
     * @return string
     * @throws \app\lib\exception\ParameterException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function placeOrder()
    {
        (new PlaceOrder())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid, $products);
        /** @var TYPE_NAME $status */
        return $status;
    }


}