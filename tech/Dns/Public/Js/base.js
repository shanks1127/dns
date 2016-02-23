/*生成DNS 配置文件*/
function ReportDNS(){
    $.ajax({
            type: "POST",
            url:APP+"/Index/sync_dns",
            data:{},
            dataType: "json",
            beforeSend:function(){
                obj=document.getElementById("holder");
                obj.style.display="block";
            },
            success: function (data) { 
                if(data.status==1){
                    alert(data.data);
                    window.location.href=APP+'/Index/index';
                }
            }
    })
}

window.onload = function() {
    var remove = spinner("holder", 5, 15, 12, 3, "rainbow");
    var form = {
                form: document.getElementsByTagName("form")[0],
                r1: document.getElementById("radius1"),
                r2: document.getElementById("radius2"),
                count: document.getElementById("count"),
                width: document.getElementById("width"),
                color: document.getElementById("color")
                };
                
    form.onsubmit = function () {
                    remove();
                    remove = spinner("holder", +form.r1.value, +form.r2.value, +form.count.value, +form.width.value, form.color.value);
                    return false;
                };
};
            
function spinner(holderid, R1, R2, count, stroke_width, colour) {
    var sectorsCount = count || 12,
                    color = colour || "#fff",
                    width = stroke_width || 15,
                    r1 = Math.min(R1, R2) || 35,
                    r2 = Math.max(R1, R2) || 60,
                    cx = r2 + width,
                    cy = r2 + width,
                    r = Raphael(holderid, r2 * 2 + width * 2, r2 * 2 + width * 2),
                    sectors = [],
                    opacity = [],
                    beta = 2 * Math.PI / sectorsCount,
 
                    pathParams = {stroke: color, "stroke-width": width, "stroke-linecap": "round"};
                    Raphael.getColor.reset();
    for (var i = 0; i < sectorsCount; i++) {
        var alpha = beta * i - Math.PI / 2,
            cos = Math.cos(alpha),
            sin = Math.sin(alpha);
        opacity[i] = 1 / sectorsCount * i;
        sectors[i] = r.path([["M", cx + r1 * cos, cy + r1 * sin], ["L", cx + r2 * cos, cy + r2 * sin]]).attr(pathParams);
        if (color == "rainbow") {
            sectors[i].attr("stroke", Raphael.getColor());
        }
    }
    var tick;
    (function ticker() {
        opacity.unshift(opacity.pop());
        for (var i = 0; i < sectorsCount; i++) {
            sectors[i].attr("opacity", opacity[i]);
        }
        r.safari();
        tick = setTimeout(ticker, 1000 / sectorsCount);
    })();
    return function () {
        clearTimeout(tick);
        r.remove();
    };
 }
 //用户授权
 function User_Auth(flag,user){
      xmlHttp=new XMLHttpRequest();
      var url="userauth.php";
      var Querystring="flag="+flag+"&username="+user;
      //alert(Querystring);
      xmlHttp.onreadystatechange=authhandle;
      xmlHttp.open("POST",url,true);
      xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
      xmlHttp.send(Querystring);
 }
 function authhandle(){
      if(xmlHttp.readyState==4){
           if(xmlHttp.status==200){
                 alert(xmlHttp.responseText);
				 //document.write(xmlHttp.responseText)
                 window.location.reload();

            }
      }
}
 //用户删除
 function User_Del(user){
      xmlHttp=new XMLHttpRequest();
      var url="userdel.php";
      var Querystring="username="+user;
      //alert(Querystring);
      xmlHttp.onreadystatechange=userdelhandle;
      xmlHttp.open("POST",url,true);
      xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
      xmlHttp.send(Querystring);
 }
 function userdelhandle(){
      if(xmlHttp.readyState==4){
           if(xmlHttp.status==200){
                 alert(xmlHttp.responseText);
				 //document.write(xmlHttp.responseText)
                 window.location.reload();

            }
      }
}

function senfe(o,a,b,c,d){
 var t=document.getElementById(o).getElementsByTagName("tr");
 for(var i=0;i<t.length;i++){
  t[i].style.backgroundColor=(t[i].sectionRowIndex%2==0)?a:b;
  t[i].onclick=function(){
   if(this.x!="1"){
    this.x="1";
    this.style.backgroundColor=d;
   }else{
    this.x="0";
    this.style.backgroundColor=(this.sectionRowIndex%2==0)?a:b;
   }
  }
  t[i].onmouseover=function(){
   if(this.x!="1")this.style.backgroundColor=c;
  }
  t[i].onmouseout=function(){
   if(this.x!="1")this.style.backgroundColor=(this.sectionRowIndex%2==0)?a:b;
  }
 }
}