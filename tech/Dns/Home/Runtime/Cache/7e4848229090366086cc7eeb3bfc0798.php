<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>DNS配置管理</title>
<link  rel="stylesheet" type="text/css" href="__PUBLIC__/Css/base.css" />
<script type="text/javascript">
    //定义thinkphp全局变量，必须在模板中定义，Js文件中才可以使用
    var PUBLIC= "__PUBLIC__";
    var APP = "__APP__";
    var ROOT = "__ROOT__";
</script>
<script type="text/javascript" src="__PUBLIC__/Js/raphael.js" charset="utf-8"></script>
<script  type="text/javascript" src="__PUBLIC__/Js/jquery.js" charset="utf-8"></script>
<script lang="javascript" src="__PUBLIC__/Js/popup.js"></script>
<script lang="javascript" src="__PUBLIC__/Js/base.js"></script>
<script  type="text/javascript" lang="javascript" src="__PUBLIC__/Js/dml.js"></script>
<script>
function sync(){
    if(confirm("确定同步数据到DNS配置文件?")){
        ReportDNS();
    }
}
</script>
</head>

<body>
<div id="topdiv">
    <input type="hidden"  id="role"  value="<?php echo ($role); ?>" />
    <!--搜索框-->
    <div id="search_box"> 
        <input type="text" id="sinfo"  name="sinfo" value="<?php echo ($sinfo); ?>"/> 
        <input type="image" src="__PUBLIC__/Images/new/btn_sch_grn.png"  id="search" alt="Search" title="Search" /> 
    </div>
    <div id="btndiv">
        <input type="button" name="editbox" class="btn_edit"  onclick="modify()"/>
        <input type="button" name="delbox" class="btn_del" onclick="del()"/>
        <input type="button" name="add" class="btn_add" onclick="window.location.href='__URL__/add'"/>
        <input type="button" name="report" class="btn_sync" onclick="sync()"/>
        <input type="button" name="admin" id="admin" class="btn_admin" style="display: none;"  onclick="window.location.href='__APP__/User/user'"/>
        <input type="button" name="dlog" id="dlog" class="btn_log"  onclick="window.location.href='__APP__/Log/log'"/>
    </div>
    <div id="login_tip">
    <img src="__PUBLIC__/Images/new/img_skh.png" style="float: left;margin-top:4px;"/>
    <label style='float: left;margin-top:4px;'><font face="Microsoft YaHei" color="blue">用户:<?php echo ($user); ?></font></label>
    <a style='float: left;margin:1px 0 0 10px;' href="__APP__/Login/login?action=logout"><img src="__PUBLIC__/Images/new/btn_lot.png" style="float: left;"/></a>
    </div>
</div>
<div id="info">
    <table  id="mytable" width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
            <th  width="10px"><input type="checkbox" disabled="true" /></th>
            <th>ZONE</th>
            <th>HOST</th>
            <th>TYPE</th>
            <th>IP</th>
        </tr>
        <!--遍历数据库数据-->
        <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td width="10px"><input type="checkbox" name="del" id="del"  value="<?php echo ($vo["id"]); ?>"/></td>
                <td><div align="center"><?php echo ($vo["zone"]); ?></div></td>
                <td><div align="center"><?php echo ($vo["host"]); ?></div></td>
                <td><div align="center"><?php echo ($vo["dnstype"]); ?></div></td>
                <td><div align="center"><?php echo ($vo["data"]); ?></div></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
         <tr>
           <td colspan='5' align='center'>
            <?php echo ($page); ?>
           </td>
          </tr>
    </table>
</div>
<div id="spin-form"><div id="holder" style="display: none;"></div></div>
<script>
$("#search").click( 
    function(){
        var sinfo =$('#sinfo').val();
        window.location.href='__URL__/search?sinfo='+sinfo;
        
})
if ($("#role").val()=='admin'){
    $("#admin").css('display','block');
}
//senfe("表格名称","奇数行背景","偶数行背景","鼠标经过背景","点击后背景")
senfe("mytable","#F5F5F5","#fff","#FFFFCC","#FFFF84");
</script>

</body>
</html>