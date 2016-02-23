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
<script  type="text/javascript" src="__PUBLIC__/Js/jquery.js" charset="utf-8"></script>
</head>

<body>

<style>
a:hover{
    text-decoration: none;
}
</style>
<div id="filter">
    <input type="text"  id="sinfo"  value="<?php echo ($sinfo); ?>" />
    <input id="search" type="button" class="search"  />&nbsp;&nbsp;&nbsp;&nbsp;
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
<script>
$("#search").click( 
    function(){
        var sinfo =$('#sinfo').val();
        window.location.href='__URL__/search?sinfo='+sinfo;
        
})
</script>

</body>
</html>