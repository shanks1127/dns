<?php
class UserAction extends Action{
    function user(){
        $index=A('Index');
        $userinfo=$index->IsLogin();
        if($userinfo[0]['role']!='admin'){
            $this->error('对不起,您没权限访问此网页',U('Login/login'));
        }
        $m=M('user');
        import('ORG.Util.Page');// 导入分页类
        $count      = $m->count();// 查询满足要求的总记录数
        $Page       = new Page($count,100);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $data = $m->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('sinfo','');
        $this->assign('data',$data);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    
    //search 
    function search(){
        $sinfo=iconv( "UTF-8","GBK" , $_GET["sinfo"]);
        $where=array();
        $sinfo2=preg_replace('/_/','\\_',$sinfo);//mysql中"_"为特殊字符，需要转义

        if($sinfo){
            $where["username"]=array('like',"%$sinfo2%");
        }
        $m=M('user');
        import('ORG.Util.Page'); //导入分页类
        $count = $m->where($where)->count(); //查询满足要求的总记录数
        $Page = new Page($count,100); //实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); //分页显示输出
         //进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $data = $m->where($where)->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('sinfo',$sinfo);
        $this->assign('data',$data); //赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display('user');                  
    }
    //授权
    function auth(){
        $opt=$_POST["opt"];
        $user=$_POST["username"];
        $m=M('user');
        $data=array();
        $data['ustat']=$opt=='yes'?'on':'off';
        $count=$m->where("username='$user'")->save($data);
        //$x=$m->getDbError();
        if($count>0){
            $this->ajaxReturn('操作成功','Auth',1);
        }else{
            $this->ajaxReturn('操作失败','Auth',0);
        }
        
    }
    //删除用户
    function del_user(){
        $user=$_POST["username"];
        $m=M('user');
        $data=array();
        $count=$m->where("username='$user'")->delete();
        //$x=$m->getDbError();
        if($count>0){
            $this->ajaxReturn('操作成功','Auth',1);
        }else{
            $this->ajaxReturn('操作失败','Auth',0);
        }
        
    }
}

?>