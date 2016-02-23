var isIe=(document.all)?true:false;
//设置select的可见状态
function setSelectState(state)
{
  var objl=document.getElementsByTagName('select');
  for(var i=0;i<objl.length;i++)
  {
    objl[i].style.visibility=state;
  }
}
function mousePosition(ev)
{
  if(ev.pageX || ev.pageY)
  {
    return {x:ev.pageX, y:ev.pageY};
  }
  return {
x:ev.clientX + document.body.scrollLeft - document.body.clientLeft,y:ev.clientY + document.body.scrollTop - document.body.clientTop
  };
}
//弹出方法
function showMessageBox(wTitle,content,pos,wWidth)
{
  closeWindow();
  var bWidth=parseInt(document.documentElement.scrollWidth);
  var bHeight=parseInt(document.documentElement.scrollHeight);
  if(isIe){
    setSelectState('hidden');}
  var back=document.createElement("div");
  back.id="back";
  var styleStr="top:0px;left:0px;position:absolute;background:#666;width:"+bWidth+"px;height:"+bHeight+"px;";
  styleStr+=(isIe)?"filter:alpha(opacity=0);":"opacity:0;";
  back.style.cssText=styleStr;
  document.body.appendChild(back);
  showBackground(back,50);
  var mesW=document.createElement("div");
  mesW.id="mesWindow";
  mesW.className="mesWindow";
mesW.innerHTML="<div class='mesWindowTop' style='width:600px;background-color:#73A2d6' onmousedown=\"drag(mesWindow);return false\"><table width='100%' height='100%'><tr><td>"+wTitle+"</td><td style='width:1px;'><input type='button' onclick='closeWindow();' title='关闭窗口' class='close' value='关闭' /></td></tr></table></div><div class='mesWindowContent' id='mesWindowContent'>"+content+"</div><div class='mesWindowBottom'></div>";
  styleStr="left:"+(((pos.x-wWidth)>0)?(pos.x-wWidth):pos.x)+"px;top:"+(pos.y)+"px;position:absolute;width:"+wWidth+"px;";
  mesW.style.cssText=styleStr;
  document.body.appendChild(mesW);
}

function showBigMessageBox(wTitle,content,pos,wWidth)
{
    closeWindow();
    var bWidth=parseInt(document.documentElement.scrollWidth);
    var bHeight=parseInt(document.documentElement.scrollHeight);
    if(isIe){
        setSelectState('hidden');}
        var back=document.createElement("div");
        back.id="back";
        var styleStr="top:0px;left:0px;position:absolute;background:#666;width:"+bWidth+"px;height:"+bHeight+"px;";
        styleStr+=(isIe)?"filter:alpha(opacity=0);":"opacity:0;";
        back.style.cssText=styleStr;
        document.body.appendChild(back);
        showBackground(back,50);
        var mesW=document.createElement("div");
        mesW.id="mesWindow";
        mesW.className="mesWindow";
        mesW.innerHTML="<div class='mesWindowTop' style='width:1100px;background-color:#73A2d6' onmousedown=\"drag(mesWindow);return false\"><table width='100%' height='100%'><tr><td>"+wTitle+"</td><td style='width:1px;'><input type='button' onclick='closeWindow();' title='关闭窗口' class='close' value='关闭' /></td></tr></table></div><div class='mesWindowContent' id='mesWindowContent'>"+content+"</div><div class='mesWindowBottom'></div>";
        styleStr="left:"+(((pos.x-wWidth)>0)?(pos.x-wWidth):pos.x)+"px;top:"+(pos.y)+"px;position:absolute;width:"+wWidth+"px;";
        mesW.style.cssText=styleStr;
        document.body.appendChild(mesW);
}

//让背景渐渐变暗
function showBackground(obj,endInt)
{
  if(isIe)
  {
    obj.filters.alpha.opacity+=1;
    if(obj.filters.alpha.opacity<endInt)
    {
      setTimeout(function(){showBackground(obj,endInt)},5);
    }
  }else{
    var al=parseFloat(obj.style.opacity);al+=0.01;
    obj.style.opacity=al;
    if(al<(endInt/100))
    {setTimeout(function(){showBackground(obj,endInt)},5);}
  }
}
//关闭窗口
function closeWindow()
{
  if(document.getElementById('back')!=null)
  {
    document.getElementById('back').parentNode.removeChild(document.getElementById('back'));
  }
  if(document.getElementById('mesWindow')!=null)
  {
    document.getElementById('mesWindow').parentNode.removeChild(document.getElementById('mesWindow'));
  }
  if(isIe){
    setSelectState('');}
}
//拖动
function drag(obj){  
  var s = obj.style;  
  var b = document.body;   
  var x = event.clientX + b.scrollLeft - s.pixelLeft;   
  var y = event.clientY + b.scrollTop - s.pixelTop; 

  var m = function(){  
    if(event.button == 1){  
      s.pixelLeft = event.clientX + b.scrollLeft - x;   
      s.pixelTop = event.clientY + b.scrollTop - y;   
    }else {
      document.detachEvent("onmousemove", m);
    }  
  }  

  document.attachEvent("onmousemove", m)  

    if(!this.z) 
      this.z = 999;   
  s.zIndex = ++this.z;   
  event.cancelBubble = true;   
}
