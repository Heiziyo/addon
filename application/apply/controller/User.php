<?php
/**
 * Created by PhpStorm.
 * User: 黑子
 * Date: 2016/10/25
 * Time: 10:11
 */

namespace app\apply\controller;

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
     * 检测验证码是否正确
     */
    public function check()
    {
        $code = isset($_POST['code']) ? trim($_POST['code']) : '';
        $check = VerifyHelper::check($code);
        if($check) {
            echo '验证码正确';
        } else {
            echo '验证码错误';
        }
    }


}