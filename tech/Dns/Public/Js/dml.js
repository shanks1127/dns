//modify
function modify(){
	var checks=document.getElementsByName("del");
	var chbox_value="";
	//if(confirm("确认要修改选中记录?")){
		for(var $i=0;$i<checks.length;$i++){
			if(checks[$i].checked){
				chbox_value+=checks[$i].value+',';
			}
		}
		if (chbox_value==""){
			alert("请选中要修改的记录!");
		}else{
			chbox_value=chbox_value.substring(0,chbox_value.length-1);
			//window.location.href='updaterecord.php?updatestr='+chbox_value;
            window.location.href=APP+"/Index/modify?updatestr="+chbox_value;
		}
	//}
}

function updating(){
     getnewdata();

}

function getnewdata(){
    xmlHttp=new XMLHttpRequest();
    var updatestr="";
    var ids=document.getElementsByName("id");
    var hosts=document.getElementsByName("host");
    var zones=document.getElementsByName("zone");
    var types=document.getElementsByName("type");
    var datas=document.getElementsByName("data");
    var levels=document.getElementsByName("level");
    for(i=0;i<ids.length;i++){
        var id=ids[i].value;
        var host=hosts[i].value;
        var zone=zones[i].value;
        var type=types[i].value;
        var data=datas[i].value;
        var level=levels[i].value;
        updatestr+=id+","+zone+","+host+","+type+","+data+","+level+"|";
    }
    $.ajax({
            type: "POST",
            url:APP+"/Index/do_modify",
            data:{
                'updatestr':updatestr
            },
            dataType: "json",
            success: function (data) { 
                if(data.status==1){
                    alert(data.data);
                    window.location.reload();
                }
            }
    })

}
//add
function adding(){
    xmlHttp=new XMLHttpRequest();
    var addstr="";
    var hosts=document.getElementsByName("host");
    var zones=document.getElementsByName("zone");
    var types=document.getElementsByName("type");
    var datas=document.getElementsByName("data");
    var levels=document.getElementsByName("level");

    for(i=0;i<hosts.length;i++){
        var host=hosts[i].value;
        var zone=zones[i].value;
        var type=types[i].value;
        var data=datas[i].value;
        var level=levels[i].value;
        addstr+=zone+","+host+","+type+","+data+","+level+"|";
    }
    $.ajax({
            type: "POST",
            url:APP+"/Index/do_add",
            data:{
                'addstr':addstr
            },
            dataType: "json",
            success: function (data) { 
                if(data.status==1){
                    alert(data.data);
                    window.location.reload();
                }
            }
    })

}

//delete
function del(){
	var checks=document.getElementsByName("del");
	var chbox_value="";
		for(var $i=0;$i<checks.length;$i++){
			if(checks[$i].checked){
				chbox_value+=checks[$i].value+',';
			}
		}
		if (chbox_value==""){
			alert("请选中要删除的记录!");
		}else{
			//去掉最后的逗号
			chbox_value=chbox_value.substring(0,chbox_value.length-1);
            window.location.href=APP+'/Index/del_preview?delstr='+chbox_value;
		}
}

function do_del(delstr){
      $.ajax({
            type: "POST",
            url:APP+"/Index/do_del",
            data:{
                'delstr':delstr
            },
            dataType: "json",
            success: function (data) { 
                if(data.status==1){
                    alert(data.data);
                    window.location.href=APP+'/Index/index';
                }
            }
    })
}
