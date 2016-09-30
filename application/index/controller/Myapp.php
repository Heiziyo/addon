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
class Myapp extends Base{
    public function index(){
        $data = Db::table("app_addoon")->select();/*dump($data);*/
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

    public function addAddon(){
        if (!empty(Request::instance()->post())){
            $identification = Request::instance()->post("identification");
            $name = Request::instance()->post("name");
            $thumb = $this->upload();
            $description = Request::instance()->post("description");
            $copyright = Request::instance()->post("copyright");
            $type = Request::instance()->post("type");
            $addtime =time();
            $data = [
                'identification'=>$identification,
                'name'=>$name,
                'description'=>$description,
                'thumb'=>$thumb,
                'copyright'=>$copyright,
                'type'=>$type,
                'addtime'=>$addtime
            ];
            Db::table("app_addoon")->insert($data);
        }else{
            return $this->view->fetch("addAddon");
        }
    }


    public function addTemplate(){

         if(!empty($_FILES))
         {
            $file = request()->file('previewnew');
            //便利数组
            foreach($file as $k=>$v)
            {
              $file=$v;
              $filepath = ROOT_PATH . 'public' . DS . 'uploads';
              // 移动到框架应用根目录/public/uploads/ 目录下
              $info = $file->move($filepath);
             if($info){
                $filename= $filepath ."/".$info->getFilename();

              }else{
                echo "上传失败";
              }
  
            }
            //添加数据入库
             if(!empty($_POST))
             {
              $data=$_POST;
              $data['previewnew']=$filename;
              $re=Db::table('app_addtemplate')->insert($data);
              if($re){
                echo "success";
              }else{
                echo "failure";
              }
             }          
             
         }

         //获取type类型，确定是插件，模板还是后台
         $appid = Request::instance()->param("type");
         $this->assign("appid",$appid);
         return $this->view->fetch("addTemplate");
    }
    private function upload(){
            $file = request()->file('thumb');
            $filepath = ROOT_PATH . 'public' . DS . 'uploads';
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move($filepath);
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                //echo $info->getExtension();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                return $filepath ."/".$info->getFilename();
            }else{
                // 上传失败获取错误信息
                return $file->getError();
            }
    }

    //版本管理
    public function revisionsetting(){
        //$appid=(Request::instance()->param()); 
        $appid = Request::instance()->param("id");  
        $info = Db::table("app_addoon")->where("id",$appid)->find();
        $this->assign("info",$info);
        
       return $this->view->fetch("revisionsetting");
    }

    //创建应用的第一个版本
   public function newrevision()
   {
        //$appid=(Request::instance()->param()); 
        $appid = Request::instance()->param("id"); 
        $info = Db::table("app_addoon")->where("id",$appid)->find();
       $this->assign("info",$info);

    return $this->view->fetch("newrevision");
   }

   //版本安全检查
   public function securityCheck()
   { 
        //$appid=(Request::instance()->param()); 
        $appid = Request::instance()->param("id");
        $info = Db::table("app_addoon")->where("id",$appid)->find();
        $this->assign('info',$info);
        //查询已经存在的版本
        if(!empty($appid))
        {
          $typeid=$appid; 
          //审核版本添加入库
          if(!empty($_POST))
          { 
            $_POST['typeid']=$typeid;
            $re = Db::table("app_securitycheck")->insert($_POST);
            $data=Db::table("app_securitycheck")->select();
         
          }else{
            $data=Db::table("app_securitycheck")->where("typeid",$appid)->select();
          }
        $this->assign("data",$data); 
        }
        
        //判断是否有版本，没有则去创建，有则显示当前应用下的所有版本
       $datas=Db::table("app_securitycheck")->where("typeid",$typeid)->select();
       if(empty($datas))
       {
        header("Location:/index/myapp/revisionsetting");
        exit;
       }

       $this->assign("datas",$datas);
         

       //安全检查信息入库
    return $this->view->fetch("securityCheck");   
   }

    public function versiondelete()
    {
      $appid = Request::instance()->param("id");
      //删除版本
      $re=Db::table("app_securitycheck")->where('id',$appid)->delete();
      if($re)
      {
        echo "删除成功";
      }else{
        echo "删除失败";
      }
    }

   //应用审查
   public function appCheck()
   {
      //输入要查找的应用
     if(!empty($_POST))
        {
           $newversion=$_POST['newversion'];
           $info=Db::table('securitycheck')->where('newversion',$newversion)->select();
                  
        }  
    return $this->view->fetch("appCheck");       
   }
    
}