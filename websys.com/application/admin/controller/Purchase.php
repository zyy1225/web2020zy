<?php
namespace app\admin\controller;

use think\Controller;

class Purchase extends Common
{
    public function index()
    {
        $lists = db('purchaser_info')->select();
                //var_dump($lists);
        $this->assign('lists', $lists);
        return $this->fetch('index');
    }
    
    public function goods_list()
    {
        $lists = db('goods')->select();
                //var_dump($lists);
        $this->assign('lists', $lists);
        return $this->fetch('goods_list');
    }
    /*删除买家信息*/
    public function purchase_del($username,$uid)
    {
        $info=db('purchaser_info')->where('id',$uid)->where('username',$username)->delete();
    }
    /*编辑*/
    public function purchase_edit()
    {
        $this->view->engine->layout(false);
        $this->assign('id', $_GET['id']);
        $this->assign('num', $_GET['num']);
        $this->assign('username', $_GET['username']);
        $this->assign('phonenumber', $_GET['phonenumber']);
        $this->assign('address', $_GET['address']);
        $this->assign('remark', $_GET['remark']);
        return $this->fetch();
    }
    /*处理编辑用户*/
    public function do_purchase_edit()
    {
        db('purchaser_info')->where('id',$_POST['id'])->update(['num'=>$_POST['num'],'username'=>$_POST['username'],'phonenumber'=>$_POST['phonenumber'],'address'=>$_POST['address'],'remark'=>$_POST['remark']]);
        echo("<!doctype html>
        <html>
        <body>
        <h2>修改成功</h2>
        </body>
        </html>");
    }
    /*添加用户*/
    public function purchase_add()
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
}
