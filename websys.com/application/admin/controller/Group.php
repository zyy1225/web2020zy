<?php
namespace app\admin\controller;

use think\Controller;

class Group extends Common
{
    /**
     * 登入
     */
    public function index()
    {
        $lists = db('admin_group')->select();
                //var_dump($lists);
        $this->assign('lists', $lists);
        return $this->fetch('index');
    }
}
