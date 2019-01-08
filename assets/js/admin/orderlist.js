var OrderList = {
    init: function() {
        this.bindEvents();
        this.ViewOrderList();

    },
    bindEvents: function() { 
        $("#table_order").on('click',".download_svg", this.downloadSvg);
    },
    downloadSvg: function() { 
        var file = $(this).attr("data-file");
        var name = $(this).attr("data-name");

        var zip = new JSZip();
        zip.add(name+".svg", file);
        zip.generateAsync({type:"blob"})
        .then(function(content) {
            // see FileSaver.js
            saveAs(content, name+".zip");
        });
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
                        field += '<td><button class="download_svg" data-file="'+value.bear_order_path_svg+'" data-name="'+value.bear_order_number+'">download</button></td>';
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