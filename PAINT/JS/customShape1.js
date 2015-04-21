var canvas='';
//canvas.setWidth("780");
//canvas.setHeight("640");
//document.getElementById('canvas').style.maxWidth="100%";
//document.getElementById('canvas').style.maxHeight="100%";
//document.getElementsByClassName('canvas-container')[0].style.maxWidth='100%';
//document.getElementsByClassName('canvas-container')[0].style.maxHeight='100%';
//document.getElementsByClassName('upper-canvas')[0].style.maxWidth='100%';
//document.getElementsByClassName('upper-canvas')[0].style.maxHeight='100%';
//var mode, color = '#36bac9', fillColor = false, strokeWidth = 5, fontfamily = 'Times New Roman', fontunderline = null, fontsize = 5, fontbold = null, fontitalic = null, fontwidth = 20;
var mode, color = '#36bac9', fillColor = false, strokeWidth = 5, fontfamily = 'Arial', fontunderline = null, fontsize = 5, fontbold = null, fontitalic = null, fontwidth = 20;
var defaultsize=10;
var obj_top = 100;
var obj_left = 100;
bgcolor = '#F5F5F5';
$('#drawing-color').change(function () {
    color = this.value;
    canvas.freeDrawingBrush.color = this.value;
    if (mode != 'line')
        pencil();
});
//$('#canvas').click(function (e) {
//    alert("testcanvas")
////        alert(canvas.getActiveObject())
//
//});
//function canvasonlick()
//{
//    alert(5)
//}
$(document).on('click','#canvas',function(e){
});
//canvas.on('mouse:down', function (o) {
//    alert(3)
//});
//
//canvas.on('click', function() {
//alert(1)
////$h1.html('You pushed nothing.');
//});
$('#drawing-line').click(function () {
    mode = 'line';
    fillColor = false;
    canvas.isDrawingMode = false;
});
//$('#drawing-line-width').keyup(function () {
    $(document).on('change keyup','#drawing-line-width',function(e){

//        e.preventDefault();
//    alert(this.value)
//    alert(canvas.getActiveObject())
//    alert(canvas.getActiveObject().get("type"))

        var imagetype=canvas.getActiveObject().get("type");


        if(canvas.getActiveObject()!=undefined&&canvas.getActiveObject()!=null)
    {
        var canvasobject=canvas.getActiveObject().get("id");
        obj_top=canvas.getActiveObject().get("top");
        obj_left=canvas.getActiveObject().get("left");


        canvas.remove(canvas.getActiveObject());

        if(this.value!=""&&this.value!=NaN&&this.value!=undefined){
            defaultsize=parseInt(this.value);
        }
        else{
            defaultsize=10;
        }
        if(imagetype.match("triangle"))
        {
//            size=size+30;
//            size=size;
            triangle()
//            canvas.getActiveObject().set('radius',defaultsize );
        }
        else if(imagetype.match("circle"))
        {
//            size=size+30;
//            size=size;
            circle();
//            canvas.getActiveObject().set('radius',defaultsize );
        }
        else if(imagetype.match("ellipse"))
        {
            eclipse();
//            canvas.getActiveObject().set('rx',defaultsize );
//            canvas.getActiveObject().set('ry',defaultsize );
//            canvas.getActiveObject().set('strokeWidth',defaultsize );
        }
        else if(imagetype.match("image"))
        {
            defaultsize=defaultsize;
            if(canvasobject.match("endCap1()"))
            {
            endCap1()
            }
            if(canvasobject.match("tappingTee1()"))
            {
                tappingTee1();
            }
            if(canvasobject.match("tJoint1()"))
            {
                tJoint1();
            }
            if(canvasobject.match("stubBlang1()"))
            {
                stubBlang1();
            }
            if(canvasobject.match("reducer1()"))
            {
                reducer1();
            }



//            canvas.getActiveObject().set('width', defaultsize);
//            canvas.getActiveObject().set('height', defaultsize);
//            canvas.getActiveObject().set('strokeWidth', defaultsize);

        }
        else if(imagetype.match("line"))
        {
            canvas.getActiveObject().set('strokeWidth', defaultsize);
        }
        else if(imagetype.match("rect"))
        {
            rectangle();
//            canvas.getActiveObject().set('strokeWidth', defaultsize);
        }
        else
        //if(imagetype.match("image")||imagetype.match("rect"))
        {
//            canvas.remove(canvas.getActiveObject());
            rectangle();
//            alert("inside")
//            var imagwidth=100+size;
//            var imagwidth=defaultsize;
//
////            alert(imagwidth)
////            var imagheight=100+size;
//            var imagheight=defaultsize;
//
////            alert(imagheight)
//        canvas.getActiveObject().set('width', imagwidth);
//        canvas.getActiveObject().set('height', imagheight);
//            canvas.getActiveObject().set('top', obj_top);
//            canvas.getActiveObject().set('left', obj_left);
//            canvas.getActiveObject().set('strokeWidth',defaultsize );
        }
//        else
//        {
//
////        canvas.getActiveObject().set('width', parseInt(this.value));
////        canvas.getActiveObject().set('height', parseInt(this.value));
////        canvas.getActiveObject().set('strokeWidth', parseInt(this.value));
//        canvas.getActiveObject().set('strokeWidth', strokeWidth);
//        }

    }

//

//    canvas.getActiveObject().str.get.strokeWidth=this.value
    strokeWidth = 5;//parseInt(canvas.getActiveObject().get('strokeWidth'), 10) || 1;
//    alert("after"+ canvas.getActiveObject().get('strokeWidth'))
    fontwidth = defaultsize;//strokeWidth;
//    alert("final")
//    if (mode != 'line')
//        pencil();
}); 

function onObjectSelected(e)
{
//    alert("testojbect")
    if (fillColor == true) {
        e.target.setFill(color);
        fillColor = false;
    }
};

function handleRemove() {
    canvas.clear().renderAll();
}
function updateImage(c) {
    fabric.Image.fromURL(c,
            function (oImg) {
                canvas.add(oImg);
            });
}

var line, isDown;
function drawLine() {
//     canvas.selectable = false;
     canvas.forEachObject(function(o){ o.hasBorders = o.hasControls = false;o.selectable=false; });
//    canvas.deactivateAllWithDispatch().renderAll();
        mode = 'line';
    fillColor = false;
    canvas.isDrawingMode = false;  

}
function circle() {
//    obj_left=50;
//    obj_top=10;
    fillColor = false;
    canvas.isDrawingMode = false;
    mode = 'circle';
    canvas.add(new fabric.Circle({
        left: obj_left,
        top: obj_top,
        radius: defaultsize,
        fill: 'transparent',
        stroke: color,
        strokeWidth: strokeWidth,
        "hasControls": true,
        "selectable": true
    }));
    selector()
}
function textEditor1() {
    mode = 'text';
    fillColor = false;
    canvas.isDrawingMode = false;
    canvas.add(new fabric.IText('TEXT', {
        fontFamily: fontfamily,
        left: 150,
        top: 50,
        textID: "SommeID",
        fontSize: fontwidth,
        fill: color,
        fontWeight: fontbold,
        fontStyle: fontitalic,
        backgroundColor: 'transparent',
        textDecoration: fontunderline
    }));
    selector()
}
function cut() {
    canvas.remove(canvas.getActiveObject());
}
function bold() {
    if (document.getElementById("fontbold").classList.contains("a-img-btn-font-rem")) {
        fontbold = 'bold';
        document.getElementById("fontbold").classList.remove("a-img-btn-font-rem");
        document.getElementById("fontbold").className += " " + "a-img-btn-font";
    }
    else if (document.getElementById("fontbold").classList.contains("a-img-btn-font")) {
        document.getElementById("fontbold").classList.remove("a-img-btn-font");
        document.getElementById("fontbold").className += " " + "a-img-btn-font-rem";
        fontbold = null;
    }
}
function italic() {
    if (document.getElementById("fontitalic").classList.contains("a-img-btn-font-rem")) {
        fontitalic = 'italic';
        document.getElementById("fontitalic").classList.remove("a-img-btn-font-rem");
        document.getElementById("fontitalic").className += " " + "a-img-btn-font";
    }
    else if (document.getElementById("fontitalic").classList.contains("a-img-btn-font")) {
        document.getElementById("fontitalic").classList.remove("a-img-btn-font");
        document.getElementById("fontitalic").className += " " + "a-img-btn-font-rem";
        fontitalic = null;
    }
    fontitalic = 'italic';
}
function underline() {
    if (document.getElementById("fontunderline").classList.contains("a-img-btn-font-rem")) {
        fontunderline = 'underline';
        document.getElementById("fontunderline").classList.remove("a-img-btn-font-rem");
        document.getElementById("fontunderline").className += " " + "a-img-btn-font";
    }
    else if (document.getElementById("fontunderline").classList.contains("a-img-btn-font")) {
        document.getElementById("fontunderline").classList.remove("a-img-btn-font");
        document.getElementById("fontunderline").className += " " + "a-img-btn-font-rem";
        fontunderline = null;
    }
}
//$('#font-family').change(function () {
//    fontfamily = this.value;
//});
function textEditor() {
    mode = 'text';
    fillColor = false;
    canvas.isDrawingMode = false;
    canvas.add(new fabric.IText('dfasdf', {
        fontFamily: 'arial black',
        left: 100,
        top: 100
        , textID: "SommeID",
        fontSize: 18
    }));
    selector()
}
function triangle() {
    mode = 'triangle';
    fillColor = false;
    canvas.isDrawingMode = false;
    var shape = new fabric.Triangle({
        left: obj_left,
        top: obj_top,
        width: defaultsize,
        height: defaultsize,
        fill: 'transparent',
        stroke: color,
        strokeWidth: strokeWidth
    });
    canvas.add(shape);
    selector()
}
function eclipse() {
    mode = 'eclipse';
    fillColor = false;
    canvas.isDrawingMode = false;
    var myEllipse = new fabric.Ellipse({
        top: obj_top,
        left: obj_left,
        rx: defaultsize,
        ry: defaultsize,
        fill: 'transparent',
        stroke: color,
        strokeWidth: strokeWidth
    });
    canvas.add(myEllipse);
    selector()
}
drawingColorEl.onchange = function () {
    canvas.freeDrawingBrush.color = this.value;
};
function save() {
    canvas.deactivateAll().renderAll();
    var dataURL = canvas.toDataURL();
    $.ajax({
        type: 'POST',
        data: {'form': dataURL, 'type': "upload"},
        url: "saveimage.php",
        success: function (data) {
            alert('Your comment was successfully added');
        },
        error: function (data) {
            alert('There was an error adding your comment');
        }
    });
}
//coding for pencil
function pencil() {
    canvas.isDrawingMode = true;
    canvas.freeDrawingBrush.width = strokeWidth;
    canvas.freeDrawingBrush.color = color;
    mode = 'pencil';
//    selector()
}
function rectangle() {

//    obj_top=parseInt(50);
//    obj_left=parseInt(100);
    mode = 'rectangle';
    fillColor = false;
    canvas.isDrawingMode = false;
    var rect = new fabric.Rect({
        left: obj_left,
        top: obj_top,
        width: defaultsize,
        height: defaultsize,
        stroke: color,
        fill: 'transparent',
        strokeWidth: strokeWidth,
        selectable:true,
        hasControls: true
    });
    canvas.add(rect);
    selector()
}
function setColor() {
    canvas.isDrawingMode = false;
    fillColor = true;
    canvas.deactivateAllWithDispatch().renderAll()
}
function clearCanvas() {
    canvas.clear();
}
function selector() {
    mode='selector';
    canvas.isDrawingMode = false;
     canvas.forEachObject(function(o){ o.hasBorders = o.hasControls = true;o.selectable=true; });
}
function tappingTee1() {
    canvas.isDrawingMode = false;
    var svg, jsonCanvas;
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M1744 4377 c-2 -7 -3 -69 -2 -138 l3 -124 269 -3 c178 -1 274 -6 283 -13 11 -9 13 -216 13 -1163 0 -707 -4 -1157 -10 -1166 -8 -13 -54 -16 -282 -20 l-273 -5 0 -135 0 -135 705 0 705 0 3 137 3 138 -264 0 c-211 0 -267 3 -283 14 -18 14 -19 31 -19 505 0 389 3 493 13 503 10 10 140 14 615 18 l602 5 0 140 0 140 -607 5 c-333 3 -610 9 -615 13 -4 5 -9 223 -11 485 -1 372 1 483 11 502 l13 25 269 5 270 5 0 135 0 135 -703 3 c-572 2 -704 0 -708 -11z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    id:"tappingTee1()",
                    width:50+parseInt(defaultsize),
                    height:50+parseInt(defaultsize),
                    left: obj_left,
                    top: obj_top,
                    strokeWidth: strokeWidth
                });
                canvas.add(oImg);
            });
    selector()
}
function tJoint1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M2106 3474 c-8 -20 -8 -248 0 -268 5 -14 41 -16 293 -16 232 0 291 -3 307 -14 18 -14 19 -33 22 -537 2 -457 1 -525 -13 -545 -15 -24 -16 -24 -220 -24 -192 0 -205 -1 -215 -19 -16 -30 -13 -266 3 -280 16 -13 1154 -15 1175 -2 9 6 12 45 10 152 l-3 144 -207 3 c-186 2 -208 4 -217 20 -5 10 -9 233 -9 542 0 464 1 527 15 543 15 15 42 17 284 17 258 0 269 1 279 20 6 12 9 71 8 147 l-3 128 -751 3 c-679 2 -752 1 -758 -14z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    id:"tJoint1()",
                    width:50+ defaultsize,
                    height:50+ defaultsize,
                    left: obj_left,
                    top: obj_top
                });
                canvas.add(oImg);
            });
    selector()
}
function stubBlang1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M2757 4603 c-4 -3 -7 -561 -7 -1238 0 -1181 -1 -1233 -18 -1248 -16 -15 -53 -17 -315 -17 -163 0 -298 -4 -301 -8 -6 -11 -10 -291 -4 -297 9 -10 1540 -7 1549 2 5 5 8 74 7 154 l-3 144 -292 5 c-192 3 -296 9 -303 16 -8 8 -11 362 -11 1239 1 886 -2 1232 -10 1242 -9 10 -46 13 -149 13 -75 0 -140 -3 -143 -7z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    id:"stubBlang1()",
                    width:50+ defaultsize,
                    height:50+ defaultsize,
                    left: obj_left,
                    top: obj_top
                });
                canvas.add(oImg);
            });
    selector()
}
function reducer1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M2236 4164 c-13 -33 -6 -299 9 -374 9 -41 22 -113 29 -160 7 -47 23 -128 35 -180 11 -52 25 -124 30 -160 6 -36 19 -105 29 -155 11 49 23 -112 27 -140 4 -27 15 -86 26 -130 10 -44 23 -109 28 -145 5 -36 21 -117 34 -180 33 -154 39 -202 27 -220 -7 -12 -39 -16 -142 -20 l-133 -5 -3 -148 -3 -147 768 2 768 3 0 145 0 145 -132 5 c-159 6 -172 13 -155 82 6 24 20 93 31 153 12 61 30 160 42 220 11 61 30 160 40 220 11 61 26 137 34 170 7 33 21 107 30 165 9 58 22 128 30 155 7 28 16 75 20 105 7 61 40 227 55 280 11 38 14 288 4 314 -6 14 -82 16 -764 16 -682 0 -758 -2 -764 -16z m1174 -307 c17 -9 24 -20 23 -37 -3 -38 -49 -284 -61 -321 -6 -19 -13 -59 -17 -89 -3 -30 -17 -109 -30 -175 -37 -181 -75 -381 -90 -465 -7 -41 -20 -111 -29 -155 -9 -44 -24 -124 -34 -178 -11 -58 -25 -105 -36 -115 -13 -13 -39 -18 -128 -20 -164 -5 -158 -11 -197 203 -11 61 -30 157 -41 215 -11 58 -36 188 -55 290 -20 102 -47 241 -60 310 -14 69 -29 150 -34 180 -5 30 -21 113 -35 183 -27 131 -26 173 2 180 10 3 193 5 407 6 299 0 396 -2 415 -12z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    id:"reducer1()",
                    width:50+ defaultsize,
                    height:50+ defaultsize,
                    left: obj_left,
                    top: obj_top
                });
                canvas.add(oImg);
            });
    selector()
}
function lastDegelbow1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M2224 4885 c-10 -7 -14 -47 -15 -152 0 -78 2 -147 6 -152 4 -7 103 -11 299 -11 271 0 294 -1 309 -18 15 -16 17 -51 16 -288 0 -148 -4 -277 -7 -286 -4 -10 -169 -182 -367 -384 -198 -201 -362 -368 -364 -370 -3 -2 163 -172 367 -377 l372 -373 0 -240 c0 -201 -3 -243 -16 -262 -15 -22 -17 -22 -309 -22 -197 0 -296 -4 -300 -11 -7 -11 -7 -290 0 -297 2 -2 356 -4 786 -4 650 0 783 2 790 13 14 22 11 273 -3 287 -9 9 -90 12 -304 12 -259 0 -293 2 -305 17 -11 13 -14 80 -15 327 l-2 312 -296 294 c-230 229 -296 301 -296 320 0 19 65 90 296 320 l295 295 -1 341 c0 208 3 351 9 367 l11 27 294 0 c254 0 297 2 310 16 13 13 16 42 16 156 0 127 -2 140 -18 145 -38 10 -1545 8 -1558 -2z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
    selector()
}
function halfDegelbow1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M3015 4207 c-49 -48 -85 -92 -85 -103 0 -12 70 -88 180 -197 99 -98 180 -186 180 -195 0 -10 -148 -165 -330 -346 l-330 -329 0 -528 c0 -512 -1 -529 -19 -539 -12 -6 -116 -10 -269 -10 -201 0 -252 -3 -261 -14 -8 -9 -11 -55 -9 -137 l3 -124 691 -3 691 -2 5 27 c3 16 2 78 -1 138 l-6 110 -261 3 c-221 2 -264 5 -275 18 -8 10 -12 45 -12 102 1 48 2 258 2 467 l1 379 287 288 c172 172 295 288 306 288 11 0 95 -75 198 -177 107 -106 188 -178 199 -178 11 0 57 38 104 84 67 65 83 87 76 100 -15 28 -949 956 -965 959 -9 2 -52 -33 -100 -81z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
    selector()
}
function fullDegelbow1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M3904 4445 c-11 -8 -14 -74 -16 -315 l-3 -305 -765 -5 -765 -5 -5 -776 c-4 -637 -7 -779 -19 -792 -12 -15 -48 -17 -332 -17 -293 0 -319 -1 -327 -17 -5 -10 -9 -88 -8 -173 l1 -155 844 -3 c624 -1 847 1 858 9 11 9 13 45 11 173 l-3 161 -333 5 c-281 4 -334 7 -342 20 -6 9 -10 250 -10 601 0 555 1 587 18 602 17 15 72 17 581 17 368 0 569 -4 582 -10 18 -10 19 -25 19 -348 0 -250 3 -341 12 -350 13 -13 291 -18 322 -6 14 6 16 89 16 845 0 728 -2 840 -15 845 -26 10 -308 9 -321 -1z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
    selector()
}
function equalTee1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M1784 4589 c-11 -18 -13 -232 -2 -251 8 -16 31 -18 273 -18 223 0 266 -2 279 -15 13 -14 15 -150 15 -1185 0 -1035 -2 -1171 -15 -1185 -13 -13 -56 -15 -279 -15 -242 0 -265 -1 -273 -17 -10 -21 -8 -236 2 -252 11 -16 1408 -15 1424 1 8 8 12 51 12 128 0 77 -4 120 -12 128 -9 9 -83 12 -273 12 -194 0 -266 3 -278 13 -16 11 -18 50 -17 511 0 350 4 503 11 513 9 10 111 13 538 13 314 0 532 -4 541 -10 13 -8 15 -49 16 -262 1 -139 2 -263 3 -276 1 -22 1 -23 144 -20 l142 3 3 704 c1 512 -1 708 -9 717 -9 11 -45 14 -145 14 -121 0 -134 -2 -135 -17 -1 -10 -2 -134 -3 -276 -1 -243 -2 -259 -20 -273 -17 -12 -106 -14 -537 -14 -457 0 -519 2 -533 16 -14 14 -16 75 -16 519 0 392 3 506 13 512 6 4 136 10 287 13 l275 5 3 115 c1 63 0 125 -3 138 l-5 22 -710 0 c-523 0 -711 -3 -716 -11z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
    selector()
}
function endCap1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="258.000000pt" height="129.000000pt" viewBox="0 0 258.000000 129.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,129.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M30 1046 c0 -153 -4 -247 -10 -251 -7 -4 -7 -12 0 -25 6 -11 10 -157 10 -379 l0 -361 238 0 c130 0 247 -3 260 -6 l22 -6 0 369 c0 274 3 372 12 381 9 9 183 12 720 12 l708 0 11 -27 c7 -18 10 -151 8 -373 l-2 -345 255 -3 255 -2 5 486 c3 267 3 551 0 630 l-5 144 -1243 0 -1244 0 0 -244z"/></g></svg>';

//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
//        d="M1558 4550 c-23 -6 -23 -6 -28 -220 -3 -118 -3 -361 0 -540 l5 -325 214 -3 c165 -2 216 1 223 10 4 7 8 147 7 311 0 215 3 303 12 313 17 20 1221 21 1238 1 7 -9 11 -110 11 -312 1 -198 5 -303 12 -312 8 -10 61 -13 219 -13 l209 0 2 303 c5 728 4 780 -12 784 -23 6 2086 9 -2112 3z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    id:"endCap1()",
                    width: defaultsize,//100 + parseInt(strokeWidth),
                    height: defaultsize,//100 + parseInt(strokeWidth),
                    left: obj_left,
                    top: obj_top,
                    strokeWidth: strokeWidth
                });
                canvas.add(oImg);
            });
    selector()
}
function diTee1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M2013 5058 c-35 -35 -69 -71 -75 -82 -9 -16 27 -56 288 -317 l299 -299 3 -1008 c1 -554 0 -1022 -3 -1041 -5 -27 -59 -86 -301 -326 -162 -161 -294 -297 -294 -301 0 -14 143 -154 157 -154 7 0 124 111 260 248 226 225 286 282 304 282 4 0 125 -119 270 -265 145 -146 269 -265 275 -265 20 0 154 131 154 150 0 10 -126 144 -295 312 l-295 294 0 400 c0 298 3 403 12 412 9 9 126 12 466 12 l454 0 301 -300 c165 -165 303 -300 307 -300 3 0 41 36 85 80 l80 80 -267 266 c-192 191 -268 273 -268 289 0 12 11 33 24 46 12 13 129 129 260 258 130 129 236 242 236 251 0 16 -146 167 -154 159 -3 -2 -138 -137 -301 -299 l-296 -295 -443 -3 c-280 -2 -453 1 -470 7 l-26 11 0 499 0 499 295 295 c162 162 295 303 295 313 0 19 -132 154 -151 154 -7 0 -130 -119 -273 -265 -164 -166 -269 -265 -281 -265 -12 0 -119 100 -283 265 -145 146 -269 265 -274 265 -6 0 -40 -28 -75 -62z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
    selector()
}
function diGatevalue1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M2255 4310 c-3 -5 -7 -74 -8 -152 l-2 -142 77 -106 c43 -58 100 -134 127 -170 49 -64 83 -110 118 -160 10 -14 58 -78 106 -143 48 -65 93 -128 99 -142 13 -29 13 -28 -114 -200 -49 -66 -115 -155 -146 -198 -31 -43 -70 -96 -87 -117 -41 -54 -78 -103 -131 -177 l-45 -61 -2 -154 -2 -153 727 -3 c711 -2 727 -2 738 17 5 11 10 79 9 154 0 131 -1 135 -27 164 -15 17 -58 74 -97 127 -38 54 -83 115 -100 136 -16 21 -48 64 -70 95 -22 32 -60 84 -85 116 -25 32 -61 81 -80 109 -19 28 -45 63 -57 77 -33 38 -29 61 25 134 42 55 103 138 207 279 46 63 150 203 214 289 l62 83 1 137 c0 75 0 144 -1 154 -1 16 -43 17 -725 17 -469 0 -727 -4 -731 -10z m1044 -304 c14 -17 5 -43 -32 -87 -16 -19 -82 -107 -148 -196 -145 -197 -145 -197 -216 -90 -26 39 -51 74 -54 77 -6 5 -130 169 -176 233 -26 35 -29 63 -10 70 6 3 150 6 318 6 248 1 309 -2 318 -13z m-253 -1071 c16 -24 76 -107 134 -185 125 -168 136 -188 121 -203 -9 -9 -95 -12 -294 -13 -330 0 -357 2 -357 26 0 14 53 92 187 273 124 167 126 170 156 156 13 -6 36 -30 53 -54z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
    selector()
}
function diFlanging1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M2704 4537 c-2 -7 -6 -522 -7 -1145 -2 -912 -5 -1134 -15 -1144 -10 -10 -78 -13 -289 -15 -218 -1 -277 -4 -284 -15 -12 -19 -11 -255 1 -263 5 -3 328 -6 717 -6 531 0 708 3 714 12 12 21 10 257 -3 266 -7 3 -129 7 -271 7 -240 1 -260 2 -272 20 -11 15 -14 213 -15 1148 0 622 -3 1134 -6 1139 -3 5 -64 9 -135 9 -98 0 -131 -3 -135 -13z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
    selector()
}
function diFlangesotcket1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M2102 4398 l3 -143 271 -1 c150 -1 277 -4 283 -8 8 -5 11 -202 11 -709 l0 -701 -360 -361 c-198 -198 -360 -365 -360 -370 0 -12 182 -195 194 -195 4 0 151 144 326 319 175 175 327 322 338 326 17 5 73 -46 316 -287 162 -161 310 -308 329 -327 l35 -33 96 93 c53 51 96 97 96 103 0 6 -162 173 -360 371 l-360 361 0 701 c0 511 3 704 11 709 7 4 129 7 273 6 167 0 265 3 273 10 14 11 19 231 7 262 -6 14 -78 16 -715 16 l-710 0 3 -142z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
    selector()
}
function diColor1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M2343 3583 c-11 -22 -9 -1255 1 -1271 5 -9 155 -12 624 -12 339 0 627 3 640 6 l22 6 0 632 c0 478 -3 635 -12 644 -9 9 -165 12 -640 12 -588 0 -628 -1 -635 -17z m1005 -255 c9 -9 12 -107 12 -380 0 -357 -1 -368 -20 -378 -13 -7 -136 -10 -375 -8 -303 3 -357 5 -365 18 -6 9 -10 165 -10 372 0 311 2 359 16 372 13 14 61 16 373 16 264 0 360 -3 369 -12z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
    selector()
}
function diCap1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M2216 3344 c-8 -20 -8 -747 0 -768 5 -14 29 -16 159 -16 138 0 154 2 159 18 3 9 5 113 5 230 -1 149 2 217 10 225 19 19 882 21 907 2 18 -12 19 -32 22 -244 l3 -232 157 3 157 3 0 395 0 395 -786 3 c-711 2 -787 1 -793 -14z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
    selector()

}
function coupler1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M2067 4466 c-7 -18 -9 -238 -8 -1138 l1 -748 944 0 c713 0 947 3 952 12 8 13 12 108 11 293 0 72 -1 460 -1 863 l0 732 -947 0 c-800 0 -948 -2 -952 -14z m1483 -396 c13 -8 15 -78 14 -534 0 -419 -3 -527 -13 -540 -12 -14 -71 -16 -545 -16 -400 0 -535 3 -544 12 -17 17 -17 1059 0 1076 15 15 1064 17 1088 2z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
//                                           canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQYAAAEhCAMAAACqQv6AAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAeUExURf///wD/AAr/CqP/o0j/SDb/Nn//f77/vvv/+2v/a6zKvA0AAAKLSURBVHgB7dzRboJAEAVQRFH8/x+u8uBksybuNCVlzKEvbDPt7hwuW9MEpslBgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQ+Cpz3OW7rx5kPVXDa4Vju52IK0w4K81oNYdqF4VBxH1vMHmkYm/lQVRi2y4EBQ9yX0vC0WHdgOAVymTMM26XCgCHuWWmQBmkIAWl4WfjcMK23+zLbIqf18dUzvJIydnLtfsM89oOHquqayH4UxrBdTwwY4saWBmmQhhCQhrCwN0iDNISANISFvUEapCEEpCEs7A3SIA0hIA1hYW+QBmkIAWkIC3uDNEhDCEhDWNgbpEEaQkAawsLeIA3SEALSEBb2BmmQhhCQhrCwN0iDNISANISFvUEavj8NS+6Y+0cylsd7LMKpxFn/IEHyO73C6eo1Fk/Eqd7rPJLXfqi8xG3QLnKor2RRO0OJUbLDofISjbeLHOorWdTOUGKU7HCovETj7SKH+koWtTOUGCU7HCov0Xi7yKG+kkXtDCVGyQ6Hyks03i5yqK9c0Xc8nJzr+U01hg0FA4a4O+ql4db/JzHa+e1ZMYbH2zwul+Xy58e9/VtkRIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQOB/BH4A6dQXtbtqskkAAAAASUVORK5CYII=',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });
    selector()
}
function beEndCateValue1() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M3078 5504 l-3 -336 -23 -19 c-22 -17 -45 -19 -388 -18 -200 0 -368 -2 -374 -6 -6 -4 -10 -82 -10 -207 l0 -201 55 -68 c30 -37 55 -73 55 -78 0 -6 14 -26 30 -44 27 -31 133 -173 347 -465 134 -183 167 -228 200 -271 58 -77 73 -46 -229 -456 -56 -77 -155 -210 -218 -295 -63 -86 -128 -175 -145 -198 -16 -23 -45 -63 -62 -88 l-33 -46 0 -187 c0 -132 4 -191 12 -199 9 -9 108 -12 384 -12 333 0 373 -2 385 -17 11 -13 15 -88 19 -357 l5 -341 193 -3 192 -2 0 338 c0 289 2 341 16 360 l15 22 369 0 c202 0 375 3 384 6 14 5 16 32 16 200 0 166 -2 199 -17 222 -17 26 -101 141 -272 372 -413 557 -448 608 -435 625 5 7 21 30 36 53 15 22 55 78 89 124 75 101 114 153 274 373 135 186 165 226 210 285 17 21 49 66 73 98 l42 60 0 195 c0 130 -4 198 -11 203 -6 3 -174 6 -373 5 -346 -2 -364 -1 -387 18 l-24 19 -5 334 -5 333 -192 3 -192 2 -3 -336z m628 -788 c23 -9 16 -53 -14 -88 -15 -18 -53 -68 -85 -110 -31 -43 -81 -111 -110 -150 -30 -40 -67 -91 -83 -114 -101 -142 -125 -167 -151 -157 -7 2 -38 40 -70 85 -32 44 -127 173 -210 286 -84 114 -153 215 -153 225 0 10 9 21 19 25 23 8 835 6 857 -2z m-404 -1388 c16 -14 50 -58 213 -283 55 -76 124 -168 153 -204 37 -46 51 -73 50 -91 l-3 -25 -440 0 -440 0 -3 22 c-2 15 12 41 40 76 39 49 177 236 323 440 57 77 76 89 107 65z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
    selector()

}
function di90degElbow() {
//    alert(2)
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.0" xmlns="http://www.w3.org/2000/svg"  width="834.000000pt" height="825.000000pt" viewBox="0 0 834.000000 825.000000"  preserveAspectRatio="xMidYMid meet"><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011 </metadata> <g transform="translate(0.000000,825.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"> <path d="M272 7867 c-116 -117 -212 -219 -212 -227 0 -20 330 -350 350 -350 8 0 251 -236 540 -525 289 -289 531 -525 538 -525 7 0 33 -19 57 -41 l45 -42 0 -2163 0 -2164 124 0 124 0 26 -31 c25 -30 26 -35 26 -165 l0 -134 2252 0 2252 0 256 -255 c140 -140 262 -255 270 -255 8 0 218 -202 465 -450 315 -315 456 -450 470 -449 12 1 108 92 235 221 l214 219 -219 219 c-121 121 -227 220 -235 220 -8 0 -229 215 -492 477 l-478 478 57 57 c32 32 64 58 73 58 8 0 251 236 540 525 289 289 532 525 540 525 20 0 200 181 200 200 0 20 -421 440 -440 440 -8 0 -164 -148 -345 -330 -181 -181 -337 -330 -345 -330 -8 0 -231 -216 -495 -480 l-480 -480 -1965 0 c-1516 0 -1969 3 -1978 12 -9 9 -12 472 -12 2024 l0 2011 45 42 c24 22 50 41 57 41 7 0 249 236 538 525 289 289 532 525 540 525 9 0 91 75 183 168 142 142 167 172 167 197 0 26 -29 59 -198 228 -168 168 -202 197 -227 197 -26 0 -82 -52 -435 -405 -223 -223 -412 -405 -420 -405 -8 0 -137 -121 -285 -270 -148 -148 -277 -270 -285 -270 -8 0 -137 122 -285 270 -148 149 -277 270 -285 270 -8 0 -197 182 -420 405 -223 223 -412 405 -420 405 -8 0 -111 -96 -228 -213z"/> </g> </svg>';

//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.0" xmlns="http://www.w3.org/2000/svg"  width="600.000000pt" height="540.000000pt" viewBox="0 0 600.000000 540.000000"  preserveAspectRatio="xMidYMid meet"> <metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,540.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"> <path d="M1325 4169 c-49 -48 -75 -80 -75 -94 0 -15 26 -46 81 -96 44 -41 165 -157 268 -259 104 -102 200 -196 215 -209 l26 -23 0 -843 c0 -928 -5 -855 60 -855 39 0 60 -24 60 -69 0 -20 5 -42 12 -49 9 -9 220 -12 886 -12 l874 0 206 -199 c114 -110 241 -233 282 -275 41 -42 78 -76 83 -76 16 0 157 143 157 159 0 8 -119 132 -266 274 -168 165 -264 266 -262 276 2 9 120 131 263 271 143 139 262 260 263 268 5 19 -128 152 -151 152 -17 0 -59 -39 -456 -426 -102 -100 -196 -187 -208 -193 -15 -8 -241 -11 -777 -11 -673 0 -756 2 -770 16 -14 14 -16 101 -16 806 l0 790 53 49 c123 115 547 536 547 545 0 12 -144 154 -155 154 -5 0 -77 -66 -161 -147 -232 -227 -378 -364 -396 -374 -19 -10 -58 26 -407 369 -85 83 -156 152 -158 152 -1 0 -36 -32 -78 -71z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
    selector()

}
function di45DegElbow() {
    canvas.isDrawingMode = false;
    mode = 'image';
//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.0" xmlns="http://www.w3.org/2000/svg"  width="600.000000pt" height="561.000000pt" viewBox="0 0 600.000000 561.000000"  preserveAspectRatio="xMidYMid meet"> <metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,561.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"> <path d="M1192 4372 c-11 -7 -13 -84 -12 -383 0 -326 -2 -377 -15 -396 l-16 -23 -383 0 c-316 0 -386 -2 -395 -14 -8 -9 -11 -48 -9 -112 l3 -99 440 -5 c242 -3 442 -7 445 -8 2 -1 286 -283 630 -627 344 -344 629 -625 633 -625 5 0 21 7 38 15 34 18 49 13 49 -19 0 -12 5 -27 12 -34 9 -9 222 -12 898 -12 l886 0 209 -211 c116 -115 247 -247 293 -291 l83 -81 80 79 c43 43 79 84 79 90 0 6 -80 92 -177 191 -302 305 -353 360 -353 383 0 15 85 106 270 291 149 148 270 273 270 277 0 11 -154 162 -165 162 -6 0 -157 -147 -337 -327 l-328 -328 -798 -3 c-438 -2 -812 0 -831 3 -27 5 -137 110 -650 622 l-616 615 -5 436 -5 437 -105 3 c-58 1 -111 -2 -118 -6z"/> </g> </svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
    selector()

}
function diReducer() {
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" width="600.000000pt" height="594.000000pt" viewBox="0 0 600.000000 594.000000"  preserveAspectRatio="xMidYMid meet"> <metadata> Created by potrace 1.10, written by Peter Selinger 2001-2011 </metadata> <g transform="translate(0.000000,594.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M2273 4308 c-6 -7 -11 -84 -12 -173 -1 -157 4 -198 45 -390 9 -38 21 -110 29 -160 8 -49 19 -110 25 -135 5 -25 19 -94 30 -155 11 -60 23 -117 26 -125 3 -8 10 -40 15 -70 38 -219 50 -285 115 -598 45 -224 52 -212 -125 -212 -124 0 -141 -2 -149 -17 -13 -25 -14 -281 -2 -304 10 -18 33 -19 824 -19 733 0 814 2 820 16 3 9 6 78 6 154 0 76 -3 145 -6 154 -5 14 -27 16 -139 16 -73 0 -140 3 -149 6 -19 7 -21 50 -5 109 6 22 15 67 20 100 13 86 38 224 49 265 11 45 24 112 56 290 36 207 54 292 64 315 5 11 14 58 19 105 5 47 14 105 20 130 45 188 64 323 68 489 2 96 2 185 -2 198 l-5 23 -814 0 c-639 0 -816 -3 -823 -12z m1288 -325 c15 -15 15 -55 0 -118 -7 -27 -19 -86 -26 -130 -28 -158 -35 -192 -70 -360 -20 -93 -43 -213 -50 -265 -8 -52 -22 -124 -30 -160 -9 -36 -22 -101 -29 -145 -32 -183 -87 -452 -97 -478 -6 -15 -21 -30 -33 -33 -11 -2 -81 -3 -155 -2 l-135 3 -17 45 c-17 45 -31 118 -52 265 -7 44 -15 85 -18 90 -4 6 -11 39 -18 75 -12 67 -45 241 -62 320 -5 25 -14 79 -19 120 -5 41 -14 91 -20 110 -14 52 -36 158 -65 320 -14 80 -33 177 -41 215 -23 109 -22 122 9 134 37 14 914 8 928 -6z"/></g> </svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
            function (oImg) {
                oImg.set({
                    width: 100 + parseInt(strokeWidth),
                    height: 100 + parseInt(strokeWidth),
                    left: 50,
                    top: 10,
                });
                canvas.add(oImg);
            });
    selector()

}
function eraser() {
    mode = 'pencil';
    canvas.isDrawingMode = true;
    canvas.freeDrawingBrush.width = strokeWidth;
    canvas.freeDrawingBrush.color = 'white';
}