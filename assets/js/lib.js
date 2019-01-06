var _init = {
    init: function() {
        this.bindEvents();

    },
    bindEvents: function() { 

    },
    postData: function(url, data) {
        if(data){
            var data = JSON.stringify(data);
            this.ajax = $.ajax({url: url, type: 'post',data: {data} });
        }else{
            this.ajax = $.ajax({url: url, type: 'post'});
        }
        return this.ajax;
    },
    JsonParse: function(data) {
        if(data == "<script>location.reload();</script>"){
            window.location = "/login";
            return;
        }
        if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
        replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
        replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
            var obj = JSON.parse(data);
        }else{
            var obj = {
                "en" : data,
                "th" : data
            };
        }
        /*
        if(obj.error == "session EXP"){
            window.location = "/login";
            return;
        }
        */
        return obj;
    }
};
_init.init();