<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/8/3
 * Time: 22:20
 */
namespace app\index\controller;
use think\Request;
use think\Db;
class Document extends Base{
    public function index(){
        $data = Db::table("app_addoon")->select();
        //获得type类型，确定显示（0）插件or(1)模板or(2)扩展
        $type = Request::instance()->param("type");
        if($type!=NULL)
        {
          $data = Db::table("app_addoon")->where("type",$type)->select();    
        }
        //添加搜索功能
        $search = Request::instance()->post("search");
        if($search!=NULL)
        {
          $data = Db::table("app_addoon")->query("select * from app_addoon where name like '%$search%' ");
        }

        $this->assign('data',$data);
        return $this->view->fetch('index');
    }


    
}