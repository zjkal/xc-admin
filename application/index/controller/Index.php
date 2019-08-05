<?php

namespace app\index\controller;

use gmars\rbac\Rbac;
use think\facade\Request;

class Index
{
    public function index()
    {
        $rbac = new Rbac();
//        $rbac->createTable();
//        $rbac->savePermissionCategory([
//            'name' => '用户管理组',
//            'description' => '网站用户的管理',
//            'status' => 1
//        ]);
//        $rbac->createPermission([
//            'name' => '文章列表查询',
//            'description' => '文章列表查询',
//            'status' => 1,
//            'type' => 1,
//            'category_id' => 1,
//            'path' => 'article/content/list',
//        ]);
//        $rbac->createRole([
//            'name' => '内容管理员',
//            'description' => '负责网站内容管理',
//            'status' => 1
//        ], '1,2,3');
//        $rbac->assignUserRole(1, [1]);
        echo Request::baseUrl();
        dump($rbac->getPermissionCategory([['status', '=', 1]]),true,'权限分组');
        dump($rbac->getPermission([['status', '=', 1]]),true,'权限节点');
        dump($rbac->getRole([], true),true,'角色列表');
    }
}
