function auth(user,opt){
    $.ajax({
            type: "POST",
            url:APP+"/User/auth",
            data:{
                'username':user,
                'opt':opt
            },
            dataType: "json",
            success: function (data) { 
                if(data.status==1){
                    alert(data.data);
                    window.location.reload();
                }
                if(data.status==0){
                    alert(data.data);
                }
            }
    })
}

function del_user(user){
    $.ajax({
            type: "POST",
            url:APP+"/User/del_user",
            data:{
                'username':user
            },
            dataType: "json",
            success: function (data) { 
                if(data.status==1){
                    alert(data.data);
                    window.location.reload();
                }
                if(data.status==0){
                    alert(data.data);
                }
            }
    })
}