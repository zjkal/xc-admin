<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\User as UserModel;
use gmars\rbac\Rbac;
use think\facade\Request;

/**
 * 后台登录控制器
 * Class Login
 * @package app\admin\controller
 */
class Login extends Controller
{
    //登录页面
    public function index()
    {

        if (Request::isAjax()) {
            $name = input('post.name');     //用户名
            $password = input('post.password');//密码
            $code = input('post.code'); //验证码

            //判断验证码
            if (!captcha_check($code)) {

                return msg(-1, '', '验证码错误');
            }

            //验证用户名密码是否正确
            $user = new UserModel();
            $rst = $user->where('username', $name)->find();
            if (!$rst) {
                return msg(-1, '', '用户不存在');
            }
            if (md5('xc_' . $password) != $rst['password']) {
                return msg(-1, '', '密码错误');
            }
            if ($rst['status'] == 0) {
                return msg(-1, '', '账户已禁用');
            }

            session('curr_admin', $rst);
            if ($rst['id'] !== 1) {
                try {
                    $rbac = new Rbac();
                    $rbac->cachePermission($rst['id']);
                }catch (Exception $ex){
                    return msg(-1, '', $ex->getMessage());
                }
            }
            return msg(1, '/admin', '登录成功');
        } else {
            return $this->fetch();
        }
    }

    //退出登录
    public function logout()
    {
        session('curr_admin', null);
        $this->redirect('/admin/login');
    }


}