<?php
 
namespace app\admin\validate;
use think\Validate; 

class OwnerCertification extends Validate{
     
    protected $rule = [ 
        ['name', 'require', '姓名不能为空'],
        ['tel', 'require', '手机号码不能为空'],
        ['room_id', 'require', '房号不能为空'],
        ['relation', 'require', '与业主的关系不能为空'],
        ['id_card', 'require', '身份证号码不能为空'],
    ];
}