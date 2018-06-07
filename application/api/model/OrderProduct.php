<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/6/5
 * Time: 下午11:03
 */

namespace app\api\model;


class OrderProduct extends BaseModel
{
    //设置自动维护时间戳
    protected $autoWriteTimestamp = true;
    // 关闭自动写入createTime字段
    protected $createTime = false;
}