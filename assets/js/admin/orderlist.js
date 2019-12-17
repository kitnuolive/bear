var OrderList = {
    init: function() {
        this.bindEvents();
        this.ViewOrderList();

    },
    bindEvents: function() { 
        $("#table_order").on('click',".download_svg", this.downloadSvg);
        $('.order_page').on('click','li', this.viewbyPage);
        $('#search_Code').on('submit', this.searchCode);
    },
    downloadSvg: function() { 
        var file = $(this).attr("data-file");
        var name = $(this).attr("data-name");
        saveAs(file, name+".svg");

        // var zip = new JSZip();
        // // zip.add(name+".svg", file);
        // zip.fileURL(name+".svg", file);
        // zip.generateAsync({type:"blob"})
        // .then(function(content) {
        //     // see FileSaver.js
        //     saveAs(content, name+".zip");
        // });
    },
    viewbyPage: function() {
        var ele = $(this).parents("ul.pagination");
        var page = ele.find("li.active").data("lp");
         
        _init.postData("/adminorder/orderlist/"+page).done(function (data) {
            var obj = _init.JsonParse(data);

            console.log(obj);
            if(obj.view.length > 0){

                $("#table_order tbody").html("");
                $.each(obj.view, function( index, value ) {
                    var field = '<tr data-page="'+obj.next_page+'">';
                        field += '<td><img style="width: 120px;" src="'+value.bear_order_path+'"></td>';
                        field += '<td>'+value.bear_order_number+'</td>';
                        field += '<td>'+value.create_date+'</td>';
                        field += '<td><button class="download_svg" data-file="'+value.bear_order_path_svg+'" data-name="'+value.bear_order_number+'">download</button></td>';
                        field += '</tr>';
                    $("#table_order tbody").append(field);
                });
                
            } else{
                var field = '<tr><td colspan="4"> no data</td></tr>';
                $("#table_order tbody").html(field);
            }   
        });
    },
    searchCode: function() { 
        var obj= {
            "search" : {
                "order_by" : "create_date",
                "order_type" : "desc",
                "bear_order_number" : $("#code_input").val()
            }
        }
        _init.postData("/adminorder/orderlist/",obj).done(function (data) {
            var obj = _init.JsonParse(data);

            console.log(obj);
            if(obj.view.length > 0){

                $("#table_order tbody").html("");
                $.each(obj.view, function( index, value ) {
                    var field = '<tr data-page="'+obj.next_page+'">';
                        field += '<td><img style="width: 250px;" src="'+value.bear_order_path+'"></td>';
                        field += '<td>'+value.bear_order_number+'</td>';
                        field += '<td>'+value.create_date+'</td>';
                        field += '<td><button class="download_svg" data-file="'+value.bear_order_path_svg+'" data-name="'+value.bear_order_number+'">download</button></td>';
                        field += '</tr>';
                    $("#table_order tbody").append(field);
                });
                
            } else{
                var field = '<tr><td colspan="4"> no data</td></tr>';
                $("#table_order tbody").html(field);
            }   

            _init.setPage($(".order_page"), "", $("#table_order"));
        });
    },
    ViewOrderList: function() { 
        var obj= {
            "search" : {
                "order_by" : "create_date",
                "order_type" : "desc"
            }
        }
        _init.postData("/adminorder/orderlist/",obj).done(function (data) {
            var obj = _init.JsonParse(data);

            console.log(obj);
            if(obj.view.length > 0){

                $("#table_order tbody").html("");
                $.each(obj.view, function( index, value ) {
                    var field = '<tr data-page="'+obj.next_page+'">';
                        field += '<td><img style="width: 120px;" src="'+value.bear_order_path+'"></td>';
                        field += '<td>'+value.bear_order_number+'</td>';
                        field += '<td>'+value.create_date+'</td>';
                        field += '<td><button class="download_svg" data-file="'+value.bear_order_path_svg+'" data-name="'+value.bear_order_number+'">download</button></td>';
                        field += '</tr>';
                    $("#table_order tbody").append(field);
                });
                
            } else{
                var field = '<tr><td colspan="4"> no data</td></tr>';
                $("#table_order tbody").html(field);
            }   

        _init.setPage($(".order_page"), "", $("#table_order"));
        });
    }
};
OrderList.init();