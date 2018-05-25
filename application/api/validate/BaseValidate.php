<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/14
 * Time: 15:06
 */

namespace app\api\validate;

use app\lib\exception\ParameterException;
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
            $e = new ParameterException([
                'msg' => $this->error
            ]);
            throw $e;
        }
    }

    protected function isPositiveInteger($value, $field = '', $data = '', $rule = '')
    {
        if (is_numeric($value) && is_integer($value + 0) && (($value + 0) > 0)) {
            return true;
        } else {
            return false;
        }
    }
}