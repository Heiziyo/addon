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
use app\index\model\MyappModel;
class Myapp extends Base{
    public function index(){
        $data = Db::table("app_addon")->select();/*dump($data);*/
        $this->assign('data',$data);

        return $this->view->fetch('index');
    }
    public function addAddon(){
            $data  = array();
            if (Request::instance()->isPost()){
                $myappmodel = new MyappModel();
                $data = Request::instance()->post();
                if (!empty(Request::instance()->file())){
                    $pre_thumb = $this->upload('pre_thumb');
                    $thumb = $this->upload('thumbs');
                }
                $data['pre_thumb'] = $pre_thumb;
                $data['thumbs'] = serialize($thumb);
                $data['dateline'] = time();
            }
            if (!empty($data)){
                $result = $myappmodel->addData($data);
                if ($result){
                    $this->success("添加成功");
                }
            }
            return $this->view->fetch("addAddon");
    }

    /*
     * 版本管理
     */
    public function versionManagement(){
        $id = Request::instance()->param("id");
        if(empty($id)){
            $this->error("不存在");
        }
        //查询当前包信息
        $info = Db::table("app_addon")->where('id',$id)->find();
        $data = Db::table("app_versionmanagement")->where('pid',$id)->select();

        $this->assign('info',$info);
        $this->assign("data",$data);
        return $this->view->fetch("versionManagement");
    }


    /*
     *
     * 创建版本
     */
    public function createVersion(){
        if (Request::instance()->isPost()){
            var_dump(Request::instance()->post());
        }else{
            $id = Request::instance()->param("id");
            if(empty($id)){
                $this->error("不存在");
            }
            //查询当前包信息
            $info = Db::table("app_addon")->where('id',$id)->find();
            //查询程序版本信息
            $version = Db::table("app_version")->select();
            $this->assign('info',$info);
            $this->assign('version',$version);
            return $this->view->fetch("createVersion");
        }
    }
    //上传
    /*
     * parm  string $image_t  图片标识
     *
     * */
    private function upload($image_t){
            $files = request()->file($image_t);
            $filepath = ROOT_PATH . 'public' . DS . 'uploads';
            if (is_array($files)){
                $thumb = "";
                foreach($files as $k=>$file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move($filepath);
                    if($info){
                        $thumb[$k] =  $info->getSaveName();
                    }else{
                        return $file->getError();
                    }
                }
                return $thumb;
            }else{
                $info = $files->move($filepath);
                if($info){
                    return $info->getSaveName();
                }else{
                    return $files->getError();
                }
            }
    }


    
}