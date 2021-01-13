<?php
namespace app\admin\controller;

use think\Controller;
use think\cache\driver\Redis;

class Login extends Controller
{
    /**
     * 登入
     */
    public function index()
    {
        $this->view->engine->layout(false);
        $redis = new Redis();
    // 	$redis->connect('127.0.0.1', 6379);
        $num=$redis->get('login_nums');
        if($num>0)
            $display="none";
        else
            $display="";
        $this->assign('display', $display);
        return $this->fetch();
    }


    /**
     * 处理登录请求
     */
    public function dologin()
    {
        $username = input('post.username');
        $password = input('post.password');
        $code = input('post.code');
        $canvas = input('post.canvas');
        if($code!=$canvas)
        {
            echo($code);
            echo($canvas);
            $this->error('验证码错误','login/index');
        }
        $redis = new Redis();
    // 	$redis->connect('127.0.0.1', 6379);
        $num=$redis->get('login_nums');
        if($num>0)
            $redis->set('login_nums', $num-1);
        // 把 'test_name' 的 值从 redis 读取出来 
        if (!$username) {
            $this->error('用户名不能为空','login/index');
        }
        if (!$password) {
            $this->error('密码不能为空','login/index');
        }

        $admin = model('Admin');
        $info = $admin->getInfoByUsername($username);

        if (!$info) {
            $this->error('用户名或密码错误','login/index');
        }
        $md5_salt = config('md5_salt');

        if (md5(md5($password).$md5_salt) != $info['password']) {
        //if (($password) != $info['password']) {
            $this->error('用户名或密码错误','login/index');
        } else {
            session('user_name', $info['username']);
            session('user_id', $info['id']);
            
            session('group_id', $admin->getUserGroups($info['id']));
            //记录登录信息
            $admin->editInfo($info['id']);
            $redis->set('login_nums', 3);
            $this->success('登入成功', 'index/index');
        }
    }

    /**
     * 登出
     */
    public function logout()
    {
        session('user_name', null);
        session('user_id', null);
        $this->success('退出成功', 'login/index');
    }

}
