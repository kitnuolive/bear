var _init = {
    init: function() {
        this.bindEvents();

    },
    bindEvents: function() { 

    },
    setPage : function($page, total, $table , page){
        /*set search value*/
        if (window.location.search != "") {
            // $(".search_field").val("");
            var pathname = window.location.search.split("?");
            pathname = pathname[1].split("&");
            $.each(pathname, function( i, v ) {
                var name = v.split("=");

                if (name[0] != "page") {
                    var velue = "";
                    if (name[1] != undefined) {
                        var velue = name[1].replace(/%20/gi, " ");
                    }
                    
                    $(".search_field[data-search='"+name[0]+"']").val(velue);
                }
            });
        } 
        // if (total > 1) {

        // console.log($page);
        // console.log(total);
        // console.log($table);
        // console.log(page);
        if($table){
            if($table.find("tbody tr").last().attr("data-page") && total == ""){
                total = $table.find("tbody tr").last().attr("data-page");
            }
            $page.bootpag({
                total: total,
                page: page,
                maxVisible: 5,
                leaps: false,
                firstLastUse: false,
                prev: '<i class="fa fa-angle-left"></i>',
                next: '<i class="fa fa-angle-right"></i>',
                wrapClass: 'pagination',
                activeClass: 'active',
                disabledClass: 'disabled',
                nextClass: 'next',
                prevClass: 'prev',
                lastClass: 'last',
                firstClass: 'first'
            })
            .on('page', function(event, num){
               // _init.postSearch($table, num, $table.data("option"));
            });
        }else{
            $page.bootpag({maxVisible: 5,total: total,page: page});
        }
        // }       
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