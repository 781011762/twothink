<?php
// +----------------------------------------------------------------------
// | TwoThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.twothink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace app\home\controller;
use app\home\model\Document;
use OT\DataDictionary;
use think\Config;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class Index extends Home{

	//系统首页
/*    public function index(){
        $category = model('Category')->getTree();
        $document = new Document();
        $lists    = $document->lists(null);
        $this->assign('category',$category);//栏目
        $this->assign('lists',$lists);//列表
        $this->assign('page',model('Document')->page);//分页

        return $this->fetch();
    }*/
	public function index(){
		$category = model('Category')->getTree();
		$document = new Document();
		$lists    = $document->lists(null);
		$this->assign('category',$category);//栏目
		$this->assign('lists',$lists);//列表
		$this->assign('page',model('Document')->page);//分页

		return $this->fetch();
	}
	public function fuwu(){

		return $this->fetch();
	}

	//小区通知
	public function article($change){
		if (!isset($change)){
			return false;
		}
		switch ($change){
			case 'inform':
				$category = 42;
				break;
			case 'sever':
				$category = 45;
				break;
			case 'activity':
				$category = 44;
				break;
			case 'life_tips':
				$category = 47;
				break;
			default:
				return false;
		}
		$this->assign('category',$category);//栏目
		return $this->fetch();
	}
	//ajax获取数据
	public function ajaxPage($category,$page){
		$this->assign('category',$category);//栏目;
		$this->assign('page',++$page);//栏目;
		return $this->fetch('ajax_page');
	}
	//小区租售
	public function rental(){
		return $this->fetch();
	}

	//关于我们
	public function aboutUs(){
		/* 获取详细信息 */
		$info = model("Document")->detail(147);
		if(!$info){
			$this->error(model("Document")->getError());
		}
		$this->assign('info',$info);//栏目
		return $this->fetch('about_us');
	}

	//发现
	public function faxian(){
		/* 获取详细信息 */
/*		$info = model("Document")->detail(147);
		if(!$info){
			$this->error(model("Document")->getError());
		}
		$this->assign('info',$info);//栏目*/
		return $this->fetch('faxian');
	}

//发现
	public function my(){
		/* 获取详细信息 */
		/*		$info = model("Document")->detail(147);
				if(!$info){
					$this->error(model("Document")->getError());
				}
				$this->assign('info',$info);//栏目*/
		return $this->fetch('my');
	}
}
