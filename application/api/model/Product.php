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

    public static function getProductsByCategoryID($id)
    {
        return self::where('category_id', $id)->select();
    }
}