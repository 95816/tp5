<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/6/3
 * Time: 下午9:37
 */

namespace app\api\service;


use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;

class Order
{
    // 客服端传过来的的商品列表信息
    protected $oProducts;

    // 数据库查询真的商品信息
    protected $products;

    // 当前用户的uid
    protected $uid;

    /**
     * 下单业务
     * @param $uid
     * @param $oProducts
     * @return array
     * @throws \think\exception\DbException
     * @throws OrderException
     */
    public function place($uid, $oProducts)
    {
        //oProducts 和 Products做对比
        // products 要从数据库取出来
        $this->uid = $uid;
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $status = $this->getOrderStatus();
        //status数组新增order_id，成功返回的是创建订单的ID，失败了也给这个字段。
        if (!$status['pass']) {
            $status['order_id'] = -1;
            return $status;
        }

        //检测库存通过，开始创建订单
        $orderSnap = $this->snapOrder($status);
    }

    private function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => null,
            'snapName' => '',
            'snapImg' => ''

        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['main_img_url'] = $this->products[0]['main_img_url'];

        if (count($this->products) > 1) {
            $snap['snapName'] .= '等';
        }
    }

    /**
     * 获取当前用户下单地址
     * @return array
     * @throws UserException
     * @throws \think\exception\DbException
     */
    private function getUserAddress()
    {
        $address = UserAddress::where('user_id', $this->uid)->find();
        if (!$address) {
            throw new UserException([
                'msg' => '用户地址不存在，下单失败'
            ]);
        }
        return $address->toArray();
    }

    /**
     * 获取订单状态(检测库存量)
     * @throws OrderException
     */
    private function getOrderStatus()
    {
        $status = [
            /*
             * pass 检测订单库存是否通过
             * orderPrice 整个订单总额
             * pStatusArray 整个订单中每个商品的详细信息
             */
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatusArray' => []
        ];

        foreach ($this->oProducts as $oProduct) {
            $pStatus = $this->getProductStatus($oProduct['product_id'], $oProduct['count'], $this->products);
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($status['pStatusArray'], $pStatus);
            if (!$pStatus['hasStock']) {
                $status['pass'] = false;
            }
        }
        return $status;
    }

    /**
     * 获取单个商品状态(检测商品库存)
     * @param $oPID
     * @param $oCount
     * @param $products
     * @return array
     * @throws OrderException
     */
    private function getProductStatus($oPID, $oCount, $products)
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
            if ($oPID == $products[$i]['id']) {
                $pIndex = $i;
            }
        }

        if ($pIndex == -1) {
            throw new OrderException([
                'msg' => 'ID为' . $oPID . '的商品不存在，创建订单失败'
            ]);
        }
        $pStatus['id'] = $products[$pIndex]['id'];
        $pStatus['name'] = $products[$pIndex]['name'];
        $pStatus['count'] = $oCount;
        $pStatus['totalPrice'] = $oCount * $products[$pIndex]['price'];
        if ($products[$pIndex]['stock'] >= $oCount) {
            $pStatus['hasStock'] = true;
        }
        return $pStatus;
    }

    /**
     * 根据客服端的订单商品获取数据库中商品的真实数据信息
     * @throws \think\exception\DbException
     */
    private function getProductsByOrder($oProducts)
    {
        $oPIDs = [];
        foreach ($oProducts as $item) {
            array_push($oPIDs, $item['product_id']);
        }

        $products = Product::all($oPIDs)
            ->hidden(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->toArray();
        return $products;
    }

}