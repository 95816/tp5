<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/24
 * Time: 14:33
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category
{
    /**
     * @url /category/all
     * @return CategoryModel[]|false of Collection
     * @throws CategoryException
     * @throws \think\exception\DbException
     */
    public function getAllCategories()
    {
        $categories = CategoryModel::all([], 'img');
        if ($categories->isEmpty()) {
            throw new CategoryException();
        }
        return $categories;
    }
}