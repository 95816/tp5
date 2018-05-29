<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/5/29
 * Time: 下午11:23
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
    protected $hidden = ['id', 'delete_time', 'update_time'];
}