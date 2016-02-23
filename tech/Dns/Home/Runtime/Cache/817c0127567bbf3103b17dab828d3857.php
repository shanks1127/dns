<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>DNS用户管理</title>
<link  rel="stylesheet" type="text/css" href="__PUBLIC__/Css/base.css" />
<script type="text/javascript">
    //定义thinkphp全局变量，必须在模板中定义，Js文件中才可以使用
    var PUBLIC= "__PUBLIC__";
    var APP = "__APP__";
    var ROOT = "__ROOT__";
</script>
<script  type="text/javascript" src="__PUBLIC__/Js/jquery.js" charset="utf-8"></script>
<script  type="text/javascript" src="__PUBLIC__/Js/base.js" charset="utf-8"></script>
<script  type="text/javascript" src="__PUBLIC__/Js/user.js" charset="utf-8"></script>
</head>
<style>
#mytable{
    width:500px;
}
</style>
<body>

<!--搜索框-->
<div id="search_box"> 
    <input type="text" id="sinfo"  name="sinfo" value="<?php echo ($sinfo); ?>"/> 
    <input type="image" src="__PUBLIC__/Images/new/btn_sch_grn.png"  id="search" alt="Search" title="Search" /> 
</div>

<div id="info">
    <table  id="mytable"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
            <th>username</th>
            <th>privilege</th>
            <th>del</th>
        </tr>
        <!--遍历数据库数据-->
        <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><div align="center"><?php echo ($vo["username"]); ?></div></td>
                <td><div align="center">
                    <?php if($vo["ustat"] == 'off' ): ?><img src='__PUBLIC__/Images/key_add.png' title='授权' style='cursor:hand;' onclick='auth("<?php echo ($vo["username"]); ?>","yes")'/>
                    <?php else: ?> <img src='__PUBLIC__/Images/key_delete.png' title='取消授权' style='cursor:hand;' onclick='auth("<?php echo ($vo["username"]); ?>","no")'/><?php endif; ?>
                </div></td>
                <td><div align="center"><img src='__PUBLIC__/Images/user_delete.png' title='删除用户' style='cursor:hand;' onclick='del_user("<?php echo ($vo["username"]); ?>")'/></div></td>               
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
         <tr>
           <td colspan='5' align='center'>
            <?php echo ($page); ?>
           </td>
          </tr>
    </table>
</div>

<script>
$("#search").click( 
    function(){
        var sinfo =$('#sinfo').val();
        window.location.href='__URL__/search?sinfo='+sinfo;
        
})
//senfe("表格名称","奇数行背景","偶数行背景","鼠标经过背景","点击后背景")
senfe("mytable","#F5F5F5","#fff","#FFFFCC","#FFFF84");
</script>
</body>
</html>