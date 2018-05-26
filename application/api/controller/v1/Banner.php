<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/14
 * Time: 14:45
 */

namespace app\api\controller\v1;


use app\api\model\Banner as BannerModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\BannerMissException;
use think\Exception;

class Banner
{
    /**
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws BannerMissException
     * @throws Exception
     * @throws \app\lib\exception\ParameterException
     */
    public function getBannerId($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $banner = BannerModel::getBannerById($id);
        if (!$banner) {
            throw new BannerMissException([
                'msg' => '未能正确获取到数据'
            ]);
        }
        return $banner;


    }
}