<?php

namespace app\admin\controller;

use app\admin\model\PermissionCategory as PermissionCategoryModel;
use gmars\rbac\Rbac;
use think\facade\Request;
use app\admin\model\Permission;
/**
 * 权限节点管理控制器
 * Class Permission
 * @package app\admin\controller
 */
class PermissionCategory extends Base
{

    //权限节点目录列表页面
    public function index()
    {
        $permissionCategoryModel = new PermissionCategoryModel();
        $list = $permissionCategoryModel->paginate(20);
        $this->assign('list', $list);
        return $this->fetch();
    }

    //权限节点目录添加
    public function add()
    {
        if (Request::isAjax()) {
            $name = input('name');
            $icon = input('icon') ?: '';
            $sort_num = input('sort_num') ?: 0;

            if (!$name) {
                return msg(-1, '', '请输入目录名称');
            }
            //查询该名称是否存在
            $permissionCategoryModel = new PermissionCategoryModel();
            $is_exist = $permissionCategoryModel->where('name', '=', $name)->value('id');
            if ($is_exist) {
                return msg(-1, '', '该目录名称已存在');
            }
            $data = [
                'name' => $name,
                'icon' => $icon,
                'sort_num' => $sort_num,
                'status' => 1,
                'create_time' => time()
            ];
            $rst = $permissionCategoryModel->insert($data);
            if ($rst) {
                return msg(1, '', '添加成功');
            } else {
                return msg(-1, '', '添加失败');
            }

        } else {
            return $this->fetch();
        }
    }

    //权限节点目录编辑
    public function edit()
    {
        $id = input('id');
        if(Request::isAjax()){
            $name = input('name');
            $icon = input('icon') ?: '';
            $sort_num = input('sort_num') ?: 0;

            if (!$name) {
                return msg(-1, '', '请输入目录名称');
            }
            //查询该名称是否存在
            $permissionCategoryModel = new PermissionCategoryModel();
            $is_exist = $permissionCategoryModel->where('name', '=', $name)->where('id','<>',$id)->value('id');
            if ($is_exist) {
                return msg(-1, '', '该目录名称已存在');
            }
            $data = [
                'id' => $id,
                'name' => $name,
                'icon' => $icon,
                'sort_num' => $sort_num,
                'status' => 1,
            ];
            try {
                $rbac = new Rbac();
                $rbac->savePermissionCategory($data);
                return msg(1, '', '修改成功');
            } catch (Exception $ex) {
                return msg(-2, '', $ex->getMessage());
            }
        }else {
            $permissionCategoryModel = new PermissionCategoryModel();
            $list = $permissionCategoryModel->where('id', '=', $id)->find();
            $this->assign('list', $list);
            return $this->fetch();
        }
    }

    //权限节点目录删除
    public function del($id)
    {
        //判断此权限是否有角色使用
        $pe = new Permission();
        $is_pe = $pe->where('category_id',$id)->find();
        if($is_pe){
            return msg(-1, '', '该目录无法被删除');
        }

        try {
            $rbac = new Rbac();
            $rbac->delPermissionCategory($id);
            return msg(1, '', '删除成功');
        } catch (Exception $ex) {
            return msg(-2, '', $ex->getMessage());
        }
    }
}