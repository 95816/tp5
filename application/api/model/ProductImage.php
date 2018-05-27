<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/5/27
 * Time: 下午10:57
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    //设置模型隐藏字段
    protected $hidden = ['img_id','product_id','delete_time'];
    public function imgUrl()
    {
        return $this->belongsTo("Image",'img_id','id');
    }
}