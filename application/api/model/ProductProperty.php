<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/5/27
 * Time: 下午11:20
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    //设置模型隐藏字段
    protected $hidden = ['delete_time','update_time','id'];
}