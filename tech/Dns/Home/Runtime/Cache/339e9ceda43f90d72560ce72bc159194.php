<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>批量修改</title>
<link  rel="stylesheet" type="text/css" href="__PUBLIC__/Css/base.css"/>
<script type="text/javascript">
    var PUBLIC= "__PUBLIC__";
    var APP = "__APP__";
    var ROOT = "__ROOT__";
</script>
<script  type="text/javascript" src="__PUBLIC__/Js/jquery.js" charset="utf-8"></script>
<script  type="text/javascript" src="__PUBLIC__/Js/dml.js"></script>
</head>
<style>
    #update{
        margin:0 auto;
        width:600px;
        margin-top: 50px;
    }
    .mark{
        font-family:"宋体";
        font-size:15px;
        COLOR: #8080FF;
    }
</style>

<body>
<div id="update">
    <a id="back" href="__URL__"><img src="__PUBLIC__/Images/new/btn_return.png"/></a>
    <fieldset>
        <legend class="mark"><font face='Microsoft YaHei'>DNS Modify</font></legend>
        <table>
        <tr><th>Zone</th><th>Host</th><th>Type</th><th>IP</th><th>级别</th></tr>
        <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["level"] == 'major'): ?><tr bgcolor="#FF0000">
                <input type="hidden" name="id" id="id"  value="<?php echo ($vo["id"]); ?>"/>
                <td>
                    <select name="zone">
                        <option value="idc2" <?php if(($vo["zone"]) == "idc2"): ?>selected<?php endif; ?> >IDC2</option> 
                        <option value="idc3" <?php if(($vo["zone"]) == "idc3"): ?>selected<?php endif; ?> >IDC3</option>
                        <option value="idc4" <?php if(($vo["zone"]) == "idc4"): ?>selected<?php endif; ?> >IDC4</option>
                        <option value="idc5" <?php if(($vo["zone"]) == "idc5"): ?>selected<?php endif; ?> >IDC5</option>
                        <option value="db" <?php if(($vo["zone"]) == "DB"): ?>selected<?php endif; ?> >DB</option>
                        <option value="api" <?php if(($vo["zone"]) == "API"): ?>selected<?php endif; ?> >API</option>
                        <option value="dangdang.com" <?php if(($vo["zone"]) == "dangdang.com"): ?>selected<?php endif; ?> >DANGDANG.COM</option>
                    </select>
                </td>
                <td><input type="text" name="host" value="<?php echo ($vo["host"]); ?>"/></td>
                <td><input type="text" name="type" value="<?php echo ($vo["dnstype"]); ?>"/></td>
                <td><input type="text" name="data" value="<?php echo ($vo["data"]); ?>"/></td>
				<td><input type="text" name="level" value="<?php echo ($vo["level"]); ?>" disabled/></td>
				</tr>
			<?php else: ?>
				<tr>
                <input type="hidden" name="id" id="id"  value="<?php echo ($vo["id"]); ?>"/>
                <td>
                    <select name="zone">
                        <option value="idc2" <?php if(($vo["zone"]) == "idc2"): ?>selected<?php endif; ?> >IDC2</option> 
                        <option value="idc3" <?php if(($vo["zone"]) == "idc3"): ?>selected<?php endif; ?> >IDC3</option>
                        <option value="idc4" <?php if(($vo["zone"]) == "idc4"): ?>selected<?php endif; ?> >IDC4</option>
                        <option value="idc5" <?php if(($vo["zone"]) == "idc5"): ?>selected<?php endif; ?> >IDC5</option>
                        <option value="db" <?php if(($vo["zone"]) == "DB"): ?>selected<?php endif; ?> >DB</option>
                        <option value="api" <?php if(($vo["zone"]) == "API"): ?>selected<?php endif; ?> >API</option>
                        <option value="dangdang.com" <?php if(($vo["zone"]) == "dangdang.com"): ?>selected<?php endif; ?> >DANGDANG.COM</option>
                    </select>
                </td>
                <td><input type="text" name="host" value="<?php echo ($vo["host"]); ?>"/></td>
                <td><input type="text" name="type" value="<?php echo ($vo["dnstype"]); ?>"/></td>
                <td><input type="text" name="data" value="<?php echo ($vo["data"]); ?>"/></td>
				<td><input type="text" name="level" value="<?php echo ($vo["level"]); ?>" disabled/></td>
				</tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        <span class="btn">
            <tr><td align="center" style='background:#fff' colspan=5><input type="button" name="save" class="save" onclick="updating()"/></td></tr>
        </span>
        </table>
    </fieldset>
</div>
<div>
	<br>
	兄弟，上面标红的域名为重要域名，请谨慎修改！！
</div>
</body>
</html>