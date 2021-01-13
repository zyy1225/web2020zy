<?php
namespace app\admin\controller;

use think\Controller;

class Menu extends Common
{
    /**
     * 登入
     */
    public function index()
    {
        $info = db('menu')->order('listorder asc')->select();
        $data=array();
        $access = db('admin_group')->select();
        $ac=array();
        foreach ($access as $a)
        {
            $rules=explode(',', $a['rules']);
            $admin=array();
            $admin['name']=$a['name'];
            $admin['rules']=$rules;
            array_push($ac,$admin);
        }
        //生成倒排表
        $menu_access=array();
        foreach ($info as $each)
        {
            $menu_access[$each['id']]="";
        }
        foreach ($ac as $a)
        {
            foreach ($a['rules'] as $rule)
            {
                $menu_access[$rule].= " ".$a['name'];
            }
        }
        $num=count($info);
        $count=array();
        // var_dump($menu_access);
        foreach ($info as $each)
        {
            $d=array();
            $d['username']=$menu_access[$each['id']];
            $d['name']=$each['name'];
            $d['parentid']=$each['parentid'];
            $d['id']=$each['id'];
            if($each['display']==1)
                $d['checked']='checked';
            else
                $d['checked']="";
            if($each['parentid']!=0)
            {
                $d['tab']="<!DOCTYPE html>
                <html>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </html>";
            }
            else 
            {
                $d['tab']="";
            }
            array_push($data,$d);
        }
        $this->assign('data', $data);
        return $this->fetch('index');
    }
    /**
     * 开启关闭
     */
    public function menu_check($id)
    {
        $info=db('menu')->where('id',$id)->find();
        if($info['display']==1)
            db('menu')->where('id',$id)->update(['display'=>2]);
        else
            db('menu')->where('id',$id)->update(['display'=>1]);
    }
    /*菜单编辑*/
    public function menu_edit()
    {
        $this->view->engine->layout(false);
        $this->assign('id', $_GET['id']);
        $this->assign('name', $_GET['name']);
        return $this->fetch();
    }
    /*处理菜单编辑*/
    public function do_menu_edit()
    {
        $id=$_POST['id'];
        $name=$_POST['name'];
        $info=db('menu')->where('id',$id)->update(['name'=>$name]);
        $role=array();
        $role[1]=$_POST['role1'];
        $role[2]=$_POST['role2'];
        $newdata=db('menu')->where('name',$name)->where('parentid',$id)->find();
        $infos=db('admin_group')->select();
        $i=0;
        foreach ($infos as $info)
        {
            $i++;
            $flag=0;
            $rules=explode(',', $info['rules']);
            foreach ($rules as $rule)
            {
                if($rule == strval($id))
                {
                    $flag=1;
                    break;
                }
            }
            if($flag==0)
            {
                if($role[$i]>0)
                {
                    $tmp=$info['rules'].','.$id;
                    db('admin_group')->where('id',$i)->update(['rules'=>$tmp]);
                }
            }
            else
            {
                if($role[$i]==0)
                {
                    $r="";
                    foreach ($rules as $rule)
                    {
                        if($rule != strval($id))
                        {
                            if($r!="")
                                $r.=",".$rule;
                            else
                                $r.=$rule;
                        }
                    }
                    db('admin_group')->where('id',$i)->update(['rules'=>$r]);
                }
            }
        }
        echo("<!doctype html>
        <html>
        <body>
        <center><h2>修改成功</h2></center>
        </body>
        </html>");
        
    }
    /*子菜单添加*/
    public function menu_add()
    {
        $this->view->engine->layout(false);
        $this->assign('id', $_GET['id']);
        return $this->fetch();
    }
    
    /*菜单添加*/
    public function newmenu_add()
    {
        $this->view->engine->layout(false);
        return $this->fetch();
    }
    /*处理菜单添加*/
    /*处理子菜单添加*/
    public function do_newmenu_add()
    {
        $name=$_POST['name'];
        $c=$_POST['c'];
        $a=$_POST['a'];
        $data=array();
        $data['name']=$name;
        $data['parentid']=0;
        $data['c']=$c;
        $data['a']=$a;
        $data['display']=2;
        $data['updatetime']=time();
        db('menu')->insert($data);
        $info=db('menu')->where('name',$name)->where('parentid',0)->find();
        db('menu')->where('id',$info['id'])->update(['listorder'=>$info['id']]);
        echo("<!doctype html>
        <html>
        <body>
        <center><h2>添加成功</h2></center>
        </body>
        </html>");
    }
    public function do_menu_add()
    {
        $id=$_POST['id'];
        $name=$_POST['name'];
        // $role=$_POST['role'];
        $info=db('menu')->where('id',$id)->find();
        $data=array();
        $data['name']=$name;
        $data['parentid']=$id;
        $data['c']=$info['c'];
        $data['a']=$info['a'];
        $data['display']=2;
        $data['listorder']=$info['listorder'];
        $data['updatetime']=time();
        $role1=$_POST['role1'];
        $role2=$_POST['role2'];
        db('menu')->insert($data);
        $newdata=db('menu')->where('name',$name)->where('parentid',$id)->find();
        if($role1==1)
        {
            $info=db('admin_group')->where('id',$role1)->find();
            $tmp=$info['rules'].','.$newdata['id'];
            db('admin_group')->where('id',$role1)->update(['rules'=>$tmp]);
        }
        if($role2==2)
        {
            $info=db('admin_group')->where('id',$role2)->find();
            $tmp=$info['rules'].','.$newdata['id'];
            db('admin_group')->where('id',$role2)->update(['rules'=>$tmp]);
        }
        echo("<!doctype html>
        <html>
        <body>
        <center><h2>添加成功</h2></center>
        </body>
        </html>");
    }
    /*删除菜单*/
    public function menu_del($id)
    {
        $info=db('menu')->where('id',$id)->delete();
        $access = db('admin_group')->select();
        foreach ($access as $a)
        {
            $rules=explode(',', $a['rules']);
            $r="";
            foreach ($rules as $rule)
            {
                if($rule!= strval($id))
                {
                    if($r!="")
                        $r.=",".$rule;
                    else
                        $r.=$rule;
                }
            }
            db('admin_group')->where('id',$a['id'])->update(['rules'=>$r]);
            
        }
    }
}
