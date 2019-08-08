<?php

namespace app\admin\controller;

use app\admin\model\Role as RoleModel;
use gmars\rbac\Rbac;
use think\Exception;
use think\facade\Request;
use app\admin\model\UserRole;
use app\admin\model\RolePermission;

/**
 * 角色管理控制器
 * Class Role
 * @package app\admin\controller
 */
class Role extends Base
{
    //角色管理展示页面
    public function index()
    {
        $roleModel = new RoleModel();
        $list = $roleModel->paginate(20);
        $this->assign('list', $list);
        return $this->fetch();
    }

    //新增角色页面
    public function add()
    {
        $rbac = new Rbac();
        if (Request::isPost()) {
            $name = input('name');
            $role_name = new RoleModel();
            $rst = $role_name->where('name', $name)->value('name');
            if ($rst) {
                return msg(-2, '', '角色名重复');
            }
            try {
                $rbac = new Rbac();
                $data = [
                    'name' => $name,
                    'status' => 1,
                    'description' => input('description'),
                    'sort_num' => 0,
                    'parent_id' => 1
                ];
                $rbac->createRole($data, implode(',', input('nodes')));
                return msg(1, '', '添加成功');
            } catch (Exception $ex) {
                return msg(-1, '', $ex->getMessage());
            }

        } else {
            $category = $rbac->getPermissionCategory([['status', '=', 1]]);
            $node = $rbac->getPermission([['status', '=', 1]]);
            $this->assign('category', $category);
            $this->assign('node', $node);
            return $this->fetch();
        }
    }

    //修改角色
    public function edit()
    {
        $id = input('id');
        $rbac = new Rbac();
        if (Request::isPost()) {
            try {
                $rbac = new Rbac();
                $data = [
                    'id' => $id,
                    'name' => input('name'),
                    'status' => 1,
                    'description' => input('description'),
                    'sort_num' => 0,
                    'parent_id' => 1
                ];
                $rbac->createRole($data, implode(',', input('nodes')));
                return msg(1, '', '修改成功');
            } catch (Exception $ex) {
                return msg(-1, '', $ex->getMessage());
            }

        } else {
            $role_model = new RoleModel();
            //查询角色表信息
            $role = $role_model->where('id', $id)->find();
            //获取权限目录
            $category = $rbac->getPermissionCategory([['status', '=', 1]]);
            //获取权限节点
            $node = $rbac->getPermission([['status', '=', 1]]);
            //获取该角色的权限
            $up = new RolePermission();
            $rst = $up->where('role_id', $id)->column('permission_id');

            //标记已选择的节点
            foreach ($node as $k => $v) {
                $node[$k]['checked'] = 0;
                foreach ($rst as $r) {
                    if($r == $v['id']){
                        $node[$k]['checked'] = 1;
                        continue;
                    }
                }
            }
            $this->assign('category', $category);
            $this->assign('node', $node);
            $this->assign('role', $role);
            return $this->fetch();
        }
    }

    //删除角色
    public function del_role()
    {
        $rbac = new Rbac();
        $id = input('id');//角色id
        //查询该角色下是否有管理员使用
        $res = UserRole::where('role_id', $id)->find();
        if ($res) {
            return msg(-1, '', '该角色被使用无法删除');
        }
        //删除角色
        $delRole = $rbac->delRole($id);
        if ($delRole) {
            return msg(1, '', '删除成功');
        } else {
            return msg(-2, '', '删除失败');
        }


    }
}