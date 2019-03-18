<?php


namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Request;


/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class Repair extends Home {
	/* 通知列表 */
	public function index(){
        $services = \think\Db::name("document")->where('category_id','44')->field(true)->select();
//        $all_menu = \think\Db::name('Menu')->column('id,title');
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('services',$services);
//        var_dump($notices);exit;
        return $this->fetch();
	}
    public function add(){
        return view('repair/add');
    }
    public function addSave()
    {
        $post_data=request()->post();
//        var_dump($post_data);exit;
        $post_data=request()->post();
//      var_dump($post_data);exit();
        $validate = validate('Manage');
        if(!$validate->check($post_data)){
            return $this->error($validate->getError());
        }
        $data = Db::name('manage')->insert($post_data);
        if($data){
            session('admin_manage_list',null);
            //记录行为
            action_log('update_manage', 'Manage', $data->id, UID);
            $this->success('新增成功', ('Home/index'));
        } else {
            $this->error('新增失败');
        }
    }

}
