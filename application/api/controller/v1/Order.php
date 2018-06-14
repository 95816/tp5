<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/5/31
 * Time: 下午9:54
 */

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;
use app\api\validate\PagingParameter;
use app\api\validate\PlaceOrder;
use app\api\model\Order as OrderModel;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\lib\exception\OrderException;

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
        'checkPrimaryScope' => ['only' => 'getDetail,getsummarybyuser'],
    ];

    /**
     * 获取历史订单
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\lib\exception\ParameterException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function getSummaryByUser($page = 1, $size = 15)
    {
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid, $page, $size);
        if ($pagingOrders->isEmpty()) {
            return [
                'data' => [],
                'current_page' => $pagingOrders->currentPage()
            ];
        } else {
            return [
                'data' => $pagingOrders->hidden(['snap_items', 'prepay_id', 'snap_address'])->toArray(),
                'page' => $pagingOrders->currentPage()
            ];
        }
    }

    /**
     * 订单详情
     * @param $id
     * @return OrderModel|null
     * @throws OrderException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\exception\DbException
     */
    public function getDetail($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if (!$orderDetail) {
            throw new OrderException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }

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