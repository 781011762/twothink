<?php
namespace app\home\controller;

/*
 * 这个类中定义前台
 * 需要验证登录才能使用的操作
 */

use app\admin\model\ActiveApplication;
use app\admin\model\OwnerCertification;
use think\Session;

class Member extends Base{
	//业主认证
	public function ownerCertification(){
		if(request()->isPost()){
			$owner_certification = OwnerCertification::get();
			$post_data=\think\Request::instance()->post();
			//自动验证
			$validate = \app\admin\validate\OwnerCertification::make();
			if(!$validate->check($post_data)){
				return $this->error($validate->getError());
			}

			$post_data["uid"] = Session::get("user_auth")["uid"];
			$data = $owner_certification->create($post_data);
			if($data){
				$this->success('申请成功,请等待认证通过', url('index'));
				//记录行为
				action_log('update_owner_certification', 'owner_certification', $data->id, UID);
			} else {
				$this->error($owner_certification->getError());
			}
		} else {
			$this->assign('info',null);
			$this->assign('meta_title', '业主认证');
			return $this->fetch('owner_certification');
		}
	}
	//在线报修
	public function online(){
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
			$post_data["uid"] = Session::get("user_auth")["uid"];
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
			return $this->fetch('online');
		}
	}
	//报名小区活动
	public function activeApplication($id){
		$uid = Session::get("user_auth")["uid"];
		$res = model("Document")->where(["id"=>$id])->select();
		if ($res == false){
			return $this->error('活动不存在');
		}
		$res = OwnerCertification::get()->where([
			"uid"=>$uid,
			"status"=>2
		])->select();
		if ($res == false){
			return $this->error('业主认证未完成');
		}
		$data = ["uid"=>$uid,"active_id"=>$id];
		$validate = \app\admin\validate\ActiveApplication::make();
		if(!$validate->check($data)){
			return $this->error($validate->getError());
		}
		$data = ActiveApplication::create($data);
		if($data){
			//记录行为
			action_log('update_active_application', 'ActiveApplication', UID);
			$this->success('申请已提交', url('index'));
			return true;
		}
		return false;
	}

}