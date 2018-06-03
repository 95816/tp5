<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/23
 * Time: 14:58
 */

namespace app\api\controller\v1;


use app\api\validate\Count;

use app\api\model\Product as ProductModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;

class Product
{
    /**
     * @url /product/recent?count=12
     * @param int $count
     * @return products of Collection
     * @throws ProductException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRecent($count = 15)
    {
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if ($products->isEmpty()) {
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    /**
     * @url /product/by_category?id=2
     * @param string $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws ProductException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllInCategory($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductsByCategoryID($id);
        if ($products->isEmpty()) {
            throw new ProductException([
                'msg' => '请求分类信息不存在,请检查分类ID!'
            ]);
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    /**
     * @url /product/11
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws ProductException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $result = ProductModel::getProductDetail($id);
        if(!$result){
            throw new ProductException();
        }
        return $result;
    }
}