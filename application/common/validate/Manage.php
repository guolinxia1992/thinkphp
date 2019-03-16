<?php
 
namespace app\common\validate;
use think\Validate;
/**
*  UC验证模型
*/
class Manage extends Validate{
    // 验证规则
    protected $rule = [
        ['name', 'require', '用户必须'],
        ['title', 'require', '标题必须'],
        ['tel', 'require', '电话必须'],
        ['address', 'require', '地址必须'],
        ['content', 'require', '内容必须'],
    ];  

}