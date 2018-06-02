<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/6/2
 * Time: 下午11:48
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class PlaceOrder extends BaseValidate
{
    protected $rule = [
        //下单接受过来的参数是一个数组
        'products' => 'checkProducts'
    ];

    protected $singleRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger'
    ];

    /**
     * @param $value
     * @throws ParameterException
     */
    protected function checkProducts($value)
    {
        if (!is_array($value)) {
            throw new ParameterException([
                'msg' => '商品参数格式不正确！'
            ]);
        }
        if (empty($value)) {
            throw new ParameterException([
                'msg' => '商品列表不能为空！'
            ]);
        }
        foreach ($value as $item) {
            $this->checkProduct($item);
        }
    }

    /**
     * @param $value
     * @throws ParameterException
     */
    protected function checkProduct($value)
    {
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if (!$result) {
            throw new ParameterException([
                'msg' => '商品列表参数错误！'
            ]);
        }
    }
}