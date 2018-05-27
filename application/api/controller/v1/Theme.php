<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/22
 * Time: 14:15
 */

namespace app\api\controller\v1;


use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ThemeException;
use think\Exception;

class Theme
{
    /**
     * @url /theme?ids=1,2,3...
     * @param string $ids
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \app\lib\exception\ParameterException
     * @throws Exception
     * @throws ThemeException
     */
    public function getSimpleList($ids = '')
    {
        (new IDCollection())->goCheck();
        $ids = explode(',', $ids);
        $result = ThemeModel::getThemeByIDs($ids);
        if ($result->isEmpty()) {
            throw new ThemeException();
        }
        return $result;
    }


    /**
     * @url /theme/1
     * @param $id
     * @return theme of Products
     * @throws ThemeException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\Exception
     */
    public function getComplexOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $theme = ThemeModel::getThemeWithProducts($id);
        if (!$theme) {
            throw new ThemeException();
        }
        return $theme;
    }
}