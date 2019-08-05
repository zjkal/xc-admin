<?php

namespace app\admin\controller;

use app\admin\model\Role as RoleModel;
use gmars\rbac\Rbac;
use think\Exception;
use think\facade\Request;
use app\admin\model\UserRole;
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
            try {
                $rbac = new Rbac();
                $data = [
                    'name' => input('name'),
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
        $role = new RoleModel();
        $id = input('id');//角色id
        if (Request::isAjax()) {
            $name = input('post.name'); //角色名
            $description = input('post.description'); //角色描述
            //判断修改后的角色名是否与数据库中有重复
            $duplicate = $role->where('id','<>',$id)->where('name','=',$name)->find();
            if($duplicate){
                return msg(-1,'','该用户名已被使用');
            }
            //更新数据
            $res = $role->save([ 'name' => $name,'description' => $description],['id'=>$id]);
            if($res){
                return msg(1,'','更新成功');
            }else{
                return msg('-2','','更新失败');
            }
        } else {
            $res = RoleModel::where('id', $id)->find();
            $this->assign('edit', $res);
            return $this->fetch();
        }
    }

    //删除角色
    public function del_role(){
        $rbac = new Rbac();
        $id = input('id');//角色id
        //查询该角色下是否有管理员使用
        $res = UserRole::where('role_id',$id)->find();
        if($res){
            return msg(-1,'','该角色被使用无法删除');
        }
        //删除角色
        $delRole = $rbac->delRole($id);
        if($delRole){
            return msg(1,'','删除成功');
        }else{
            return msg(-2,'','删除失败');
        }


    }
}