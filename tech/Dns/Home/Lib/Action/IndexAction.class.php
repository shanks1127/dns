<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){
        $userinfo=$this->IsLogin();
        echo $userinfo;
        $m=M('dns_records');
        import('ORG.Util.Page');// 导入分页类
        $count      = $m->count();// 查询满足要求的总记录数
        $Page       = new Page($count,100);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $data = $m->order('host')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('role',$userinfo[0]['role']);
        $this->assign('user',$userinfo[0]['username']);
        $this->assign('sinfo','');
        $this->assign('data',$data);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); 
    }
    //是否登录
    public function IsLogin(){
        $user=cookie('username');
        if(cookie('isLogin') and $user ){
            $m=M('user');
            $where['username']=$user;
            $where['ustat']='on';
            $data=$m->where($where)->field('username,role')->select();
            if(count($data)>0){
                return $data;
            }else{
                $this->error('对不起,您没权限访问此网页',U('Login/login'));

            }
            
        }else{
            $this->error('请先登录,再访问!',U('Login/login'));
        }
        
    }
    //search 
    public function search(){
        $sinfo=iconv( "UTF-8","GBK" , $_GET["sinfo"]);
        $userinfo=$this->IsLogin();
        $where=array();
        $sinfo2=preg_replace('/_/','\\_',$sinfo);//mysql中"_"为特殊字符，需要转义

        if($sinfo){
            $where["zone|host|dnstype|data"]=array('like',"%$sinfo2%");
        }
        $m=M('dns_records');
        import('ORG.Util.Page'); //导入分页类
        $count      = $m->where($where)->count(); //查询满足要求的总记录数
        $Page       = new Page($count,100); //实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show(); //分页显示输出
         //进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $data = $m->where($where)->order('host')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('role',$userinfo[0]['role']);
        $this->assign('user',$userinfo[0]['username']);
        $this->assign('sinfo',$sinfo);
        $this->assign('data',$data); //赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display('index');                  
    }
    //日志操作
    public function addlog($txt){
        $logtime=date('Y-m-d H:i:s',time());
        $m=M('log');
        $data['username']=cookie('username');
        $data['content']=$txt;
        $data['logtime']=$logtime;
        $m->add($data);
    }
    //modify
    public function modify(){
        $updatestr=iconv( "UTF-8","GBK" , $_GET["updatestr"]);
        $updatestr="($updatestr)";
        $m=M('dns_records');
        $data=$m->where("(zone,host) in (select zone,host from dns_records where id in $updatestr)")->select();
        $this->assign('data',$data);
        $this->display('modify');
    }
    public function do_modify(){
        $updatestr=iconv( "UTF-8","GBK" , $_POST["updatestr"]);
        $updatestr=substr($updatestr,0,-1);
        $str1=explode("|",$updatestr);
        $m=M('dns_records');
        
        foreach($str1 as $item){
            $data=array();
            $colums=explode(",",$item);
            $data['zone']=$colums[1];
            $data['host']=$colums[2]; 
            $data['dnstype']=$colums[3]; 
            $data['data']=$colums[4];
            $m->where("id=$colums[0]")->save($data);
            $sql="update dns_records set host='$colums[2]',zone='$colums[1]',dnstype='$colums[3]',data='$colums[4]' where id='$colums[0]'";
            $this->addlog($sql);
             
        }
        $this->ajaxReturn('操作成功!','Modify',1);
    }
    //add
    public function add(){
        $this->display();
    }
    public function do_add(){
        $addstr=iconv( "UTF-8","GBK" , $_POST["addstr"]);
        //去掉字符串最后一个|
        $addstr=substr($addstr,0,-1);
        $str1=explode("|",$addstr);
        $m=M('dns_records');
        foreach($str1 as $item){
            $data=array();
            $colums=explode(",",$item);
            $data['zone']=$colums[0];
            $data['host']=$colums[1];
            $data['dnstype']=$colums[2];
            $data['data']=$colums[3];
			$data['level']=$colums[4];
            $m->data($data)->add();
            $sql="insert into dns_records(zone,host,dnstype,data,level) values('$colums[0]','$colums[1]','$colums[2]','$colums[3]','$colums[4]')";
            $this->addlog($sql);
        }
        $this->ajaxReturn('操作成功!','Add',1);
    }
    
    public function del_preview(){
        $delstr=iconv( "UTF-8","GBK" , $_GET["delstr"]);
        $ids=explode(',',$delstr);
        $m=M('dns_records');
        $where=array();
        $where['id']=array('in',$ids);
        $data=$m->where($where)->select();
        $this->assign('delstr',$delstr);
        $this->assign('data',$data);
        //var_dump($data);
        $this->display();
    }  
    
    public function do_del(){
        $delstr=iconv( "UTF-8","GBK" , $_POST["delstr"]);
        $ids=explode(',',$delstr);
        $m=M('dns_records');
        $where=array();
        $where['id']=array('in',$ids);
        $rs=$m->where($where)->field('zone,host,dnstype,data')->select();//方便日志查看
        foreach ($rs as $row){
            $m->where($row)->delete();
            $sql="delete from dns_records where zone='".$row['zone']."' and host='".$row['host']."' and dnstype='".$row['dnstype']."' and data='".$row['data']."'";
            $this->addlog($sql);
        }
        $this->ajaxReturn('操作成功!','Del',1);
    }
    //sync
    public function sync_dns(){
        $d=`python Public/Script/auto_dns.py`;
        $this->addlog('同步DNS配置文件');
        //$d=`ls Public/Script`;
        $this->ajaxReturn($d,'Sync',1);
    }
}
