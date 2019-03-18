<?php


namespace app\home\controller;
use think\Controller;
use think\Request;


/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class Notice extends Home {
	/* 通知列表 */
	public function index(){
        $notices = \think\Db::name("document")->where('category_id','43')->field(true)->select();
//        $all_menu = \think\Db::name('Menu')->column('id,title');
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('notices',$notices);
//        var_dump($notices);exit;
        return $this->fetch();
	}
    public function notice_detail($id){
        $notices = \think\Db::name("document")->where('id',$id)->field(true)->find();
        $data = ++$notices['view'];
//        var_dump($data);exit;
        \think\Db::name("document")->where('id',$id)->update(['view'=>$data]);

        $contents = \think\Db::name("document_article")->where('id',$id)->field(true)->find();
        $user = \think\Db::name("member")->where('uid',$notices['uid'])->field(true)->find();
//        $all_menu = \think\Db::name('Menu')->column('id,title');
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('notices',$notices);
        $this->assign('contents',$contents);
        $this->assign('user',$user);
//        var_dump($contents);exit;
        return $this->fetch();
    }
}
