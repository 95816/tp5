<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/14
 * Time: 14:45
 */

namespace app\api\controller\v2;


use app\api\model\Banner as BannerModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\BannerMissException;
use think\Exception;

class Banner
{

    public function getBannerId($id)
    {
        return 'this is version v2';
    }
}