var Canvas = {
    canvas : null,
    context : null,
    init: function() {
      this.bindEvents();
      //this.fabric();
       
    },
    bindEvents: function() {         
      $('#canvas-tool').on('click', '.step-head',this.selectStep);
      $('#frame-panel').on('click', '.frame',this.selectFrame);
      $('#reset_btn').on('click',this.selectFrame);
      $('#complete_btn').on('click',this.genSVG);

      $('#sticker-panel').on('click', '.sticker',this.selectIcon);

      
    },
    fabric: function() { 
      var canvas = new fabric.Canvas('myCanvas');

          // var bgImg = new fabric.Image();
          // bgImg.setSrc('/assets/images/persoanlisation/SVG/WHITE-SVG-01.svg', function () {
          //   bgImg.set({
          //     top: 0,
          //     left: 0,
          //     scaleX: canvas.getWidth()/bgImg.width,
          //     scaleY: canvas.getHeight()/bgImg.height,
          //     backgroundImageOpacity: 1,
          //     backgroundImageStretch: true,
          //     originX: 'left',
          //     originY: 'top'
          //   });
          // });

          // canvas.setBackgroundImage(bgImg);
          var textbox = new fabric.Textbox('fill your message here.', {
              fontFamily: 'Copperplate-Lig',
                          fontSize: 32,
                          stroke: '#939393',
                          fill: '#b3b3b3',
                          strokeWidth: 1,
                          left: 50,
                           top: 50
            });
            canvas.add(textbox).setActiveObject(textbox);

            fabric.Image.fromURL('/assets/images/persoanlisation/SVG/icon02.png', function(myImg) {
             //i create an extra var for to change some image properties
             var img1 = myImg.set({ left: 250, top: 100 ,width:65,height:65});
             canvas.add(img1); 
            });

          
          // canvas.add(new fabric.Rect({ left: 110, top: 110, fill: '#f0f', width: 50, height: 50 }));
          // canvas.add(new fabric.Rect({ left: 50, top: 50, fill: '#77f', width: 40, height: 40 }));

          canvas.forEachObject(function(o){ 
            o.hasBorders = true,
            o.hasControls = true,
            o.transparentCorners = false,
            o.borderColor = '#754729',
            o.cornerColor = '#754729',
            o.cornerSize =  8,
            o.setControlVisible('mt',false),
            o.setControlVisible('mb',false);
          });

          canvas.hoverCursor = 'pointer';

          function animate(e, dir) {
            if (e.target) {
              fabric.util.animate({
                startValue: e.target.get('angle'),
                endValue: e.target.get('angle') + (dir ? 5 : -5),
                duration: 100,
                onChange: function(value) {
                  e.target.setAngle(value);
                  canvas.renderAll();
                },
                onComplete: function() {
                  e.target.setCoords();
                }
              });
              fabric.util.animate({
                startValue: e.target.get('scaleX'),
                endValue: e.target.get('scaleX') + (dir ? 0.02 : -0.02),
                duration: 100,
                onChange: function(value) {
                  e.target.scale(value);
                  canvas.renderAll();
                },
                onComplete: function() {
                  e.target.setCoords();
                }
              });
            }
          }
          canvas.on('mouse:down', function(e) { animate(e, 1); });
          canvas.on('mouse:up', function(e) { animate(e, 0); });
          this.__canvases.push(canvas);
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
    selectFrame: function() {           
      var src = $(this).attr("data-src");
      $('.frame').removeClass("select");
      if (!$(this).hasClass("btn-re")) {
        $(this).addClass("select");
      }
      else{
        $('#frame-panel').find(".frame").eq(0).addClass("select");
      }
      var bgImg = new fabric.Image();
          bgImg.setSrc(src, function () {
            bgImg.set({
              top: 0,
              left: 0,
              scaleX: canvas.getWidth()/bgImg.width,
              scaleY: canvas.getHeight()/bgImg.height,
              backgroundImageOpacity: 1,
              backgroundImageStretch: true,
              originX: 'left',
              originY: 'top'
            });
          });

          canvas.setBackgroundImage(bgImg);
      // Canvas.drawPhoto(src);
    },
    selectIcon: function() {           
      var src = $(this).find("img").attr("src");
      
      var imgElement = $(this).find("img");
      var imgInstance = new fabric.Image(imgElement, {
        left: 100,
        top: 100,
      });
      canvas.add(imgInstance);
      // Canvas.drawPhoto(src);
    },
    drawPhoto: function(photo) {
      canvas  = document.getElementById('myCanvas');
      Canvas.context = canvas.getContext('2d');
     
      Canvas.context.clearRect(0, 0, canvas.width, canvas.height);
      document.getElementById('canvasImg').src = "";
      if(photo != null){
        var imageObj = new Image();
        imageObj.onload = function() {
          Canvas.context.drawImage(imageObj, 0, 0);
        };
        imageObj.src = photo;
      }
    },
    control: function(){
      var canvas = canvas = new fabric.Canvas('c');

        var rect = new fabric.Rect({
          left: 150,
          top: 200,
          originX: 'left',
          originY: 'top',
          width: 150,
          height: 120,
          angle: -10,
          fill: 'rgba(255,0,0,0.5)',
          transparentCorners: false
        });

        canvas.add(rect).setActiveObject(rect);

        canvas.item(0)["hasControls"] = true;
        canvas.item(0)["hasBorders"] = true;
        canvas.item(0)["hasRotatingPoint"] = true;
        canvas.item(0)["visible"] = true;
        canvas.item(0)["selectable"] = true;
        canvas.item(0)["evented"] = true;

        canvas.item(0)["borderColor"] = "#000000";
        canvas.item(0)["cornerColor"] = "#000000";
        canvas.item(0)["cornerStrokeColor"] = "#000000";
        canvas.item(0)["cornerStyle1"] = "circle";
        canvas.item(0)["setControlVisible"]("tl",true);
        canvas.item(0)["setControlVisible"]("ml",false);
        canvas.item(0)["setControlVisible"]("bl",true);
        canvas.item(0)["setControlVisible"]("mb",false);
        canvas.item(0)["setControlVisible"]("br",true);
        canvas.item(0)["setControlVisible"]("mr",false);
        canvas.item(0)["setControlVisible"]("tr",true);
        canvas.item(0)["setControlVisible"]("mt",false);
        canvas.item(0)["setControlVisible"]("mtr",true);
      },
    addIcon: function(Icon) {
      canvas  = document.getElementById('myCanvas');
      Canvas.context = canvas.getContext('2d');
      var imageObj = new Image();
      var imageIcon = new Image();

      imageObj.onload = function() {
        Canvas.context.drawImage(imageObj, 0, 0);
      };
      imageObj.src = '/assets/images/persoanlisation/SVG/WHITE-SVG-01.svg';


      imageIcon.onload = function() {
        Canvas.context.drawImage(imageIcon, 200, 100,50,50);
      };
      imageIcon.src = '/assets/images/persoanlisation/SVG/icon.svg';


      Canvas.context.font = 'italic 30pt Calibri,ansana';

      Canvas.context.fillStyle = '#b3b3b3';
      Canvas.context.lineWidth = 2;
      // stroke color
      Canvas.context.strokeStyle = '#939393';
      Canvas.context.fillText('Hello World!', 70, 90);
      Canvas.context.strokeText('Hello World!', 70, 90);
    },
    dragandDrop: function(){
      var width = 421;
      var height = 265;

      function update(activeAnchor) {
          var group = activeAnchor.getParent();

          var topLeft = group.get('.topLeft')[0];
          var topRight = group.get('.topRight')[0];
          var bottomRight = group.get('.bottomRight')[0];
          var bottomLeft = group.get('.bottomLeft')[0];
          var image = group.get('Image')[0];

          var anchorX = activeAnchor.getX();
          var anchorY = activeAnchor.getY();

          // update anchor positions
          switch (activeAnchor.getName()) {
              case 'topLeft':
                  topRight.setY(anchorY);
                  bottomLeft.setX(anchorX);
                  break;
              case 'topRight':
                  topLeft.setY(anchorY);
                  bottomRight.setX(anchorX);
                  break;
              case 'bottomRight':
                  bottomLeft.setY(anchorY);
                  topRight.setX(anchorX);
                  break;
              case 'bottomLeft':
                  bottomRight.setY(anchorY);
                  topLeft.setX(anchorX);
                  break;
          }

          image.position(topLeft.position());

          var width = topRight.getX() - topLeft.getX();
          var height = bottomLeft.getY() - topLeft.getY();
          if(width && height) {
              image.width(width);
              image.height(height);
          }
      }
      function addAnchor(group, x, y, name) {
          var stage = group.getStage();
          var layer = group.getLayer();

          var anchor = new Konva.Circle({
              x: x,
              y: y,
              stroke: '#666',
              fill: '#ddd',
              strokeWidth: 2,
              radius: 8,
              name: name,
              draggable: true,
              dragOnTop: false
          });

          anchor.on('dragmove', function() {
              update(this);
              layer.draw();
          });
          anchor.on('mousedown touchstart', function() {
              group.setDraggable(false);
              this.moveToTop();
          });
          anchor.on('dragend', function() {
              group.setDraggable(true);
              layer.draw();
          });
          // add hover styling
          anchor.on('mouseover', function() {
              var layer = this.getLayer();
              document.body.style.cursor = 'pointer';
              this.setStrokeWidth(4);
              layer.draw();
          });
          anchor.on('mouseout', function() {
              var layer = this.getLayer();
              document.body.style.cursor = 'default';
              this.setStrokeWidth(2);
              layer.draw();
          });

          group.add(anchor);
      }

      var stage = new Konva.Stage({
          container: 'myCanvas',
          width: width,
          height: height
      });

      var layer = new Konva.Layer();
      stage.add(layer);

      // darth vader
      var darthVaderImg = new Konva.Image({
          width: 200,
          height: 137
      });

      // yoda
      var yodaImg = new Konva.Image({
          width: 93,
          height: 104
      });

      var darthVaderGroup = new Konva.Group({
          x: 180,
          y: 50,
          draggable: true
      });
      layer.add(darthVaderGroup);
      darthVaderGroup.add(darthVaderImg);
      addAnchor(darthVaderGroup, 0, 0, 'topLeft');
      addAnchor(darthVaderGroup, 200, 0, 'topRight');
      addAnchor(darthVaderGroup, 200, 138, 'bottomRight');
      addAnchor(darthVaderGroup, 0, 138, 'bottomLeft');

      var yodaGroup = new Konva.Group({
          x: 20,
          y: 110,
          draggable: true
      });
      layer.add(yodaGroup);
      yodaGroup.add(yodaImg);
      addAnchor(yodaGroup, 0, 0, 'topLeft');
      addAnchor(yodaGroup, 93, 0, 'topRight');
      addAnchor(yodaGroup, 93, 104, 'bottomRight');
      addAnchor(yodaGroup, 0, 104, 'bottomLeft');

      var imageObj1 = new Image();
      imageObj1.onload = function() {
          darthVaderImg.image(imageObj1);
          layer.draw();
      };
      imageObj1.src = '/assets/images/persoanlisation/SVG/icon.svg';

      var imageObj2 = new Image();
      imageObj2.onload = function() {
          yodaImg.image(imageObj2);
          layer.draw();
      };
      imageObj2.src = '/assets/images/persoanlisation/SVG/icon.svg';
    },
    genSVG: function(){
        var dataURL = canvas.toDataURL("image/png");
        console.log(dataURL);

        // set canvasImg image src to dataURL
        // so it can be saved as an image
        document.getElementById('canvasImg').src = dataURL;
        var image = dataURL.replace("image/png", "image/octet-stream");
        // window.location.href=image;

         $("svg").attr({ version: '1.1' , xmlns:"http://www.w3.org/2000/svg"});

       var svg = $("#myCanvas").html();
       var b64 = Base64.encode(svg); // or use btoa if supported

       // Works in recent Webkit(Chrome)
       $("body").append($("<img src='data:image/svg+xml;base64,\n"+b64+"' alt='file.svg'/>"));

       // Works in Firefox 3.6 and Webit and possibly any browser which supports the data-uri
       $("body").append($("<a href-lang='image/svg+xml' href='data:image/svg+xml;base64,\n"+b64+"' title='file.svg'>Download</a>"));
          }
};
Canvas.init();