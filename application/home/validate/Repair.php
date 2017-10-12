<?php
 
namespace app\home\validate;
use think\Validate; 

class Repair extends Validate{
     
    protected $rule = [ 
        ['name', 'require', '报修人不能为空'],
        ['tel', 'require', '手机号码不能为空'],
        ['address', 'require', '地址不能为空'],
        ['title', 'require', '标题不能为空'],
        ['content', 'length:0,255'],
    ];
}