<?php

namespace app\api\model;

class BannerItem extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time', 'id', 'banner_id', 'img_id'];

    public function img()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}
