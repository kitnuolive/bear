var FrameList = {
    categoryId : 0,
    frameImag : "",
    frameSvg : "",
    myCanvas : null,
    init: function() {
        this.bindEvents();
        this.ViewframeCategory();

        this.fabricMyCanvas();

    },
    bindEvents: function() { 
        $("#frameCategory_select").on('change', this.ViewframeList);
        $('.order_page').on('click','li', this.viewbyPage);
        $("#table_order tbody").on('click','.delete_frame_btn', this.DeleteFrame);

        $("#add_frame_btn").on('click', this.openAddframe);
        $("#frame_list_pic").on('change', this.ViewframeImg);
        $("#frame_list_pic_svg").on('change', this.ViewframeSvg);
        $("#save_frame_btn").on('click', this.saveframe);
    },

    fabricMyCanvas: function() { 
        // Canvas.myCanvas = new fabric.Canvas('myCanvas');
        FrameList.myCanvas = new fabric.Canvas("viewCanvas", {
              hoverCursor: 'pointer',
              selection: true,
              selectionBorderColor: 'green',
              backgroundColor: null
          });
          FrameList.myCanvas.setHeight(265);
          FrameList.myCanvas.setWidth(412);
    },
    DeleteFrame: function() { 
        $tr = $(this).parents("tr");
        var id = $tr.attr("data-id");
        var name = $tr.find("td").eq(1).text();
        bootbox.confirm({
            title: "ลบเฟรม",
            message: "ต้องการลบ เฟรม : "+name+" ใช่หรือไม่",
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
                    _init.postData("/adminframe/frameDelete/",{frame_list_id : id}).done(function (data) {
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
                "frame_category_id" : $("#frameCategory_select").val()
            }
        }
        _init.postData("/frame/frameList/"+page,obj).done(function (data) {
            var obj = _init.JsonParse(data);

                var num = 0;
                if(obj.view.length > 0){
                    $("#table_order tbody").html("");
                    $.each(obj.view, function( index, value ) {
                        num++;
                        var name =  $("#frameCategory_select option:selected").text()+" #"+num;
                        var field = '<tr data-page="'+obj.next_page+'">';
                            field += '<td><img style="width: 120px;" src="'+value.frame_list_pic+'"></td>';
                            field += '<td>'+name+'</td>';
                            field += '<td><button class="delete_frame_btn" data-id="'+value.frame_list_id+'">Delete</button></td>';
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
    ViewframeCategory: function() { 
        _init.postData("/frame/frameCategory/").done(function (data) {
            var obj = _init.JsonParse(data);
            $("#frameCategory_select").html("<option>เลือกหมวดหมู่</option>");
            if(obj.view.length > 0){
                $.each(obj.view, function( index, value ) {
                    var type = "FINISHED DESIGNS";
                    if(this.frame_category_type == 1){
                        type = "CUSTOMISE DESIGNS";
                    }
                    var field = '<option value="'+this.frame_category_id+'">'+this.frame_category_name+' ('+type+')</option>';
                    $("#frameCategory_select").append(field);
                });
            }
        });
    },
    ViewframeList: function() { 
        FrameList.categoryId = 0;
        if($("#frameCategory_select").val() == ""){
            var field = '<tr><td colspan="3"> no data</td></tr>';
            $("#table_order tbody").html(field);
            $("#add_frame_btn").hide();
        }else{
            $("#add_frame_btn").show();
            FrameList.categoryId = $("#frameCategory_select").val();
            var obj= {
                "search" : {
                    "frame_category_id" : $("#frameCategory_select").val()
                }
            }
            _init.postData("/frame/frameList/",obj).done(function (data) {
                var obj = _init.JsonParse(data);

                var num = 0;
                if(obj.view.length > 0){
                    $("#table_order tbody").html("");
                    $.each(obj.view, function( index, value ) {

                        num++;
                        var name =  $("#frameCategory_select option:selected").text()+" #"+num;
                        var field = '<tr data-page="'+obj.next_page+'">';
                            field += '<td><img style="width: 200px;" src="'+value.frame_list_pic+'"></td>';
                            field += '<td>'+name+'</td>';
                            field += '<td><button class="delete_frame_btn" data-ID="'+value.frame_list_id+'">Delete</button></td>';
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
    openAddframe: function() {
        var name =  $("#frameCategory_select option:selected").text();
        $("#addframe_modal").modal("show");
        $("#frame_Img").attr("src","/upload/frame/CUSTOMIZE/PNG/11-9.png");
        $("#frame_category_id").val(name);
        $("#frame_list_pic").val("");
        $("#frame_list_pic_svg").val("");
    },
    ViewframeImg: function(){
        var files = $(this)[0].files[0];
        FrameList.getBase64(files,"png");
    },
    ViewframeSvg: function(){
        var files = $(this).val();
        if(files != null && files != "null"){
            fabric.loadSVGFromURL(files, function(objects, options) {
                var obj = fabric.util.groupSVGElements(objects, options);
                FrameList.myCanvas.setBackgroundImage(obj, FrameList.myCanvas.renderAll.bind(FrameList.myCanvas), {
                    scaleX: parseFloat(FrameList.myCanvas.width) / parseFloat(obj.width),
                    scaleY: parseFloat(FrameList.myCanvas.height) / parseFloat(obj.height),
                });
                //   canvas.add(obj).renderAll();
            });
        }
        else{
            FrameList.myCanvas.setBackgroundImage(0, FrameList.myCanvas.renderAll.bind(FrameList.myCanvas));
        }
    },
    saveframe: function(){
        var frame_list_pic = $("#frame_list_pic").val();
        var frame_list_pic_svg = $("#frame_list_pic_svg").val();

        if(frame_list_pic == "" || frame_list_pic_svg == ""){
            alert("กรุณาเพิ่มรูปให้ครบถ้วน");
        }else{
            var encodedString = btoa(unescape(encodeURIComponent(FrameList.myCanvas.toSVG())));

            var obj = {
                "frame_category_id" : FrameList.categoryId,
                "frame_list_pic" : FrameList.frameImag,
                "frame_list_pic_svg" : 'data:image/svg+xml;utf8,' + encodedString
            }

            _init.postData("/adminframe/frameAdd/",obj).done(function (data) {
                var obj = _init.JsonParse(data);
                console.log(obj);
                FrameList.ViewframeList();
                $("#addframe_modal").modal("hide");
    
            });
        }
    },
    getBase64: function(file,type) {

        var filetype = file.type;
        var surname = filetype.split("/");
        console.log(file,filetype)
        // if(surname[1] != type){
        //     if(type == "png"){
        //         $("#frame_list_pic").val("");
        //         alert("กรุณาเพิ่มรูป .png เท่านั้น");
        //     }
        //     if(type == "svg"){
        //         $("#frame_list_pic_svg").val("");
        //         alert("กรุณาเพิ่มรูป .svg เท่านั้น");
        //     }
        //     return;
        // }
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function () {
                console.log(file);
                console.log(reader.result);
                FrameList.frameImag = reader.result;

                $("#frame_Img").attr("src",reader.result);
                return reader.result;
            };
            reader.onerror = function (error) {
                console.log('Error: ', error);

                FrameList.frameImag = "";
                $("#frame_Img").attr("src","/upload/frame/CUSTOMIZE/PNG/11-9.png");
                return;
            };
        
    }
};
FrameList.init();