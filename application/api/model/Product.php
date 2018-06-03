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

    //获取最新商品

    /**
     * @param $count
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getMostRecent($count)
    {
        return self::limit($count)->order('create_time', 'desc')->select();
    }
    //商品分类 ID为分类ID

    /**
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getProductsByCategoryID($id)
    {
        return self::where('category_id', $id)->select();
    }

    //查看商品详情

    /**
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getProductDetail($id)
    {
//        return self::with(['properties','imgs.imgUrl'])->find($id);
        return self::with(['properties'])
            ->with(['imgs' => /**
             * @param $query
             */
                function ($query) {
                $query->with(['imgUrl'])->order('order', 'asc');
            }])
            ->find($id);
    }

}