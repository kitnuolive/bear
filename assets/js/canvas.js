
var Canvas = {
    myCanvas : null,
    viewCanvas : null,
    context : null,
    init: function() {
      this.bindEvents();
      this.fabricMyCanvas();
      // display/hide text controls
      Canvas.myCanvas.on('object:selected', function(e) {
          var step = $(".step[mode=2]");   
        if (e.target.type === 'i-text') {
              $(".step").removeClass("select");
            $("#canvas-tool").addClass("open");
            step.addClass("select");
        }
        else{
            // $('#textControls').hidden = true;
          step.removeClass("select");
          $(".step[mode=3]").addClass("select");
        }
      });
      Canvas.myCanvas.on('before:selection:cleared', function(e) {
          var step = $(".step[mode=2]");  
        if (e.target.type === 'i-text') {
          // $('#textControls').hidden = true;
          step.removeClass("select");
          $(".step[mode=3]").addClass("select");
        }
      });
       
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
      $('#text-align').on('click', 'i',this.setTextAlign);

      $(".number-box").on('click', 'i',this.selectNumber);
      $("#canvas-select").on('click', '.btn-select',this.selectnNextFrame);

      // $('#lnkDownload').on('click',this.complete);
      $('#complete_btn').on('click',this.complete);
      
    },
    fabricMyCanvas: function() { 
      // Canvas.myCanvas = new fabric.Canvas('myCanvas');
      Canvas.myCanvas = new fabric.Canvas("myCanvas", {
            hoverCursor: 'pointer',
            selection: true,
            selectionBorderColor: 'green',
            backgroundColor: null
        });
      Canvas.myCanvas.setHeight(265);
      Canvas.myCanvas.setWidth(412);

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
        o.setControlVisible('mt',false),
        o.setControlVisible('mb',false);
        o.setControlVisible('ml',false),
        o.setControlVisible('mr',false);
      });

      Canvas.viewCanvas = new fabric.Canvas("myCanvas", {
            hoverCursor: 'pointer',
            selection: true,
            selectionBorderColor: 'green',
            backgroundColor: null
        });
      Canvas.viewCanvas.setHeight(600);
      Canvas.viewCanvas.setWidth(500);
    },
    fabricViewCanvas: function(href,text_code) { 
      Canvas.viewCanvas = new fabric.Canvas("viewCanvas", {
            hoverCursor: 'pointer',
            selection: true,
            selectionBorderColor: 'green',
            backgroundColor: "#ffffff"
        });
      Canvas.viewCanvas.setHeight(600);
      Canvas.viewCanvas.setWidth(500);

        var src = "/assets/images/canvas_logo.png";
        fabric.Image.fromURL(src, function(img) {
          var oImg = img.set({
            left: 200,
            top: 30,
            angle: 00
          }).scale(1);
          Canvas.viewCanvas.add(oImg).renderAll();
        });
        Canvas.viewCanvas.add(new fabric.IText('personalisation', {
          fontFamily: 'Copperplate-Lig',
            fontSize: 18,
            fill: '#754729',
            left: 170,
            top: 80
        }));

        var src = "/assets/images/persoanlisation/back-envelope-white.png";
        fabric.Image.fromURL(src, function(img) {
          var oImg = img.set({
            left: 20,
            top: 120,
            angle: 00
          }).scale(1);
          Canvas.viewCanvas.add(oImg).renderAll();
        });

        Canvas.viewCanvas.add(new fabric.IText('beawelry ® 2018 ©', {
          fontFamily: 'Copperplate-Lig',
            fontSize: 12,
            fill: '#754729',
            left: 20,
            top: 570
        }));

        fabric.Image.fromURL(href, function(img) {
          var oImg = img.set({
            left: 30,
            top: 130,
            angle: 00
          }).scale(1);
          Canvas.viewCanvas.add(oImg).renderAll();
        });

        /* Code */
        Canvas.viewCanvas.add(new fabric.IText('code :' + text_code, {
          fontFamily: 'Copperplate-Lig',
            fontSize: 18,
            fill: '#754729',
            left: 150,
            top: 420
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
        setTimeout(function(){ $("#lnkDownload").trigger( "click" ); }, 1100); 

        $("#complete_modal").modal("show");

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
    },
    selectNumber: function(e) {
      $box = $(this).parents(".number-box");
      $input = $box.find("input");
      var old = parseFloat($input.val());
      var mode = $(this).attr("mode");

      var min = parseFloat($input.attr("min"));
      var max = parseFloat($input.attr("max"));

      if (mode == "minus" && old > min) {
        $input.val(old-1);
      }
      else if (mode == "plus" && old < max) {
        $input.val(old+1);
      }else{
        $input.val(old);
      }

      Canvas.setLineHeight();
      Canvas.setCharSpacing();
    },
    complete: function(e) { 
        var href = Canvas.myCanvas.toDataURL({
          format: 'png',
          quality: 0.8
        });
        Canvas.fabricViewCanvas(href , "df12000246");
    },
    resetCanvas: function() { 
      Canvas.myCanvas.clear();
      Canvas.Addtext();
    },    setFontFamily: function(e) {
        Canvas.myCanvas.getActiveObject().set("fontFamily", $(this).val());
        Canvas.myCanvas.renderAll();
    },
    setLineHeight: function(e) {
        Canvas.myCanvas.getActiveObject().set("lineHeight", $("#text-line-height").val());
        Canvas.myCanvas.renderAll();
    },
    setCharSpacing: function(e) {
        var spacing =  parseFloat($("#text-space").val())*100;
        Canvas.myCanvas.getActiveObject().set("charSpacing",spacing);
        Canvas.myCanvas.renderAll();
    },
    setTextAlign: function(e) {
      var mode = $(this).attr("mode");
        Canvas.myCanvas.getActiveObject().set("textAlign", mode);
        Canvas.myCanvas.renderAll();
    },
    Addtext: function(e){
        Canvas.myCanvas.add(new fabric.IText('TYPE YOUR MESSEAGE', {
          fontFamily: 'Copperplate-Lig',
            fontSize: 24,
            stroke: '#939393',
            fill: '#b3b3b3',
            strokeWidth: 1,
            left: 50,
             top: 50
        }));
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
    },
    delSelect: function(e) {
      if (Canvas.myCanvas.getActiveObject()) {          
        Canvas.myCanvas.remove(Canvas.myCanvas.getActiveObject());
      }
    },
    selectStep: function() {    
      var step = $(this).parents(".step");   
      var mode = step.attr("mode");   
      if (step.hasClass("select")) {
        step.removeClass("select");
        $("#canvas-tool").removeClass("open");
      }
      else{
        $(".step").removeClass("select");
        $("#canvas-tool").addClass("open");
        step.addClass("select");
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

        // fabric.Image.fromURL(src, function($img) { 
        //         $img.set({width: Canvas.myCanvas.width, height: Canvas.myCanvas.height, originX: 'left', originY: 'top'});
        //         Canvas.myCanvas.setBackgroundImage($img, Canvas.myCanvas.renderAll.bind(Canvas.myCanvas), {width: Canvas.myCanvas.width, height: Canvas.myCanvas.height, originX: 'left', originY: 'top'});

        // });

      fabric.Image.fromURL(src, function(img) {
        img.set({width: Canvas.myCanvas.width, height: Canvas.myCanvas.height, originX: 'left', originY: 'top'});
         // add background image
         Canvas.myCanvas.setBackgroundImage(img, Canvas.myCanvas.renderAll.bind(Canvas.myCanvas), {
            scaleX: parseFloat(Canvas.myCanvas.width) / parseFloat(img.width),
            scaleY: parseFloat(Canvas.myCanvas.height) / parseFloat(img.height),
         });
      });
    },
    selectIcon: function() {           
      var src = $(this).find("img").attr("src");
      
      var imgElement = $(this).find("img");
      fabric.Image.fromURL(src, function(img) {
          var oImg = img.set({
            left: 250,
            top: 50,
            angle: 00,
          }).scale(1);
          Canvas.myCanvas.add(oImg).renderAll();
        });
      // Canvas.drawPhoto(src);
    }
};
Canvas.init();