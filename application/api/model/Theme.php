<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/22
 * Time: 14:13
 */

namespace app\api\model;


class Theme extends BaseModel
{
    //设置模型隐藏字段
    protected $hidden = ['update_time', 'delete_time', 'topic_img_id', 'head_img_id'];

    //主题关联Image_logo图片
    public function topicImg()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    //主题关联Image头部图片
    public function headImg()
    {
        return $this->belongsTo('Image', 'head_img_id', 'id');
    }

    //获取所有主题集合
    public static function getThemeByIDs($ids)
    {
        return self::with('topicImg,headImg')->select($ids);
    }

    //主题关联商品
    public function products()
    {
        return $this->belongsToMany('Product', 'theme_product', 'product_id', 'theme_id');
    }

    //获取单一主题及其商品
    public static function getThemeWithProducts($id)
    {
        return self::with('topic_img,head_img,products')->find($id);
    }


}