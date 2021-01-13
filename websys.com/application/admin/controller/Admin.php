<?php
namespace app\admin\controller;

use think\Controller;

class Admin extends Common
{
    /**
     * 登入
     */
    public function index()
    {
        $lists = db('admin')->select();
                //var_dump($lists);
        //$this->assign('lists', $lists);
        $roles=array();
        $i=1;
        foreach ($lists as $each)
        {
            $tmp=array();
            $info=db('admin_group_access')->where('uid',$each['id'])->find();
            $role_name=db('admin_group')->where('id',$info['group_id'])->find();
            $tmp['id']=$each['id'];
            $tmp['username']=$each['username'];
            $tmp['role']=$role_name['name'];
            $tmp['group_id']=$info['group_id'];
            $tmp['i']=$i;
            $roles[$i]=$tmp;
            $i+=1;
        }
        //var_dump($roles);
        $this->assign('lists', $roles);
        return $this->fetch('index');
    }
    /*编辑*/
    public function member_edit()
    {
        $this->view->engine->layout(false);
        $username=$_GET['username'];
        $role=$_GET['role'];
        $id=$_GET['id'];
        $this->assign('username', $username);
        $this->assign('role', $role);
        $this->assign('id', $id);
        return $this->fetch();
    }
    /*处理编辑用户*/
    public function do_member_edit()
    {
        $username = $_POST['username'];
        $roleid = $_POST['role'];
        db('admin')->where('id',$_POST['id'])->update(['username'=>$username]);
        db('admin_group_access')->where('uid',$_POST['id'])->update(['group_id'=>$roleid]);
        echo("<!doctype html>
        <html>
        <body>
        <h2>修改成功</h2>
        </body>
        </html>");
    }
    
    /*添加用户*/
    public function member_add()
    {
        $this->view->engine->layout(false);
        return $this->fetch();
    }
    /*处理添加用户*/
    public function do_member_add()
    {
        $data = array();
        $data['username'] = $_POST['username'];
        $md5_salt = config('md5_salt');
        $tmp = md5(md5($_POST['password']).$md5_salt);
        $data['password'] = $tmp;
        db('admin')->insert($data);
        $info = db('admin')->where('username',$data['username'])->find();
        
        
        $newdata = array();
        $newdata['uid'] = $info['id'];
        $role = $_POST['role'];
        if($role=="系统管理员")
            $newdata['group_id'] = 1;
        else
            $newdata['group_id'] = 2;
        db('admin_group_access')->insert($newdata);
        return $this->fetch('member_add');
    }
    /*删除用户*/
    public function member_del($username,$uid)
    {
        $info=db('admin')->where('username',$username)->delete();
        $info=db('admin_group_access')->where('uid',$uid)->delete();
    }
    /*
    修改密码
    */
    public function public_edit_info()
    {
        return $this->fetch('public_edit_info');
    }
    /*
    处理修改密码
    */
    public function do_set_pw()
    {
        $md5_salt = config('md5_salt');
        $admin = model('Admin');
        $info = $admin->getInfoByUsername(session('user_name'));
        if(md5(md5($_POST['password']).$md5_salt)==$info['password'])
        {
            $admin->setUserpw(md5(md5($_POST['newpassword']).$md5_salt));
            $this->success('修改成功','index');
        }
        else
        {
            return $this->error('密码错误','public_edit_info');
        }
    }
}
