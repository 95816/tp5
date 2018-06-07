<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/6/7
 * Time: 下午10:40
 */

namespace app\api\service;

use app\api\service\Order as OrderService;

use think\Exception;

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
        $orderService = new OrderService();
        $status = $orderService->checkOrderStack($this->orderID);
    }
}