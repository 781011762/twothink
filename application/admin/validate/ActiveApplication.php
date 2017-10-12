<?php
 
namespace app\admin\validate;
use think\Validate; 

class ActiveApplication extends Validate{
     
    protected $rule = [ 
        ['uid', 'require', '用户id不能为空'],
        ['active_id', 'require', '活动id不能为空'],
    ];
}