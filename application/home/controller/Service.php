<?php


namespace app\home\controller;
use think\Controller;
use think\Request;


/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class Service extends Home {
	/* 通知列表 */
	public function index(){
        $services = \think\Db::name("document")->where('category_id','44')->field(true)->select();
//        $all_menu = \think\Db::name('Menu')->column('id,title');
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('services',$services);
//        var_dump($notices);exit;
        return $this->fetch();
	}
    public function service_detail($id){
        $service = \think\Db::name("document")->where('id',$id)->field(true)->find();
        $data = ++$service['view'];
//        var_dump($data);exit;
        \think\Db::name("document")->where('id',$id)->update(['view'=>$data]);

        $contents = \think\Db::name("document_article")->where('id',$id)->field(true)->find();
        $user = \think\Db::name("member")->where('uid',$service['uid'])->field(true)->find();
//        $all_menu = \think\Db::name('Menu')->column('id,title');
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('service',$service);
        $this->assign('contents',$contents);
        $this->assign('user',$user);
//        var_dump($contents);exit;
        return $this->fetch();
    }
}
