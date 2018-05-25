<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

/**
 * Class Index
 * @package app\index\controller
 */
class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function ajax_upload(Request $request)
    {
        if ($request->isPost()) {

        }
    }
}
