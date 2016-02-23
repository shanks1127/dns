<?php
    class LoginAction extends Action{
        function login(){
            $this->display();
        }
        function IsUserExist($user){
            $m=M('user');
            $where['username']=$user;
            $num=$m->where($where)->count();
            if ($num==0){//新用户,return 0
                return 0;
            }else{
                return 1;//用户已存在
            }
        }
        function logout(){
            cookie(null); // 清空当前设定前缀的所有cookie值
            $this->display('login');
        }
        function check_login(){
            //get user pwd
            $user = $_POST['username'];
            $password = $_POST['password'];
            $ldaphost=C('LDAP_HOST');  
            $username = $user."@".C('LDAP_USER_SUFFIX');
            $logintime=date('Y-m-d H:i:s',time());
            //login
            if($_GET["action"]=="login"){
               $conn = ldap_connect($ldaphost);
                 if($conn){
                    //设置参数
                    ldap_set_option ( $conn, LDAP_OPT_PROTOCOL_VERSION, 3 );
                    ldap_set_option ( $conn, LDAP_OPT_REFERRALS, 0 ); // Binding to ldap server
                    echo "$username<br>$password";
                    $bd = ldap_bind($conn, $username, $password);
                    echo $bd;
                    if($bd and $user!='Username' and $user!='' and $password!=''){
                        //保存域帐户到user表
                        $m=M('user');
                        $data=array();
                        if($this->IsUserExist($user)==0){//new user
                            $data['username']=$user;
                            $data['role']='user';
                            $data['ustat']='off';
                            $data['logintime']=$logintime;
                            $m->data($data)->add();
                            
                        }else{
                            $data['logintime']=$logintime;
                            $m->where("username='$user'")->save($data);
                        }
                        
                    }else{
                        echo "<script>alert('用户名或密码错误')</script>";
                        $this->redirect('Login/login');
                    }
                    cookie('username',$user,C('COOKIE_TIMEOUT')); // 指定cookie保存时间
                    cookie('isLogin',1,C('COOKIE_TIMEOUT'));
                    $this->redirect('Index/index');
                 }else{
                      echo "<script>alert('LDAP 连接失败!')</script>";
                 }
            }else if ($_GET["action"]=="logout"){
                
            }
        }
        
    }
?>
