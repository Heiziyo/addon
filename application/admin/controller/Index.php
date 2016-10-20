<?php
namespace app\admin\controller;
use app\common\controller\Base;
class Index  extends Base
{
    public function index()
    {

        echo 111;

        return $this->view->fetch('index');

    }
}
