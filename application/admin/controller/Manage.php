<?php
// +----------------------------------------------------------------------
// | TwoThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.twothink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 艺品网络
// +---------------------------------------------------------------------- 

namespace app\admin\controller;

/**
 * 后台配置控制器
 * @author 艺品网络  <twothink.cn>
 */
class Manage extends Admin  {

    /**
     * 后台菜单首页
     * @return none
     */
    public function index(){
        $title      =   trim(input('title'));
        $type       =   config('config_manage_list');
        $all_manage   =   \think\Db::name('Manage')->column('id,title');
        if($title){
            $map['title'] = array('like',"%{$title}%");
            $manages       =   \think\Db::name("Manage")->where($map)->field(true)->paginate(3,false,['query'=>['title'=>$title]]);
        }else{
            $manages = \app\admin\model\Manage::paginate(3);
            Cookie('__forward__',$_SERVER['REQUEST_URI']);
        }
        $this->assign('manages',$manages);
//        var_dump($manages);exit;
        return $this->fetch();

    }

    /**
     * 新增菜单
     * @author 艺品网络  <twothink.cn>
     */
    public function add(){
//        var_dump(request());exit();
        if(request()->isPost()){
            $Manage = model('Manage');
            $post_data=request()->post();
//            var_dump($post_data);exit();
            $validate = validate('Manage');
            if(!$validate->check($post_data)){
            	return $this->error($validate->getError());
            }
            $data = $Manage->create($post_data);
            if($data){ 
                    session('admin_manage_list',null);
                    //记录行为
                    action_log('update_manage', 'Manage', $data->id, UID);
                    $this->success('新增成功', Cookie('__forward__')); 
            } else {
                $this->error($Manage->getError());
            }
        } else {
            $this->assign('info',array('pid'=>input('pid')));
            $manages = \think\Db::name('Manage')->field(true)->select();
            $manages = model('Common/Tree')->toFormatTree($manages);
            $manages = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $manages);
            $this->assign('Manage', $manages);
            $this->assign('meta_title','新增菜单');
            return $this->fetch('edit');
        }
    }

    /**
     * 编辑配置
     * @author 艺品网络  <twothink.cn>
     */
    public function edit($id = 0){
        if(request()->isPost()){
            $Manage = model('Manage');
            $post_data=$this->request->post();
            $validate = validate('Manage');
            if(!$validate->check($post_data)){
            	return $this->error($validate->getError());
            }
            $data = $Manage->update($post_data);
            if($data){ 
                    session('admin_manage_list',null);
                    //记录行为
                    action_log('update_manage', 'Manage', $data->id, UID);
                    $this->success('更新成功', Cookie('__forward__')); 
            } else {
                $this->error($Manage->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = \think\Db::name('Manage')->field(true)->find($id);
            if(false === $info){
                $this->error('获取报修信息错误');
            }
            $this->assign('info', $info);
            $this->assign('meta_title', '编辑报修菜单');
            return $this->fetch();
        }
    }

    /**
     * 删除后台菜单
     * @author 艺品网络  <twothink.cn>
     */
    public function del(){

        $id = array_unique((array)input('id/a',0));
//        var_dump($id);exit;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map = array('id' => array('in', $id) );
        if(\think\Db::name('Manage')->where($map)->delete()){
            session('admin_manage_list',null);
            //记录行为
            action_log('update_manage', 'Manage', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    public function toogleHide($id,$value = 1){
        session('admin_menu_list',null);
        $this->editRow('menu', array('hide'=>$value), array('id'=>$id));
    }

    public function toogleDev($id,$value = 1){
        session('admin_menu_list',null);
        $this->editRow('menu', array('is_dev'=>$value), array('id'=>$id));
    }

    public function importFile($tree = null, $pid=0){
        if($tree == null){
            $file = APP_PATH."Admin/Conf/Menu.php";
            $tree = require_once($file);
        }
        $menuModel = model('Menu');
        foreach ($tree as $value) {
            $add_pid = $menuModel->add(
                array(
                    'title'=>$value['title'],
                    'url'=>$value['url'],
                    'pid'=>$pid,
                    'hide'=>isset($value['hide'])? (int)$value['hide'] : 0,
                    'tip'=>isset($value['tip'])? $value['tip'] : '',
                    'group'=>$value['group'],
                )
            );
            if($value['operator']){
                $this->import($value['operator'], $add_pid);
            }
        }
    }

    public function import(){
        if(request()->isPost()){
            $tree = input('tree');
            $lists = explode(PHP_EOL, $tree);
            $menuModel = \think\Db::name('Menu');
            if($lists == array()){
                $this->error('请按格式填写批量导入的菜单，至少一个菜单');
            }else{
                $pid = input('pid');
                foreach ($lists as $key => $value) {
                    $record = explode('|', $value);
                    if(count($record) == 2){
                        $menuModel->insert(array(
                            'title'=>$record[0],
                            'url'=>$record[1],
                            'pid'=>$pid,
                            'sort'=>0,
                            'hide'=>0,
                            'tip'=>'',
                            'is_dev'=>0,
                            'group'=>'',
                        ));
                    }
                }
                session('admin_menu_list',null);
                $this->success('导入成功',url('index?pid='.$pid));
            }
        }else{
            $this->assign('meta_title','批量导入后台菜单');
            $pid = (int)input('pid');
            $this->assign('pid', $pid);
            $data = \think\Db::name('Menu')->where("id={$pid}")->field(true)->find();
            $this->assign('data', $data);
            return $this->fetch();
        }
    }

    /**
     * 菜单排序
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
            $list = \think\Db::name('Menu')->where($map)->field('id,title')->order('sort asc,id asc')->select();
 
            $this->assign('list', $list);
            $this->assign('meta_title', '菜单排序');
            return $this->fetch();
        }elseif (request()->isPost()){
            $ids = input('ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = \think\Db::name('Menu')->where(array('id'=>$value))->setField('sort', $key+1);
            }
            if($res !== false){
                session('admin_menu_list',null);
                $this->success('排序成功！');
            }else{
                $this->error('排序失败！');
            }
        }else{
            $this->error('非法请求！');
        }
    }
}