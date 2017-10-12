<?php


namespace app\home\controller;
use think\Cookie;
use think\Session;


/**
 * 前台用户后台公共控制器
 */
class Base extends Home {
    protected function _initialize(){
        /* 用户登录检测 */
        if (!is_login()){
			Cookie::set("__forward__",$_SERVER["HTTP_REFERER"]);
	        $this->error('您还没有登录，请先登录！', url('User/Login/index'));
        }
        parent::_initialize();
    }

}
