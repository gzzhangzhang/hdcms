$(function(){
    $('form').validate({
        webname:{
            rule:{
                required:true
            },
            error:{
                required:'网站名称不能为空'
            }
        },
        url:{
            rule:{
                required:true,
                regexp:/^http/i
            },
            error:{
                required:'网址不能为空',
                regexp:'网址非法'
            }
        },
        email:{
            rule:{
                required:true,
                email:true
            },
            error:{
                required:'站长邮箱不能为空',
                email:'邮箱格式不正确'
            }
        }
    })
})