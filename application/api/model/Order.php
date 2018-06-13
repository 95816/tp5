<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/6/5
 * Time: 下午10:33
 */

namespace app\api\model;


class Order extends BaseModel
{
    //设置隐藏字段
    protected $hidden = ['user_id', 'delete_time', 'update_time'];

    //设置自动维护时间戳
    protected $autoWriteTimestamp = true;

    /**
     * @param $uid
     * @param int $page
     * @param int $size
     * @throws \think\exception\DbException
     */
    public static function getSummaryByUser($uid, $page = 1, $size = 15)
    {
        $pagingData = self::where('user_id', $uid)
            ->order('create_time', 'desc')
            ->paginate($size, true, ['page' => $page]);
        return $pagingData;
    }
}