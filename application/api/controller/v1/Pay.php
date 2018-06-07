<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/6/7
 * Time: 下午10:35
 */

namespace app\api\controller\v1;

use app\api\service\Pay as PayService;
use app\api\validate\IDMustBePositiveInt;

class Pay extends BaseController
{
    //指定前置操作方法
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getpreorder'],
    ];

    public function getPreOrder($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        $pay->pay();
    }
}