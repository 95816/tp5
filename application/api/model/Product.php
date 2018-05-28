<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/22
 * Time: 14:13
 */

namespace app\api\model;


class Product extends BaseModel
{
    //设置模型隐藏字段
    protected $hidden = ['delete_time', 'update_time', 'create_time', 'pivot', 'category_id'];

    //获取器 字段:main_img_url
    protected function getMainImgUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value, $data);
    }

    //获取最新商品
    public static function getMostRecent($count)
    {
        return self::limit($count)->order('create_time', 'desc')->select();
    }

    //商品分类 ID为分类ID
    public static function getProductsByCategoryID($id)
    {
        return self::where('category_id', $id)->select();
    }

    //设置商品详情图关联
    public function imgs()
    {
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    //设置商品属性关联
    public function properties()
    {
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    //查看商品详情
    public static function getProductDetail($id)
    {
//        return self::with(['properties','imgs.imgUrl'])->find($id);
        return self::with(['properties'])
            ->with(['imgs' => function ($query) {
                $query->with(['imgUrl'])->order('order', 'asc');
            }])
            ->find($id);
    }

}