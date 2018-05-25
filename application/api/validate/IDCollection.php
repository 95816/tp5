<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/22
 * Time: 14:36
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|checkIDs'
    ];

    protected $message = [
        'ids' => '必须传入以逗号隔开的ID数据形式'
    ];

    public function checkIDs($value = '')
    {
        $ids = explode(',', $value);
        if (empty($ids)) {
            return false;
        }
        foreach ($ids as $id) {
            if (!$this->isPositiveInteger($id)) {
                return false;
            }
        }
        return true;
    }
}