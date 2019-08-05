<?php

namespace app\admin\controller;

use gmars\rbac\Rbac;
use think\Exception;
use think\facade\Request;
use app\admin\model\Permission as PermissionModel;
use app\admin\model\PermissionCategory as PermissionCategoryModel;

/**
 * 权限节点管理控制器
 * Class Permission
 * @package app\admin\controller
 */
class Permission extends Base
{
    //权限节点列表页面
    public function index()
    {

        $permissionModel = new PermissionModel();
        $list = $permissionModel->alias('p')
            ->field('p.id,p.name,p.path,p.sort_num,p.is_menu,pc.name as category')
            ->leftJoin('xc_permission_category pc', 'pc.id=p.category_id')
            ->paginate(20);
        $this->assign('list', $list);
        return $this->fetch();
    }

    //新增权限节点页面
    public function add()
    {
        if (Request::isAjax()) {
            $permissionModel = new PermissionModel();
            $category_id = input('category_id');
            $name = input('name');
            $path = input('path');
            $is_menu = input('is_menu');
            $sort_num = input('sort_num') ?: 0;

            if (!$name || !$path) {
                return msg(-1, '', '请输入完整信息');
            }
            $is_exist = $permissionModel->where('name', '=', $name)->whereOr('path', '=', $path)->value('id');
            if ($is_exist) {
                return msg(-1, '', '该节点已存在');
            }

            $data = [
                'category_id' => $category_id,
                'name' => $name,
                'type' => 1,
                'path' => $path,
                'status' => 1,
                'create_time' => time(),
                'sort_num' => $sort_num,
                'is_menu' => $is_menu
            ];

            try {
                $rbac = new Rbac();
                $rbac->createPermission($data);
                return msg(1, '', '新增成功');
            } catch (Exception $ex) {
                return msg(-1, '', $ex->getMessage());
            }

        } else {
            $list_permission_category = PermissionCategoryModel::field('id,name')->select();
            $this->assign('list_permission_category', $list_permission_category);
            return $this->fetch();
        }
    }
}