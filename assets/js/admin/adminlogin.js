var Adminlogin = {
    init: function() {
        this.bindEvents();
    },
    bindEvents: function() { 
        $("body").on('submit',"#form_login", this.adminlogin);
    },
    adminlogin: function() { 
        var obj = {
            "username" : $("#usename").val(),
            "password" : md5($("#password").val(),)
        }
        _init.postData("/adminlogin/" , obj).done(function (data) {
            var obj = _init.JsonParse(data);

            console.log(obj);
            if(obj.error){
                alert("Username or Password is in correct");
            }
            else{
                window.location = "/orderlist";
            }
        });
    }
};
Adminlogin.init();