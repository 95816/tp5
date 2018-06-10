<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/6/7
 * Time: 下午10:40
 */

namespace app\api\service;

use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class Pay
{
    private $orderID;
    private $orderNO;

    /**
     * 订单ID初始化赋值
     * Pay constructor.
     * @param $orderID
     * @throws Exception
     */
    public function __construct($orderID)
    {
        if (!$orderID) {
            throw new Exception('订单号不允许为NULL');
        }
        $this->orderID = $orderID;
    }

    public function pay()
    {
        //订单号根本不存在
        //订单存在，但是和当前操作用户不是匹配的
        //订单已经支付过
        $this->checkOrderValid();
        $orderService = new OrderService();
        $status = $orderService->checkOrderStack($this->orderID);
        if (!$status['pass']) {
            return $status;
        }


    }

    private function makeWxPreOrder($totalPrice)
    {
        $openid = Token::getCurrentTokenVar('openid');
        if (!$openid) {
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('天天都来买');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url('');
    }

    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') {
            Log::record($wxOrder, 'error');
            Log::record('获取预支付订单失败', 'error');
        }
    }

    /**
     * 检查订单是否符合支付条件
     * @return mixed
     * @throws Exception
     * @throws OrderException
     * @throws TokenException
     */
    private function checkOrderValid()
    {
        $order = OrderModel::where('user_id', $this->orderID)
            ->find();
        //订单号不存在
        if (!$order) {
            throw new OrderException();
        }
        //检测是当前用户的订单
        if (!Token::isValidateOperate($order->user_id)) {
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        }
        //检查订单是否支付
        if ($order->status != OrderStatusEnum::UNPAID) {
            throw new OrderException([
                'msg' => '订单已经支付过啦',
                'errorCode' => 80003,
                'code' => 400
            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }
}