<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/16
 * Time: 9:40
 */

namespace app\api\model;

class Banner extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time'];

    protected $resultSetType = 'collection';

    public function items()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

    public static function getBannerById($id)
    {
        return self::with(['items' => ['img']])->find($id);
    }

}