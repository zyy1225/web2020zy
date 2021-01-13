<?php
namespace app\admin\controller;

use think\Controller;

class Log extends Common
{
    /**
     * 登入
     */
    public function index()
    {
        $lists = db('admin_log')->select();
        $roles=array();
        $i=1;
        foreach ($lists as $each)
        {
            $tmp=array();
            $tmp['id']=$each['id'];
            $tmp['username']=$each['username'];
            $tmp['a']=$each['a'];
            $tmp['ip']=long2ip($each['ip']);
            $tmp['time']=date('Y-m-d H:i:s',$each['time']);
            $roles[$i]=$tmp;
            $i+=1;
        }
        //var_dump($roles);
        $this->assign('lists', $roles);
        return $this->fetch('index');
    }
}
