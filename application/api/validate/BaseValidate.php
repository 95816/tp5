<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/14
 * Time: 15:06
 */

namespace app\api\validate;

use app\lib\exception\ParameterException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        $request = Request::instance();
        $param = $request->param();
        if ($this->batch()->check($param)) {
            return true;
        } else {
            if (config('app_debug')) {
                try {
                    $e = new ParameterException([
                        'msg' => $this->error
                    ]);
                } catch (Exception $e) {
                    throw new Exception('内部错误');
                }

            }
            throw $e;
        }
    }
}