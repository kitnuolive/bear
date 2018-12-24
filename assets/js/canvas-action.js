var CanvasAction = {
    frameCategory : [],
    frameCategoryName : "",
    frameCategoryMode : "CUSTOMISE DESIGNS",
    init: function() {
        this.bindEvents();
        this.postData("/frame/frameCategory/").done(function (data) {
            var obj = CanvasAction.JsonParse(data);
            CanvasAction.renderFrameCategory(obj);
        });
        this.postData("/frame/stickerCategory/").done(function (data) {
            var obj = CanvasAction.JsonParse(data);
            CanvasAction.renderStickerCategory(obj);
        });
    },
    bindEvents: function() {   
        $("#frame_category_ui").on("click","li",this.clickFrameCategory);
        $("#sticker_category").on("click",".sticker",this.clickStickerCategory);
        $("#frame_category_mode").on("click",".btn-link",this.clickFrameMode);
        $("#frame_category_name").on("click",this.clickFrameName);
    },
    clickFrameName: function() {
        $("#frame_List").hide();
        $("#frame_category").fadeIn(0);
    },
    renderFrameCategory: function(obj) {   
        $("#frame_category_ui").html("");
        $.each(obj.view, function(key) {
            if(key == 0){
                CanvasAction.frameCategoryName = this.frame_category_name;
                Canvas.frame_category_id = this.frame_category_id;
                Canvas.frame_category_code = this.frame_category_code;
            }
            if(this.frame_category_type == 1){
                if(CanvasAction.frameCategory.filter(x => x.frame_category_name === this.frame_category_name).length == 0){
                    CanvasAction.frameCategory.push(this);
                    $("#frame_category_ui").append('<li data-id="'+this.frame_category_id+'" data-code="'+this.frame_category_code+'"><div>'+this.frame_category_name+'</div></li>');
                }
                else{
                    var index = CanvasAction.frameCategory.findIndex(x => x.frame_category_name === this.frame_category_name);
                    $("#frame_category_ui li").eq(index).attr("data-id",this.frame_category_id);
                }
            }
            else{
                if(CanvasAction.frameCategory.filter(x => x.frame_category_name === this.frame_category_name).length == 0){
                    CanvasAction.frameCategory.push(this);
                    $("#frame_category_ui").append('<li data-fin="'+this.frame_category_id+'" data-code="'+this.frame_category_code+'"><div>'+this.frame_category_name+'</div></li>');
                }
                else{
                    var index = CanvasAction.frameCategory.findIndex(x => x.frame_category_name === this.frame_category_name);
                    $("#frame_category_ui li").eq(index).attr("data-fin",this.frame_category_id);
                }
            }
        }); 

        var id = $("#frame_category_ui li").eq(0).data("id");
        var fin = $("#frame_category_ui li").eq(0).data("fin");
        var code = $("#frame_category_ui li").eq(0).data("code");
        CanvasAction.postData("/frame/frameList/",{"search" :{"frame_category_id":id}}).done(function (data) {
            var obj = CanvasAction.JsonParse(data);
            $("#frame_category_name").text( CanvasAction.frameCategoryName);
            $("#frame_category").hide();
            $("#frame_List").fadeIn(0);

            if(fin == undefined){
                var field = '<div class="col-md-12" style="padding-right: 0;padding-left: 30px;">';
                field += '<button class="btn-link select" data-id="'+id+'" data-code="'+code+'">CUSTOMISE DESIGNS</button>';
                field += '</div>';

                $("#frame_category_mode").html(field);
            }
            else{
                var field = '<div class="col-md-6" style="padding-right: 0;padding-left: 30px;">';
                field += '<button class="btn-link select" data-id="'+id+'" data-code="'+code+'">CUSTOMISE DESIGNS</button>';
                field += '</div>';
                field += '<div class="col-md-6" style="border-left: 1px solid #ddd;">';
                field += '<button class="btn-link" data-id="'+fin+'" data-code="'+code+'">FINISHED DESIGNS</button>';
                field += '</div>';

                $("#frame_category_mode").html(field);
            }
            CanvasAction.renderFrameList(obj);
        });
    },
    clickFrameCategory: function() {   
        var id = $(this).data("id");
        var fin = $(this).data("fin");
        CanvasAction.frameCategoryName = $(this).find("div").text();
        Canvas.frame_category_id = id;
        Canvas.frame_category_code = $(this).data("code");
        CanvasAction.postData("/frame/frameList/",{"search" :{"frame_category_id":id}}).done(function (data) {
            var obj = CanvasAction.JsonParse(data);
            $("#frame_category_name").text( CanvasAction.frameCategoryName);
            $("#frame_category").hide();
            $("#frame_List").fadeIn(0);

            if(fin == undefined){
                var field = '<div class="col-md-12" style="padding-right: 0;padding-left: 30px;">';
                field += '<button class="btn-link select" data-id="'+id+'">CUSTOMISE DESIGNS</button>';
                field += '</div>';

                $("#frame_category_mode").html(field);
            }
            else{
                var field = '<div class="col-md-6" style="padding-right: 0;padding-left: 30px;">';
                field += '<button class="btn-link select" data-id="'+id+'">CUSTOMISE DESIGNS</button>';
                field += '</div>';
                field += '<div class="col-md-6" style="border-left: 1px solid #ddd;">';
                field += '<button class="btn-link" data-id="'+fin+'">FINISHED DESIGNS</button>';
                field += '</div>';

                $("#frame_category_mode").html(field);
            }
            CanvasAction.renderFrameList(obj);
        });
    },
    clickFrameMode: function() {   
        var id = $(this).data("id");
        CanvasAction.frameCategoryMode = $(this).text();
        Canvas.myCanvas.clear();
        Canvas.state = [];

        if(CanvasAction.frameCategoryMode == "CUSTOMISE DESIGNS"){
            Canvas.Addtext();
        }

        Canvas.frame_category_id = id;
        Canvas.frame_category_code = $(this).data("code");
        $("#frame_category_mode .btn-link").removeClass("select");
        $(this).addClass("select");
        CanvasAction.postData("/frame/frameList/",{"search" :{"frame_category_id":id}}).done(function (data) {
            var obj = CanvasAction.JsonParse(data);
            CanvasAction.renderFrameList(obj);
        });
    },
    renderFrameList: function(obj) {   
        var num = 0;
        if(CanvasAction.frameCategoryMode == "CUSTOMISE DESIGNS"){
            var field = '<div class="frame select" data-id="0" num="0" data-src="null" name="blank">';
                field += '<div class="inner">';
                field += '<img src="/assets/images/persoanlisation/png/WHITE-PNG-00.png">';
                field += '</div>';
                field += '</div>';
            $("#frame-panel").html(field);
            $("#canvas-select").find(".select_design").attr("min",0).attr("max",obj.row_total).attr("current",0);
        }
        else{
            $("#frame-panel").html("");
            $("#canvas-select").find(".select_design").attr("min",1).attr("max",obj.row_total).attr("current",1);
        }
        
        // console.log(obj);
        $("#canvas-select").find(".select_design").attr("min",0).attr("max",obj.row_total).attr("current",0);
        $.each(obj.view, function() {
            num++;
            var name =  CanvasAction.frameCategoryName+" #"+num;
            var field = '<div class="frame" num="'+num+'" data-id="'+this.frame_list_id+'" data-src="'+this.frame_list_pic_svg+'" name="'+name+'">';
                field += '<div class="inner">';
                field += '<img src="'+this.frame_list_pic+'">';
                field += '</div>';
                field += '</div>';
            $("#frame-panel").append(field);
        });
    },
    renderStickerCategory: function(obj) {   
        $("#sticker_category").html("");
        $.each(obj.view, function(key) {
            var select ="";
            if(key == 0){select ="select";
                CanvasAction.postData("/frame/stickerList/",{"search" :{"sticker_category_id":this.sticker_category_id}}).done(function (data) {
                    var obj = CanvasAction.JsonParse(data);
                    CanvasAction.renderStickerList(obj);
                });
            }
            var field ='<div class="sticker '+select+'" style="width: auto;" data-id="'+this.sticker_category_id+'">';
            field +='<div class="inner " style="margin-right: 5px;padding: 5px 15px;float: left;">';
            field +='<a href="javascript:;">'+this.sticker_category_name+'</a>';
            field +='</div>';
            field +='</div>';
            $("#sticker_category").append(field);
        }); 
    },
    clickStickerCategory: function() {   
        var id = $(this).data("id");
        $("#sticker_category .sticker").removeClass("select");
        $(this).addClass("select");

        CanvasAction.postData("/frame/stickerList/",{"search" :{"sticker_category_id":id}}).done(function (data) {
            var obj = CanvasAction.JsonParse(data);
            CanvasAction.renderStickerList(obj);
        });
    },
    renderStickerList: function(obj) { 
        $("#sticker-panel").html("");  
        $.each(obj.view, function() {
            var field ='<div class="sticker" data-src="'+this.sticker_list_pic_svg+'">';
                field +='<div class="inner">';
                field +='<img src="'+this.sticker_list_pic+'">';
                field +='</div>';
                field +='</div>';
            $("#sticker-panel").append(field);
        });
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
    },
};
CanvasAction.init();