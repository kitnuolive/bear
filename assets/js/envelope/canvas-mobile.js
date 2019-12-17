
var Canvas = {
    myCanvas : null,
    viewCanvas : null,
    context : null,
    current :null,
    mods : 0,
    counter:0,
    state : [],
    frame_category_id : 0,
    frame_list_id : 0,
    frame_category_code : "ev",
    bear_order_number : "",
    bear_order_id : 0,
    init: function() {
      this.bindEvents();

      CanvasAction.postData("/frame/frameCategory/").done(function (data) {
        var obj = CanvasAction.JsonParse(data);
          // CanvasAction.renderFrameCategory(obj);
          CanvasAction.renderFrameCategoryInline(obj);
      });
      this.fabricMyCanvas();
      // display/hide text controls
       
    },
    bindEvents: function() {         
      $('#canvas-tool').on('click', '.step-head',this.selectStep);
      $('#frame-panel').on('click', '.frame',this.selectFrame);
      $('#reset_btn').on('click',this.resetCanvas);

      $('#sticker-panel').on('click', '.sticker',this.selectIcon);

      $('#file').on('change',this.addImage);
      $('#delSelect_btn').on('click',this.delSelect);
      $('#sendFront_btn').on('click',this.sendFront);
      $('#sendBack_btn').on('click',this.sendBack);
      $('#clearAll_btn').on('click',this.clearAll);
      $('#Addtext_btn').on('click',this.Addtext);

      $('#font-family').on('change',this.setFontFamily);
      $('#text-line-height').on('change',this.setLineHeight);
      $('#text-space').on('change',this.setCharSpacing);
      $('#text-size').on('change',this.setFontSize);
      $('#text-align').on('click', 'i',this.setTextAlign);

      $(".number-box").on('click', 'i',this.selectNumber);
      $("#canvas-select").on('click', '.btn-select',this.selectnNextFrame);

      // $('#lnkDownload').on('click',this.complete);
      $('#complete_btn').on('click',this.complete);
      $('#undo_btn').on('click',this.undo);
      $('#redo_btn').on('click',this.redo);
    },
    fabricMyCanvas: function() { 
      // Canvas.myCanvas = new fabric.Canvas('myCanvas');
      Canvas.myCanvas = new fabric.Canvas("myCanvas", {
            hoverCursor: 'pointer',
            selection: true,
            selectionBorderColor: 'green',
            backgroundColor: null
        });
      Canvas.myCanvas.setHeight(192);
      Canvas.myCanvas.setWidth(290);

      Canvas.Addtext();

      // Do some initializing stuff
      fabric.Object.prototype.set({
        transparentCorners: false,
        rotatingPointOffset :30,
        cornerStyle: 'circle',
        cornerColor: '#22A7F0',
        borderColor: '#22A7F0',
        cornerSize: 12,
        padding:5
      });
      
      Canvas.myCanvas.forEachObject(function(o){ 
        o.setControlVisible('mt',false);
        o.setControlVisible('mb',false);
        o.setControlVisible('ml',false);
        o.setControlVisible('mr',false);
      });

      Canvas.myCanvas.on(
          'object:modified', function () {
          updateModifications(true);
      },
          'object:added', function () {
          updateModifications(true);   
      });

      function updateModifications(savehistory) {
          if (savehistory === true) {
              //myjson = JSON.stringify(canvas);
              var myjson = Canvas.myCanvas.toJSON();
              Canvas.state.push(myjson);
          }
      }

      Canvas.myCanvas.on('object:selected', function(e) {
        var step = $(".step");
        $("#canvas-tool").addClass("open"); 
        $(".step-head").removeClass("select");
        if (e.target.type === 'i-text') {
          //console.log("text",e);
          step.removeClass("select");
          $(".step[mode=2]").addClass("select");
          $(".step-head[mode=2]").addClass("select");
          $("#frame_category").hide();
          $("#frame_List").hide();
          $("#text-size").val(e.target.fontSize);
          $("#font-family").val(e.target.fontFamily);
          $("#text-space").val(e.target.charSpacing/100);
          $("#text-line-height").val(e.target.lineHeight);
        }
        else if (e.target.type === 'group') {
            // $('#textControls').hidden = true;
          step.removeClass("select");
          $(".step[mode=3]").addClass("select");
          $(".step-head[mode=3]").addClass("select");
          $("#frame_category").hide();
          $("#frame_List").hide();
        }
        else{
          step.removeClass("select");
          $(".step[mode=1]").addClass("select");
          $(".step-head[mode=1]").addClass("select");
          $("#frame_category").hide();
          $("#frame_List").show();

        }
      });

      Canvas.myCanvas.on('before:selection:cleared', function(e) {
        var step = $(".step");  
        //console.log("cleared :" +e.target.type);

        $(".step-head").removeClass("select");
        if (e.target.type === 'i-text') {
          // $('#textControls').hidden = true;
          step.removeClass("select");
          $(".step[mode=2]").addClass("select");
          $(".step-head[mode=2]").addClass("select");
          $("#frame_category").hide();
          $("#frame_List").hide();
        }
        else if (e.target.type === 'image') {
            // $('#textControls').hidden = true;
          step.removeClass("select");
          $(".step[mode=1]").addClass("select");
          $(".step-head[mode=1]").addClass("select");
          $("#frame_category").hide();
          $("#frame_List").show();
        }
        else{
          step.removeClass("select");
          $(".step[mode=3]").addClass("select");
          $(".step-head[mode=3]").addClass("select");
          $("#frame_category").hide();
          $("#frame_List").hide();
        }
      });

      // Canvas.viewCanvas = new fabric.Canvas("viewCanvas", {
      //       hoverCursor: 'pointer',
      //       selection: true,
      //       selectionBorderColor: 'green',
      //       backgroundColor: null
      //   });
      // Canvas.viewCanvas.setHeight(600);
      // Canvas.viewCanvas.setWidth(500);

      updateModifications(true);  
    },
    fabricViewCanvas: function(href,text_code) { 
      Canvas.viewCanvas = new fabric.Canvas("viewCanvas", {
            hoverCursor: 'pointer',
            selection: true,
            selectionBorderColor: 'green',
            backgroundColor: "#ffffff"
        });
      Canvas.viewCanvas.setHeight(350);
      Canvas.viewCanvas.setWidth(500);

        var src = "/assets/images/persoanlisation/back-envelope-white.png";
        fabric.Image.fromURL(src, function(img) {
          var oImg = img.set({
            left: 30,
            top: 20,
            angle: 00
          }).scale(.77);
          Canvas.viewCanvas.add(oImg).renderAll();
        });

        fabric.Image.fromURL(href, function(img) {
          var oImg = img.set({
            left: 45,
            top: 30,
            angle: 00
          }).scale(1.4);
          Canvas.viewCanvas.add(oImg).renderAll();
        });

        /* Code */
        Canvas.viewCanvas.add(new fabric.IText('design id : ' + text_code, {
          fontFamily: 'Copperplate-Lig',
            fontSize: 18,
            fill: '#754729',
            left: 130,
            top: 320
        }));

        setTimeout(function(){ 
            var saveImage = Canvas.viewCanvas.toDataURL({
              format: 'png',
              quality: 0.8
            });

            $("#lnkDownload").attr("href",saveImage); 
            $("#lnkDownload").attr("download",'personalisation_' + text_code+ '.png');   
            $("#canvasImg").attr("src",saveImage); 
            $("#canvasImg").attr("alt",'personalisation_' + text_code);            
         }, 1000);
        setTimeout(function(){ 
          $("#lnkDownload").trigger( "click" ); 

          var file = Canvas.viewCanvas.toDataURL({
            format: 'png',
            quality: 0.8
          });

          var obj = {
            "bear_order_id":Canvas.bear_order_id,
            "png" :file
          };
          var data = JSON.stringify(obj);
          var form = new FormData();
          form.append("data", data);

          $.ajax({
              url: "/createorder/updateOrder",
              type: "POST",
              contentType:false,
              processData: false,
              cache: false,
              data: form,
              success: function(data){
                  var obj = CanvasAction.JsonParse(data);          
                  //console.log(obj);
              }
          });
        
        }, 1100); 
        $("#complete_modal").modal("show");

    },
    updateModifications: function(savehistory) { 
      if (savehistory === true) {
        //myjson = JSON.stringify(canvas);
        var myjson = Canvas.myCanvas.toJSON();
        Canvas.state.push(myjson);
      }
    }, 
    undo: function(e) {
     if (Canvas.mods < Canvas.state.length) {
          Canvas.myCanvas.clear().renderAll();
          Canvas.myCanvas.loadFromJSON(Canvas.state[Canvas.state.length - 1 - Canvas.mods - 1]);
          Canvas.myCanvas.renderAll();
          ////console.log("geladen " + (state.length-1-mods-1));
          ////console.log("state " + state.length);
          Canvas.mods += 1;
          ////console.log("mods " + mods);
      }
    },
    redo: function(e) {
      if (Canvas.mods > 0) {
          Canvas.myCanvas.clear().renderAll();
          Canvas.myCanvas.loadFromJSON(Canvas.state[Canvas.state.length - 1 - Canvas.mods + 1]);
          Canvas.myCanvas.renderAll();
          ////console.log("geladen " + (state.length-1-mods+1));
          Canvas.mods -= 1;
          ////console.log("state " + state.length);
          ////console.log("mods " + mods);
      }
    },
    selectnNextFrame: function(e) {
      $box = $(this).parents("#canvas-select");
      $input = $box.find(".select_design");
      var old = parseFloat($input.attr("current"));
      var mode = $(this).attr("mode");

      var min = parseFloat($input.attr("min"));
      var max = parseFloat($input.attr("max"));

      if (mode == "minus" && old > min) {
        $input.attr("current" , old-1);
        Canvas.selectFrame(old-1);
      }
      else if (mode == "plus" && old < max) {
        $input.attr("current" , old+1);
        Canvas.selectFrame(old+1);
      }else{
        $input.attr("current" , old);
      }

      $("#frame_category").hide();
          $("#frame_List").show();
          Canvas.updateModifications(true); 
    },
    selectnNextCat: function(e) {
      $box = $(this).parents("#canvas-select-category");
      $input = $box.find("#frame_category_name");
      var old = parseFloat($input.attr("current"));
      var mode = $(this).attr("mode");

      var min = parseFloat($input.attr("min"));
      var max = parseFloat($input.attr("max"));
      var num = 0;

      if (mode == "minus" && old > min) {
        $input.attr("current" , old-1);
        num = old-1;
      }
      else if (mode == "plus" && old < max) {
        $input.attr("current" , old+1);
        num = old+1;
      }else{
        $input.attr("current" , old);
        num = old;
      }

      var id = $("#frame_category_ui").find("li[data-num='"+num+"']").attr("data-id");
      var fin = $("#frame_category_ui").find("li[data-num='"+num+"']").attr("data-fin");
      var name = $("#frame_category_ui").find("li[data-num='"+num+"']").find("div").text();
      if(fin == undefined){
        fin = id;
      }
      $("#frame_category_name").text(name);

      $("#frame_category_mode").find("div").eq(0).find(".btn-link").attr("data-id",id);
      $("#frame_category_mode").find("div").eq(1).find(".btn-link").attr("data-id",fin);

      if(CanvasAction.frameCategoryMode == "CUSTOMISE DESIGNS"){
        CanvasAction.postData("/frame/frameList/",{"search" :{"frame_category_id":id}}).done(function (data) {
            var obj = CanvasAction.JsonParse(data);
            CanvasAction.renderFrameList(obj);
        });
      }
      else{
        CanvasAction.postData("/frame/frameList/",{"search" :{"frame_category_id":fin}}).done(function (data) {
            var obj = CanvasAction.JsonParse(data);
            CanvasAction.renderFrameList(obj);
        });
      }
      
    },
    selectNumber: function(e) {
      $box = $(this).parents(".number-box");
      $input = $box.find("input");
      var old = parseFloat($input.val());
      var mode = $(this).attr("mode");

      var min = parseFloat($input.attr("min"));
      var max = parseFloat($input.attr("max"));
      var number = 0.1;
      if($box.attr("mode") == "size"){
        number = 1;
      }

      if (mode == "minus" && old > min) {
        $input.val(old-number);
      }
      else if (mode == "plus" && old < max) {
        $input.val(old+number);
      }else{
        $input.val(old);
      }

      Canvas.setLineHeight();
      Canvas.setCharSpacing();
      Canvas.setFontSize();
      Canvas.updateModifications(true); 
    },
    complete: function(e) { 
      // //console.log('data:image/svg+xml;utf8,' + encodeURIComponent(Canvas.myCanvas.toSVG()));
      // //console.log('data:image/svg+xml;utf8,' + btoa(Canvas.myCanvas.toSVG()));

      var encodedString = btoa(unescape(encodeURIComponent(Canvas.myCanvas.toSVG())));
      var obj = {
        "frame_category_id" : Canvas.frame_category_id,
        "frame_list_id" : Canvas.frame_list_id,
        "frame_category_code" : Canvas.frame_category_code,
        "svg" : 'data:image/svg+xml;utf8,' + encodedString

      }
      var data = JSON.stringify(obj);
      var form = new FormData();
        form.append("data", data);

      $.ajax({
          url: "/createorder/genOrderNumber/",
          type: "POST",
          contentType:false,
          processData: false,
          cache: false,
          data: form,
          success: function(data){
              var obj = CanvasAction.JsonParse(data);          
              //console.log(obj);
    
              Canvas.bear_order_number = obj.result.bear_order_number ;
              Canvas.bear_order_id = obj.result.bear_order_id;
              var href = Canvas.myCanvas.toDataURL({
                format: 'png',
                quality: 0.8
              });
              Canvas.fabricViewCanvas(href , Canvas.bear_order_number);
          }
      });
    },
    resetCanvas: function() { 
      Canvas.myCanvas.clear();
      Canvas.state = [];
      Canvas.Addtext();
      Canvas.updateModifications(true); 
    },    
    setFontFamily: function(e) {
        Canvas.myCanvas.getActiveObject().set("fontFamily", $(this).val());
        Canvas.myCanvas.renderAll();
        Canvas.updateModifications(true); 
    },
    setLineHeight: function(e) {
        Canvas.myCanvas.getActiveObject().set("lineHeight", $("#text-line-height").val());
        Canvas.myCanvas.renderAll();
        Canvas.updateModifications(true); 
    },
    setCharSpacing: function(e) {
        var spacing =  parseFloat($("#text-space").val())*100;
        Canvas.myCanvas.getActiveObject().set("charSpacing",spacing);
        Canvas.myCanvas.renderAll();
        Canvas.updateModifications(true); 
    },
    setFontSize: function(e) {
        var size =  parseFloat($("#text-size").val());
        Canvas.myCanvas.getActiveObject().set("fontSize",size);
        Canvas.myCanvas.renderAll();
        Canvas.updateModifications(true); 
    },
    setTextAlign: function(e) {
      $("#text-align").find("i").removeClass("select");
      $(this).addClass("select");
      var mode = $(this).attr("mode");
        Canvas.myCanvas.getActiveObject().set("textAlign", mode);
        Canvas.myCanvas.renderAll();
        Canvas.updateModifications(true); 
    },
    Addtext: function(e){

      var topText = Math.floor(Math.random() * 150) + 10;

        Canvas.myCanvas.add(new fabric.IText('YOUR MESSAGE', {
          fontFamily: 'Copperplate-Lig',
            fontSize: 24,
            charSpacing: 100,
            lineHeight:1,
            stroke: '#999999',
            fill: '#ffffff',
            strokeWidth: 1,
            left: 25,
            top: topText
        }).setShadow({ color: 'rgba(0,0,0,0.3)' }));
        Canvas.myCanvas.renderAll();
        Canvas.myCanvas.forEachObject(function(o){ 
          o.setControlVisible('mt',false);
          o.setControlVisible('mb',false);
          o.setControlVisible('ml',false);
          o.setControlVisible('mr',false); 
          o.setControlVisible('tl',false);
          o.setControlVisible('tr',false);
          o.setControlVisible('bl',false);
          o.setControlVisible('br',false);
        });
        Canvas.updateModifications(true); 
    },
    addImage: function(e) {
        var file = e.target.files[0];
          var reader = new FileReader();
          reader.onload = function(f) {
            var data = f.target.result;
            fabric.Image.fromURL(data, function(img) {
              var oImg = img.set({
                left: 0,
                top: 0,
                angle: 00,
              }).scale(0.3);
              Canvas.myCanvas.add(oImg).renderAll();
              //var a = canvas.setActiveObject(oImg);
              var dataURL = canvas.toDataURL({
                format: 'png',
                quality: 1
              });
            });
          };
          reader.readAsDataURL(file);
          Canvas.updateModifications(true); 
    },
    delSelect: function(e) {
      if (Canvas.myCanvas.getActiveObject()) {          
        Canvas.myCanvas.remove(Canvas.myCanvas.getActiveObject());

        var myjson = Canvas.myCanvas.toJSON();
        Canvas.state.push(myjson);
      }
    },
    selectStep: function() {    
      var step = $(this).parents(".step");   
      var mode = $(this).attr("mode");   
      $(".step-head").removeClass("select");
      $(".step[mode=1]").find(".step-head[mode='"+mode+"']").addClass("select");
      if ($(".step[mode='"+mode+"']").hasClass("select")) {
        $(".step[mode='"+mode+"']").removeClass("select");
        $("#canvas-tool").removeClass("open");

        if(mode == 1){
          $("#frame_category").hide();
          $("#frame_List").hide();
        }
      }
      else{
        $(".step").removeClass("select");
        $("#canvas-tool").addClass("open");
        $(".step[mode='"+mode+"']").addClass("select");

        if(mode == 1){
          $("#frame_category").hide();
          $("#frame_List").show();
        }else{
          $("#frame_category").hide();
          $("#frame_List").hide();
        }
      }
    },
    selectFrame: function(e) {    
      if (e.type == "click") {
        $frame = $(this);
      }
      else{
        $frame = $('#frame-panel').find(".frame[num='"+e+"']");
      }
      var src = $frame.attr("data-src");
      var num = $frame.attr("num");
      var name = $frame.attr("name");

      var srcObj = $frame.find("img").attr("src");

      $("#canvas-select").find(".select_design").attr("current" , num);
      $("#canvas-select").find(".select_design").text(name);

      $('.frame').removeClass("select");
      if (!$frame.hasClass("btn-re")) {
        $frame.addClass("select");
      }
      else{
        $('#frame-panel').find(".frame").eq(0).addClass("select");
      }

      // $("#frame_category").hide();
      // $("#frame_List").hide();

      //console.log("src" ,src);
      if(src != null && src != "null"){
        fabric.loadSVGFromURL(src, function(objects, options) {
              var obj = fabric.util.groupSVGElements(objects, options);
              Canvas.myCanvas.setBackgroundImage(obj, Canvas.myCanvas.renderAll.bind(Canvas.myCanvas), {
                scaleX: parseFloat(Canvas.myCanvas.width) / parseFloat(obj.width),
                scaleY: parseFloat(Canvas.myCanvas.height) / parseFloat(obj.height),
            });
            //   canvas.add(obj).renderAll();
        });
      }
      else{
        Canvas.myCanvas.setBackgroundImage(0, Canvas.myCanvas.renderAll.bind(Canvas.myCanvas));
      }
      
      Canvas.updateModifications(true); 

      $('html, body').animate({
        scrollTop: $("#canvas-select").offset().top
      }, 1000);
    },
    selectIcon: function() {         
      // if($(this).attr("data-num") == 1){
        var src = $(this).attr("data-src");
        // var src = $(this).find("img").attr("src");
        //console.log(src);
        var topText = Math.floor(Math.random() * 120) + 10;
        var leftText = Math.floor(Math.random() * 220) + 5;

        var group = [];
        fabric.loadSVGFromURL(src,function(objects,options)
        {
          var loadedObjects = new fabric.Group(group);
          loadedObjects.set({
            left: leftText,
            top: topText
          }).scale(8);

          loadedObjects.scaleToWidth(54);
          loadedObjects.scaleToHeight(54);

          Canvas.myCanvas.add(loadedObjects);
          Canvas.myCanvas.renderAll();
          Canvas.myCanvas.forEachObject(function(o){ 
            o.setControlVisible('mt',false);
            o.setControlVisible('mb',false);
            o.setControlVisible('ml',false);
            o.setControlVisible('mr',false);
            o.setControlVisible('tl',false);
            o.setControlVisible('tr',false);
            o.setControlVisible('bl',false);
            o.setControlVisible('br',false);
          });
        },
        function(item, object) {
          object.set('id', item.getAttribute('id'));
          group.push(object);
        });      
        Canvas.updateModifications(true);
        $(this).attr("data-num",0);

        $('html, body').animate({
          scrollTop: $("#canvas-select").offset().top
        }, 1000);
      // }
      // else{
      //   $(this).attr("data-num",1);
      // }
         
    },
    selectIconOld: function() {           
      var src = $(this).find("img").attr("src");
      
      var imgElement = $(this).find("img");
      fabric.Image.fromURL(src, function(img) {
        var oImg = img.set({
          left: 50,
          top: 80,
          angle: 00,
        }).scale(1);
        Canvas.myCanvas.add(oImg).renderAll();
        Canvas.myCanvas.forEachObject(function(o){ 
          o.setControlVisible('mt',false),
          o.setControlVisible('mb',false);
          o.setControlVisible('ml',false),
          o.setControlVisible('mr',false);
        });
      });
      // Canvas.drawPhoto(src);

      
    }
};
Canvas.init();