<?php

namespace app\admin\controller;

/**
 * 管理后台首页控制器
 * Class Index
 * @package app\admin\controller
 */
class Index extends Base
{
    //后台首页
    public function index()
    {
        return $this->fetch();
    }

    public function test(){

    }
}