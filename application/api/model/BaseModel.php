<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/22
 * Time: 11:39
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{
    public function getImgUrl($value, $data)
    {
        $finalUrl = $value;
        if ($data['from'] == 1) {
            $finalUrl = config('setting.img_prefix') . $value;
        }
        return $finalUrl;
    }
}