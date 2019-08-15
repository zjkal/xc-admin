<?php

namespace app\admin\controller;

use app\admin\model\User as UserModel;
use app\admin\model\Role as RoleModel;
use app\admin\model\UserRole;
use gmars\rbac\Rbac;
use think\facade\Request;

/**
 * 后台用户管理控制器
 * Class User
 * @package app\admin\controller
 */
class User extends Base
{
    //管理员修改密码页面
    public function password()
    {
        if (Request::isAjax()) {
            $op = input('op');
            $np = input('np');
            $id = session('curr_admin.id');
            //查询该管理员旧密码
            $pwd = UserModel::where('id', $id)->value('password');
            if (md5('xc_' . $op) != $pwd) {
                return msg(-1, '', '旧密码不正确');
            }
            //修改密码

            $user = new UserModel();
            $rst = $user->save(['password' => md5('xc_' . $np)], ['id' => $id]);
            if($rst) {
                session('curr_admin', null);
                return msg(1, '', '更新成功');
            }else{
                return msg(-2, '', '更新失败');
            }
        } else {
            return $this->fetch();
        }
    }

    //管理员列表页面
    public function index()
    {
        $search = input('post.name');
        $where = [];
        if($search){
            $where[] = ['u.username','like',$search.'%'];
        }
        $userModel = new UserModel();
        $list = $userModel->alias('u')
            ->field('u.id,u.username,u.realname,u.status,u.create_time,u.last_login_time,r.name as rolename')
            ->leftJoin('xc_user_role ur', 'ur.user_id = u.id')
            ->leftJoin('xc_role r', 'r.id = ur.role_id')
            ->where('u.is_del', '=', 0)
            ->where($where)
            ->paginate(11);
        $this->assign('list', $list);
        return $this->fetch();
    }

    //新增管理员页面
    public function add()
    {
        if (Request::isAjax()) {
            $userModel = new UserModel();
            $username = input('username');
            $realname = input('realname');
            $role = input('role');
            $password = input('password');
            $status = input('status');

            if (!$username || !$role || !$password) {
                return msg(-1, '', '请输入完整信息');
            }
            $is_exist = $userModel->where('username', '=', $username)->value('id');
            if ($is_exist) {
                return msg(-1, '', '该用户名已被使用');
            }

            //添加会员并赋权限,开启事务
            $userModel->startTrans();
            try {
                $data = [
                    'username' => $username,
                    'realname' => $realname,
                    'password' => md5('xc_' . $password),
                    'status' => $status,
                    'create_time' => time(),
                ];
                $uid = $userModel->insertGetId($data);

                $rbac = new Rbac();
                $rbac->assignUserRole($uid, [$role]);

                $userModel->commit();
                return msg(1, '', '新增成功');
            } catch (\Exception $e) {
                // 回滚事务
                $userModel->rollback();
                return msg(-1, '', '新增失败');
            }
        } else {
            $list_role = RoleModel::field('id,name')->select();
            $this->assign('list_role', $list_role);
            return $this->fetch();
        }
    }

    //修改管理员页面
    public function edit()
    {
        //管理员ID
        $id = input('id');
        if ($id === 1) {
            return msg(-1, '', '超级管理员不能被修改');
        }

        $userModel = new UserModel();
        //查询所有角色
        $list_role = RoleModel::field('id,name')->select();

        if (Request::isAjax()) {
            $role = input('role');
            $realname = input('realname');
            $password = input('password');
            $status = input('status');

            //待修改项
            $data['update_time'] = time();
            $data['realname'] = $realname;
            $data['status'] = $status;

            //是否修改了密码(密码不为空, 并且和旧密码不相同)
            if ($password) {
                $data['password'] = md5('xc_' . $password);
            }

            //赋权限
            $rbac = new Rbac();
            $rst1 = $rbac->assignUserRole($id, [$role]);

            //修改用户信息
            $rst2 = $userModel->save($data, ['id' => $id]);

            if ($rst1 || $rst2) {
                return msg(1, '', '修改成功');
            } else {
                return msg(-1, '', '修改失败');
            }

        } else {
            //查询用户信息
            $data = $userModel->alias('u')
                ->where('u.id', '=', $id)
                ->field('u.id,u.username,u.realname,u.password,u.status,r.id as role')
                ->leftJoin('xc_user_role ur', 'ur.user_id = u.id')
                ->leftJoin('xc_role r', 'r.id = ur.role_id')
                ->find();
            $this->assign('data', $data);
            $this->assign('list_role', $list_role);
            return $this->fetch();
        }
    }


    //禁用管理员
    public function disable($id)
    {
        $rst = UserModel::where('id', '=', $id)->setField('status', 0);
        if ($rst) {
            return msg(1, '', '禁用成功');
        } else {
            return msg(-1, '', '禁用失败');
        }
    }

    //启用管理员
    public function enable($id)
    {
        $rst = UserModel::where('id', '=', $id)->setField('status', 1);
        if ($rst) {
            return msg(1, '', '启用成功');
        } else {
            return msg(-1, '', '启用失败');
        }
    }

    //软删除管理员
    public function del($id)
    {
        if ($id == 1) {
            return msg(-1, '', '超级管理员不能被删除');
        }
        $rst = UserModel::update(['is_del' => 1], ['id' => $id]);
        if ($rst) {
            return msg(1, '', '删除成功');
        } else {
            return msg(-2, '', '删除失败');
        }
    }
}