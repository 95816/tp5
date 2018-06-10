<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/6/10
 * Time: 下午1:48
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\ProductException;
use think\Db;
use think\Exception;

class OrderBak
{
    //用户提交过来的商品信息
    protected $oProducts;
    //根据用户提交的商品信息在数据库中的真实信息
    protected $products;
    //当前用户ID
    protected $uid;


    public function place($uid, $oProducts)
    {
        $this->oProducts = $oProducts;
        $this->uid = $uid;
        $this->products = $this->getProductsByOrder($oProducts);
        //检测订单状态
        $status = $this->getOrderStatus();
        //status数组新增order_id，成功返回的是创建订单的ID，失败了也给这个字段。
        if (!$status['pass']) {
            $status['order_id'] = -1;
            return $status;
        }

        // 生成订单快照
        $orderSnap = $this->snapOrder($status);
        // 检测库存通过，开始创建订单
        $status = $this->createOrder($orderSnap);
        $status['pass'] = true;
        return $status;

    }

    /**
     * 创建订单
     * @param $snap
     * @return array
     * @throws \Exception
     */
    private function createOrder($snap)
    {
        Db::startTrans();
        try {
            $orderNO = $this->makeOrderNO();
            $order = new \app\api\model\Order();
            $order->order_no = $this->makeOrderNO();
            $order->user_id = $this->uid;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();
            $order_id = $order->id;
            foreach ($this->oProducts as &$oProduct) {
                $oProduct['order_id'] = $order_id;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);
            Db::commit();
            return [
                'order_no' => $orderNO,
                'order_id' => $order_id,
                'create_time' => $order->create_time,
            ];
        } catch (Exception $exception) {
            Db::rollback();
            throw $exception;
        }

    }

    private function makeOrderNO()
    {
        $yCode = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        $orderSn = $yCode[intval(date('Y') - 2018)] . strtoupper(dechex(date('m'))) .
            date('d') . substr(time(), -5) . substr(microtime(), 2, 5) .
            sprintf('%02d', rand(0, 99));
        return $orderSn;
    }

    /**
     * 生成订单快照
     * @param $status
     * @return array
     * @throws OrderException
     * @throws \think\exception\DbException
     */
    private function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => null,
            'snapName' => '',
            'snapImg' => '',
        ];
        $snap['orderPrice'] = $status['totalPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        $snap['pStatus'] = $status['pStatusArray'];
        if (count($this->products) > 1) {
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    /**
     * 获取用户地址
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws OrderException
     * @throws \think\exception\DbException
     */
    private function getUserAddress()
    {
        $address = UserAddress::where('user_id', $this->uid)->find();
        if (!$address) {
            throw new OrderException([
                'msg' => '用户地址不存在，下单失败',
                'errorCode' => 60001
            ]);
        }
        return $address;
    }

    /**
     * @param $orderID
     * @return array
     * @throws ProductException
     * @throws \think\exception\DbException
     */
    public function checkOrderStack($orderID)
    {
        $this->oProducts = OrderProduct::where('order_id', $orderID)->select();
        $this->products = $this->getProductsByOrder($this->oProducts);
        $status = $this->getOrderStatus();
        return $status;
    }

    /**
     * 检测订单状态
     * @return array
     * @throws ProductException
     */
    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'totalPrice' => 0,
            'totalCount' => 0,
            'pStatusArray' => []
        ];

        foreach ($this->oProducts as $oProduct) {
            $pStatus = $this->getProductStatus($oProduct['product_id'], $oProduct['count'], $this->products);
            if (!$pStatus['hasStock']) {
                $status['pass'] = false;
            }
            $status['totalCount'] += $pStatus['count'];
            $status['totalPrice'] += $pStatus['totalPrice'];
            array_push($status['pStatusArray'], $pStatus);
        }

        return $status;
    }

    /**
     * 检测商品库存
     * @param $oPId
     * @param $oCount
     * @param $products
     * @return array
     * @throws ProductException
     */
    private function getProductStatus($oPId, $oCount, $products)
    {
        $pIndex = -1;
        $pStatus = [
            'id' => null,
            'name' => '',
            'count' => 0,
            'hasStock' => false,
            'totalPrice' => 0
        ];
        for ($i = 0; $i < count($products); $i++) {
            if ($oPId == $products[$i]['id']) {
                $pIndex = $i;
            }
        }
        if ($pIndex == -1) {
            throw new ProductException([
                'msg' => 'ID为' . $oPId . '.商品不存在'
            ]);
        }
        $pStatus['id'] = $oPId;
        $pStatus['name'] = $products[$pIndex]['name'];
        $pStatus['count'] = $oCount;
        if ($oCount >= $products[$pIndex]['stock']) {
            $pStatus['hasStock'] = true;
        }
        $pStatus['totalPrice'] = $products[$pIndex]['price'] * $oCount;

        return $pStatus;
    }

    /**
     * @param $oProducts
     * @return mixed
     * @throws \think\exception\DbException
     */
    private function getProductsByOrder($oProducts)
    {
        $pIDs = [];
        foreach ($oProducts as $oProduct) {
            array_push($pIDs, $oProduct['product_id']);
        }

        $products = Product::all($pIDs)
            ->visible(['id', 'name', 'price', 'stock', 'main_img_url'])
            ->toArray();
        return $products;

    }

}