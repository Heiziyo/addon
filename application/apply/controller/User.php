<?php
/**
 * Created by PhpStorm.
 * User: 黑子
 * Date: 2016/10/25
 * Time: 10:11
 */

namespace app\apply\controller;

use app\apply\model\UserModel;
use app\common\controller\Base;
use app\common\helper\VerifyHelper;

class User extends Base
{


    public function register()
    {
        return $this->view->fetch();
    }

    public function login()
    {
        return $this->view->fetch();
    }

    public function forget()
    {
        return $this->view->fetch();
    }

    /**
     * 显示验证码图片
     */
    public function verify()
    {
        VerifyHelper::verify();
    }

    /**
     * 注册页逻辑
     */
    public function doRegister()
    {
        $code = isset($_POST['code']) ? trim($_POST['code']) : '';
        $post['fname'] = isset($_POST['name']) ? stripslashes(trim($_POST['name'])) : '';
        $post['femail'] = isset($_POST['email']) ? trim($_POST['email']) : '';
        $post['fpassword'] = isset($_POST['password']) ? md5(trim($_POST['password'])) : '';
        //注册时间
        $post['fregtime'] = time();
        //创建激活码
        $post['ftoken'] = md5($post['fname'].$post['fpassword'].$post['fregtime']);
        //激活码过期时间限制
        $post['ftokenexptime'] = time() + 3600*24;
        $user_model = new UserModel();
        //验证用户名
        $result = $user_model->userName($post['fname']);
        if ($result) {
            $this->error('该用户名已被注册，换一个吧~');
        }
        //验证验证码
        $check = VerifyHelper::check($code);
        if(!$check) {
            $this->error('验证码错误');
        }
        //验证验证码

    }


}