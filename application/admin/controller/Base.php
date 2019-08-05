<?php

namespace app\admin\controller;

use think\Controller;
use gmars\rbac\Rbac;
use think\facade\Request;
use think\facade\Session;
use app\admin\model\User as UserModel;
use app\admin\model\Permission as PermissionModel;
use app\admin\model\PermissionCategory as PermissionCategoryModel;

/**
 * RBAC父类(除登录之外的控制器需要继承该控制器)
 * Class Base
 * @package app\admin\controller
 */
class Base extends Controller
{
    public function initialize()
    {
        parent::initialize();
        if (!Session::has('curr_admin')) {
            if (Request::isAjax()) {
                return msg(-1, '/admin/login/index', '登录超时');
            } else {
                $this->redirect('/admin/login/index');
            }
        }
        $rbac = new Rbac();
        $url = Request::baseUrl();
        //非超级管理员(ID为1) && 该URL不在权限排除列表 && 没有该URL的权限
        if (Session::get('curr_admin.id') != 1 && !$this->uncheck($url) && !$rbac->can($url)) {
            if (Request::isAjax()) {
                echo msg(-1, '', '没有权限');
            } else {
                $this->error('没有权限');
            }
            exit('没有权限');
        }

        $this->make_menu();
    }


    /**
     * 生成菜单
     */
    private function make_menu()
    {
        $uid = Session::get('curr_admin.id');
        if ($uid) {
            //如果为超级管理员
            if ($uid === 1) {
                $node = PermissionModel::field('name,category_id,path')->where('is_menu', '=', 1)->order('sort_num', 'desc')->select()->toArray();
            } else {
                $userModel = new UserModel();
                $node = $userModel->alias('u')
                    ->field('p.name,p.category_id,p.path')
                    ->leftJoin('xc_user_role ur', 'ur.user_id = u.id')
                    ->leftJoin('xc_role r', 'r.id = ur.role_id')
                    ->leftJoin('xc_role_permission rp', 'rp.role_id = r.id')
                    ->leftJoin('xc_permission p', 'p.id = rp.permission_id')
                    ->where('u.id', '=', $uid)
                    ->where('p.is_menu', '=', 1)
                    ->order('p.sort_num', 'desc')
                    ->select()->toArray();
            }

            $list_category = PermissionCategoryModel::field('id,name,icon')->order('sort_num', 'desc')->select()->toArray();
            //目录
            $category = [];

            //去除空目录
            foreach ($list_category as $c) {
                foreach ($node as $n) {
                    //为目录匹配到节点,并且临时目录中不存在该目录
                    if ($n['category_id'] == $c['id'] && !in_array($c['id'], $category)) {
                        $category[$c['id']] = $c;
                        continue;
                    }
                }
            }

            $this->assign('menu_category', $category);
            $this->assign('menu_node', $node);
        }
    }


    /**
     * 排除权限检查的URL
     * @param string $url URL
     * @return bool
     */
    private function uncheck($url)
    {
        $urls = [
            '/admin',
            '/admin/index',
            '/admin/index/index',
            '/admin/login/logout'
        ];

        return in_array($url, $urls);
    }

}