<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/24
 * Time: 14:34
 */

namespace app\api\model;

class Category extends BaseModel
{
    //设置模型隐藏字段
    protected $hidden = ['delete_time', 'update_time', 'create_time'];

    //分类关联图片
    public function img()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    //分类关联商品
    public function products()
    {
        return $this->hasMany('Product', 'category_id', 'id');
    }

}