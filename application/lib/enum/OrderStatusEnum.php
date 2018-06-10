<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/6/10
 * Time: 下午10:21
 */

namespace app\lib\enum;


class OrderStatusEnum
{
    //待支付
    CONST UNPAID = 1;
    //已支付
    CONST PAID = 2;
    //已发货
    CONST DELIVERED = 3;
    //已支付但库存不足
    CONST PAID_BUT_OUT_OF = 4;
}