var OrderList = {
    init: function() {
        this.bindEvents();
        this.ViewOrderList();

    },
    bindEvents: function() { 

    },
    ViewOrderList: function() { 
        var obj= {
            "search" : {
                "orderby" : "create_date",
                "ordertype" : "asc"
            }
        }
        _init.postData("/adminorder/orderlist/",obj).done(function (data) {
            var obj = _init.JsonParse(data);

            console.log(obj.view);
            if(obj.view.length > 0){

                $("#table_order tbody").html("");
                $.each(obj.view, function( index, value ) {
                    var field = '<tr>';
                        field += '<td><img style="width: 120px;" src="'+value.bear_order_path+'"></td>';
                        field += '<td>'+value.bear_order_number+'</td>';
                        field += '<td>'+value.create_date+'</td>';
                        field += '<td><a href="'+value.bear_order_path_svg+'" download="'+value.bear_order_number+'">download</a></td>';
                        field += '</tr>';
                    $("#table_order tbody").append(field);
                });
                
            } else{
                var field = '<tr><td colspan="4"> no data</td></tr>';
                $("#table_order tbody").html(field);
            }   
        });
    }
};
OrderList.init();