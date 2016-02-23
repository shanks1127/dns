<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>dns record 删除预览界面</title>
<link  rel="stylesheet" type="text/css" href="__PUBLIC__/Css/base.css"/>
<script type="text/javascript">
    var PUBLIC= "__PUBLIC__";
    var APP = "__APP__";
    var ROOT = "__ROOT__";
</script>
<script  type="text/javascript" src="__PUBLIC__/Js/jquery.js" charset="utf-8"></script>
<script  type="text/javascript" src="__PUBLIC__/Js/dml.js" charset="utf-8"></script>
<style>
#preview{
    width: 500px;
    float:left;
    margin:50px 0 0 400px;
}
</style>
</head>
<body>
<div id="preview">
<fieldset>
<legend><font face="Microsoft YaHei">Del Preview</font></legend>
<table width=100%>
<tr><th>Zone</th><th>Host</th><th>Type</th><th>IP</th><th>级别</th></tr>
    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["level"] == 'major'): ?><tr bgcolor="#FF0000">
          <td><div align="center"><?php echo ($vo["zone"]); ?></div></td>
          <td><div align="center"><?php echo ($vo["host"]); ?></div></td>
          <td><div align="center"><?php echo ($vo["dnstype"]); ?></div></td>
          <td><div align="center"><?php echo ($vo["data"]); ?></div></td>
		  <td><div align="center"><?php echo ($vo["level"]); ?></div></td>
          </tr>
		<?php else: ?>
			<tr>
          <td><div align="center"><?php echo ($vo["zone"]); ?></div></td>
          <td><div align="center"><?php echo ($vo["host"]); ?></div></td>
          <td><div align="center"><?php echo ($vo["dnstype"]); ?></div></td>
          <td><div align="center"><?php echo ($vo["data"]); ?></div></td>
		  <td><div align="center"><?php echo ($vo["level"]); ?></div></td>
          </tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
</table>
<span>
<input type="button" name="confirm" class="confirm" onclick="do_del('<?php echo ($delstr); ?>')"/>
<input type="button" name="cancel" class="cancel"onclick="javascript:history.go(-1)"/>
</span>
</fieldset>
<div>
	<br>
	兄弟，上面标红的域名为重要域名，真要删？？！！
</div>
</div>

</body>
</html>