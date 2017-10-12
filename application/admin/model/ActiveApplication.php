<?php
// +----------------------------------------------------------------------
// | TwoThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.twothink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 艺品网络 
// +----------------------------------------------------------------------

namespace app\admin\model;
use think\Model;

/**
 * 报修模型
 */

class ActiveApplication extends Model {
    protected $insert = ['status'=>1];
	protected $autoWriteTimestamp = true;       // int 型    自动更新时间戳

	public function items() { //建立一对多关联
		return $this->hasOne('app\home\model\Document', 'id', 'active_id'); //关联的模型，外键，当前模型的主键
	}

	public static function getActiveByID($id)
	{
		$banner = self::with('items')->find($id); // 通过 with 使用关联模型，参数为关联关系的方法名
		return $banner;
	}
}
