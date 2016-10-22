<?php
/**
 * Created by PhpStorm.
 * User: 黑子
 * Date: 2016/10/20
 * Time: 16:25
 */
namespace app\apply\controller;
use app\common\controller\Base;
class Index extends Base{


    public function index(){



        return $this->view->fetch('index');
    }
}