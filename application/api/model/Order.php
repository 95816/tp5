<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/6/5
 * Time: 下午10:33
 */

namespace app\api\model;


class Order extends BaseModel
{
    //设置隐藏字段
    protected $hidden = ['user_id', 'delete_time', 'update_time'];
}