<?php
class LogAction extends Action{
    function log(){
        $index=A('Index');
        $index->IsLogin();
        $m=M('log');
        import('ORG.Util.Page');// 导入分页类
        $count      = $m->count();// 查询满足要求的总记录数
        $Page       = new Page($count,100);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $data = $m->order('logtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
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
            $where["username|content|logtime"]=array('like',"%$sinfo2%");
        }
        $m=M('log');
        import('ORG.Util.Page'); //导入分页类
        $count = $m->where($where)->count(); //查询满足要求的总记录数
        $Page = new Page($count,100); //实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); //分页显示输出
         //进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $data = $m->where($where)->order('logtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('sinfo',$sinfo);
        $this->assign('data',$data); //赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display('log');                  
    }
    
}

?>