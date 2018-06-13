<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/6/12
 * Time: 下午11:41
 */

namespace app\api\service;

use app\api\model\Product;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class WxNotify extends \WxPayNotify
{
    public function NotifyProcess($data, &$msg)
    {
        if ($data['result_code'] == 'SUCCESS') {
            Db::startTrans();
            try {
                $order = OrderModel::where('order_no', $data['out_trade_no'])->find()->lock(true);
                if ($order->status == 1) {
                    $orderService = new OrderService();
                    $stockStatus = $orderService->checkOrderStack($order->id);
                    if ($stockStatus['pass']) {
                        $this->updateOrderStatus($order->id, true);
                        $this->reduceStock($stockStatus);
                    } else {
                        $this->updateOrderStatus($order->id, false);
                    }
                }
                Db::commit();
                return true;
            } catch (Exception $exception) {
                Db::rollback();
                Log::error($exception);
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * 库存量消减
     * @param $stockStatus
     * @throws Exception
     */
    private function reduceStock($stockStatus)
    {
        foreach ($stockStatus['pStatusArray'] as $singlePStatus) {
            Product::where('id', $singlePStatus['id'])->setDec('stock', $singlePStatus['count']);
        }
    }

    /**
     * 更新订单状态
     * @param $orderID
     * @param $success
     */
    private function updateOrderStatus($orderID, $success)
    {
        $success = $success ? OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id', $orderID)->update(['status' => $success]);
    }
}