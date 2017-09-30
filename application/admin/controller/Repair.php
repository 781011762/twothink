<?php
namespace app\admin\controller;

class Repair extends Admin{
	/**
	 * 报修列表
	 */
	public function index(){
		$pid = input('get.pid', 0);
		/* 获取频道列表 */
		$map  = array('status' => array('gt', -1), 'pid'=>$pid);
		$list = \think\Db::name('Repair')->where($map)->order('sort asc,id asc')->select();
		$this->assign('list', $list);
		$this->assign('pid', $pid);
		$this->assign('meta_title' , '报修管理');
		return $this->fetch();
	}

	/**
	 * 添加报修
	 * @author 艺品网络  <twothink.cn>
	 */
	public function add(){
		if(request()->isPost()){
			$Repair = model('repair');
			$post_data=\think\Request::instance()->post();
			//自动验证
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
			$pid = input('pid', 0);
			//获取父导航
			if(!empty($pid)){
				$parent = \think\Db::name('Repair')->where(array('id'=>$pid))->field('title')->find();
				$this->assign('parent', $parent);
			}

			$this->assign('pid', $pid);
			$this->assign('info',null);
			$this->assign('meta_title', '新增报修');
			return $this->fetch('edit');
		}
	}

	/**
	 * 编辑报修
	 * @author 艺品网络  <twothink.cn>
	 */
	public function edit($id = 0){
		if($this->request->isPost()){
			$postdata = \think\Request::instance()->post();
			$Repair = \think\Db::name("Repair");
			$data = $Repair->update($postdata);
			if($data !== false){
				$this->success('编辑成功', url('index'));
			} else {
				$this->error('编辑失败');
			}
		} else {
			$info = array();
			/* 获取数据 */
			$info = \think\Db::name('Repair')->find($id);

			if(false === $info){
				$this->error('获取配置信息错误');
			}

			$pid = input('get.pid', 0);
			//获取父导航
			if(!empty($pid)){
				$parent = \think\Db::name('Repair')->where(array('id'=>$pid))->field('title')->find();
				$this->assign('parent', $parent);
			}

			$this->assign('pid', $pid);
			$this->assign('info', $info);
			$this->meta_title = '修改报修';
			return $this->fetch();
		}
	}

	/**
	 * 删除频道
	 * @author 艺品网络  <twothink.cn>
	 */
	public function del(){
		$id = array_unique((array)input('id/a',0));

		if ( empty($id) ) {
			$this->error('请选择要操作的数据!');
		}

		$map = array('id' => array('in', $id) );
		if(\think\Db::name('Repair')->where($map)->delete()){
			//记录行为
			action_log('update_Repair', 'Repair', $id, UID);
			$this->success('删除成功');
		} else {
			$this->error('删除失败！');
		}
	}

	/**
	 * 导航排序
	 * @author 艺品网络  <twothink.cn>
	 */
	public function sort(){
		if(request()->isGet()){
			$ids = input('ids');
			$pid = input('pid');

			//获取排序的数据
			$map = array('status'=>array('gt',-1));
			if(!empty($ids)){
				$map['id'] = array('in',$ids);
			}else{
				if($pid !== ''){
					$map['pid'] = $pid;
				}
			}
			$list = \think\Db::name('Repair')->where($map)->field('id,title')->order('sort asc,id asc')->select();

			$this->assign('list', $list);
			$this->assign('meta_title','导航排序');
			return $this->fetch();
		}elseif (request()->isPost()){
			$ids = input('ids');
			$ids = explode(',', $ids);
			foreach ($ids as $key=>$value){
				$res = \think\Db::name('Repair')->where(array('id'=>$value))->setField('sort', $key+1);
			}
			if($res !== false){
				$this->success('排序成功！');
			}else{
				$this->error('排序失败！');
			}
		}else{
			$this->error('非法请求！');
		}
	}
}