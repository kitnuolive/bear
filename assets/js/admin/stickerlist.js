var StickerList = {
    categoryId : 0,
    StickerImag : "",
    StickerSvg: "",
    myCanvas: null,
    init: function() {
        this.bindEvents();
        this.ViewstickerCategory();

    },
    bindEvents: function() { 
        $("#StickerCategory_select").on('change', this.ViewstickerList);
        $('.order_page').on('click','li', this.viewbyPage);
        $("#table_order tbody").on('click','.delete_stricker_btn', this.DeleteSticker);

        $("#add_sticker_btn").on('click', this.openAddSticker);
        $("#sticker_list_pic").on('change', this.ViewstickerImg);
        $("#sticker_list_pic_svg").on('change', this.ViewstickerSvg);
        $("#save_sticker_btn").on('click', this.saveSticker);
    },

    fabricMyCanvas: function() { 
        // Canvas.myCanvas = new fabric.Canvas('myCanvas');
        StickerList.myCanvas = new fabric.Canvas("viewCanvas", {
              hoverCursor: 'pointer',
              selection: true,
              selectionBorderColor: 'green',
              backgroundColor: null
          });
          StickerList.myCanvas.setHeight(150);
          StickerList.myCanvas.setWidth(150);
    },
    DeleteSticker: function() { 
        $tr = $(this).parents("tr");
        var id = $tr.attr("data-id");
        var name = $tr.find("td").eq(1).text();
        bootbox.confirm({
            title: "ลบสติกเกอร์",
            message: "ต้องการลบ สติกเกอร์ : "+name+" ใช่หรือไม่",
            buttons: {
                confirm: {
                    label: 'ใช่',
                    className: 'btn-danger'
                },
                cancel: {
                    label: 'ไม่',
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if(result){
                    _init.postData("/adminframe/stickerDelet/",{sticker_list_id : id}).done(function (data) {
                        $tr.remove();
                    });
                    console.log('This was logged in the callback: ' + result);
                }
            }
        });
    },
    viewbyPage: function() {
        var ele = $(this).parents("ul.pagination");
        var page = ele.find("li.active").data("lp");
        var obj= {
            "search" : {
                "sticker_category_id" : $("#StickerCategory_select").val()
            }
        }
         
        _init.postData("/frame/stickerList/"+page,obj).done(function (data) {
            var obj = _init.JsonParse(data);

                var num = 0;
                if(obj.view.length > 0){

                    $("#table_order tbody").html("");
                    $.each(obj.view, function( index, value ) {
                        num++;
                        var name =  $("#StickerCategory_select option:selected").text()+" #"+num;
                        var field = '<tr data-page="'+obj.next_page+'">';
                            field += '<td><img style="width: 120px;" src="'+value.sticker_list_pic+'"></td>';
                            field += '<td>'+name+'</td>';
                            field += '<td><button class="delete_stricker_btn" data-id="'+value.sticker_list_id+'">Delete</button></td>';
                            field += '</tr>';
                        $("#table_order tbody").append(field);
                    });
                    
                } else{
                    var field = '<tr><td colspan="3"> no data</td></tr>';
                    $("#table_order tbody").html(field);
                }   
                _init.setPage($(".order_page"), "", $("#table_order"));

        });
    },
    ViewstickerCategory: function() { 
        _init.postData("/frame/stickerCategory/").done(function (data) {
            var obj = _init.JsonParse(data);
            $("#StickerCategory_select").html("<option>เลือกหมวดหมู่</option>");
            if(obj.view.length > 0){
                $.each(obj.view, function( index, value ) {
                    var field = '<option value="'+this.sticker_category_id+'">'+this.sticker_category_name+'</option>';
                    $("#StickerCategory_select").append(field);
                });
            }
        });
    },
    ViewstickerList: function() { 
        StickerList.categoryId = 0;
        if($("#StickerCategory_select").val() == ""){
            var field = '<tr><td colspan="3"> no data</td></tr>';
            $("#table_order tbody").html(field);
            $("#add_sticker_btn").hide();
        }else{
            $("#add_sticker_btn").show();
            StickerList.categoryId = $("#StickerCategory_select").val();
            var obj= {
                "search" : {
                    "sticker_category_id" : $("#StickerCategory_select").val()
                }
            }
            _init.postData("/frame/stickerList/",obj).done(function (data) {
                var obj = _init.JsonParse(data);

                var num = 0;
                if(obj.view.length > 0){

                    $("#table_order tbody").html("");
                    $.each(obj.view, function( index, value ) {
                        num++;
                        var name =  $("#StickerCategory_select option:selected").text()+" #"+num;
                        var field = '<tr data-page="'+obj.next_page+'">';
                            field += '<td><img style="width: 90px;" src="'+value.sticker_list_pic+'"></td>';
                            field += '<td>'+name+'</td>';
                            field += '<td><button class="delete_stricker_btn" data-id="'+value.sticker_list_id+'">Delete</button></td>';
                            field += '</tr>';
                        $("#table_order tbody").append(field);
                    });
                    
                } else{
                    var field = '<tr><td colspan="3"> no data</td></tr>';
                    $("#table_order tbody").html(field);
                }   

            _init.setPage($(".order_page"), "", $("#table_order"));
            });
        }
        
    },
    openAddSticker: function() {
        var name =  $("#StickerCategory_select option:selected").text();
        $("#addsticker_modal").modal("show");
        $("#sticker_Img").attr("src","/upload/sticker/Love/PNG/Love-004.png");
        $("#sticker_category_id").val(name);
        $("#sticker_list_pic").val("");
        $("#sticker_list_pic_svg").val("");
    },
    ViewstickerImg: function(){
        var files = $(this)[0].files[0];
        StickerList.getBase64(files,"png");
    },
    ViewstickerSvg: function(){
        var files = $(this).val();
        if(files != null && files != "null"){
            fabric.loadSVGFromURL(files, function(objects, options) {
                var obj = fabric.util.groupSVGElements(objects, options);
                StickerList.myCanvas.setBackgroundImage(obj, StickerList.myCanvas.renderAll.bind(StickerList.myCanvas), {
                    scaleX: parseFloat(StickerList.myCanvas.width) / parseFloat(obj.width),
                    scaleY: parseFloat(StickerList.myCanvas.height) / parseFloat(obj.height),
                });
                //   canvas.add(obj).renderAll();
            });
        }
        else{
            StickerList.myCanvas.setBackgroundImage(0, StickerList.myCanvas.renderAll.bind(StickerList.myCanvas));
        }
    },
    saveSticker: function(){
        var sticker_list_pic = $("#sticker_list_pic").val();
        var sticker_list_pic_svg = $("#sticker_list_pic_svg").val();

        if(sticker_list_pic == "" || sticker_list_pic_svg == ""){
            alert("กรุณาเพิ่มรูปให้ครบถ้วน");
        }else{
            var filesticker_list_pic_svg = $("#sticker_list_pic_svg")[0].files[0];

            var encodedString = btoa(unescape(encodeURIComponent(filesticker_list_pic_svg)));

            var obj = {
                "sticker_category_id" : StickerList.categoryId,
                "sticker_list_pic" : StickerList.StickerImag,
                "sticker_list_pic_svg" : 'data:image/svg+xml;utf8,' + encodedString
            }

            _init.postData("/adminframe/stickerAdd/",obj).done(function (data) {
                var obj = _init.JsonParse(data);
                console.log(obj);
                StickerList.ViewstickerList();
                $("#addsticker_modal").modal("hide");
    
            });
        }
    },
    getBase64: function(file,type) {

        var filetype = file.type;
        var surname = filetype.split("/");
        // if(surname[1] != type){
        //     if(type == "png"){
        //         $("#sticker_list_pic").val("");
        //         alert("กรุณาเพิ่มรูป .png เท่านั้น");
        //     }
        //     if(type == "svg"){
        //         $("#sticker_list_pic_svg").val("");
        //         alert("กรุณาเพิ่มรูป .svg เท่านั้น");
        //     }
        //     return;
        // }
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function () {
                console.log(reader.result);
                StickerList.StickerImag = reader.result;
                $("#sticker_Img").attr("src",reader.result);
                return reader.result;
            };
            reader.onerror = function (error) {
                console.log('Error: ', error);
                StickerList.StickerImag = "";
                $("#sticker_Img").attr("src","/upload/sticker/Love/PNG/Love-004.png");
                return;
            };
        
    }
};
StickerList.init();