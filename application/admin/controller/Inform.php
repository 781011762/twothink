<?php
// +----------------------------------------------------------------------
// | TwoThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.twothink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 艺品网络
// +----------------------------------------------------------------------
namespace app\admin\controller;
use app\admin\model\AuthGroup;

/**
 * 后台内容控制器
 * @author 艺品网络  <twothink.cn>
 */
class Inform extends Admin {

	/**
	 * 小区通知
	 */
	public function index(){
		/* 获取频道列表 */
		$map  = array('status' => array('gt', 0));
		$list = \think\Db::name('Inform')->where($map)->order('sort asc,id asc')->select();
		$this->assign('list', $list);
		$this->assign('meta_title' , '小区通知');
		return $this->fetch();
	}

	/**
	 * 添加通知
	 * @author 艺品网络  <twothink.cn>
	 */
	public function add(){
		if(request()->isPost()){
			$inform = model('inform');
			$post_data=\think\Request::instance()->post();
			//自动验证
			var_dump($post_data);exit();
			$validate = validate('repair');
			if(!$validate->check($post_data)){
				return $this->error($validate->getError());
			}
			//>>>>增加货号
			$sn = 'BX_'.strtoupper(uniqid());
			$post_data['sn'] = $sn;
			//<<<<<<<<
			$data = $Repair->create($post_data);
			if($data){
				$this->success('新增成功', url('index'));
				//记录行为
				action_log('update_Repair', 'Repair', $data->id, UID);
			} else {
				$this->error($Repair->getError());
			}
		} else {

			$this->assign('info',null);
			$this->assign('meta_title', '通知');
			return $this->fetch('edit');
		}
	}

	/**
	 * 文档编辑页面初始化
	 * @author 艺品网络  <twothink.cn>
	 */
	public function edit(){
		//获取左边菜单
		$this->getMenu();

		$id     =   input('id','');
		if(empty($id)){
			$this->error('参数不能为空！');
		}
		$model_id = input('param.model',0);
		$cate_id =   input('param.cate_id',0);
		//获取模型信息
		if(empty($model_id) && !empty($cate_id)){
			$model_id =   \think\Db::name('Category')->getFieldById($cate_id,'model');   // 当前分类支持的文档模型
		}
		$model = \think\Db::name('Model')->getById($model_id);

		//继承模型先实例化基础模型数据
		if($model['extend'] != 0){
			$model_id = $model['extend'];
		}

		//获取基础模型数据
		if(!$jc_data       = logic($model_id)->detail($id)){
			$this->error('数据不存在');
		}
		//获取扩展模型数据
		if($jc_data['model_id']){
			$kz_data  = logic($jc_data['model_id'])->detail($id);
			$kz_data || $this->error('扩展数据不存在');
		}
		if($kz_data){
			$data = array_merge($jc_data, $kz_data);
		}else{
			$data = $jc_data;
		}
		if($data['pid']){
			// 获取上级文档
			$article        =   \think\Db::name(get_table_name($model_id))->field('id,,titletype')->find($data['pid']);
			$this->assign('article',$article);
		}

		// 获取当前的模型信息
		$model    =   get_document_model($data['model_id']);

		$this->assign('data', $data);
		$this->assign('model_id', $data['model_id']);
		$this->assign('model',      $model);

		//获取表单字段排序
		$fields = get_model_attribute($model['id']);
		$this->assign('fields',     $fields);
		//获取当前分类的文档类型
		$this->assign('type_list', get_type_bycate($data['category_id']));

		$this->assign('meta_title', '编辑文档');
		return $this->fetch();
	}
}
