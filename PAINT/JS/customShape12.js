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
var defaultsize=30,defaultflag=0;
var obj_top = 100;
var obj_left = 100;
var objsize=0;
bgcolor = '#F5F5F5';
$('#drawing-color').change(function () {
    color = this.value;
    canvas.freeDrawingBrush.color = this.value;
    if (mode != 'line')
        pencil();
});
$('#drawing-line').click(function () {
    mode = 'line';
    fillColor = false;
    canvas.isDrawingMode = false;
});
function redrawimages()
{
    var imagetype=canvas.getActiveObject().get("type");
//    alert(imagetype)
    if(canvas.getActiveObject()!=undefined&&canvas.getActiveObject()!=null)
    {
        defaultflag=1;
        var canvasobject=canvas.getActiveObject().get("id");
//        alert(canvasobject)
        if(canvasobject!=undefined)
        {
        obj_top=canvas.getActiveObject().get("top");
        if(obj_top<0)
        {
            obj_top=100;
        }
        obj_left=canvas.getActiveObject().get("left");
        if(obj_left<0)
        {
            obj_left=100;
        }
        if(imagetype!='line'&&imagetype!='path'&&imagetype!='i-text')
        {
            canvas.remove(canvas.getActiveObject());
        }
        if(this.value!=""&&this.value!=NaN&&this.value!=undefined){
            defaultsize=parseInt(this.value);
        }
        else{
            defaultsize=10;
        }
        if(imagetype.match("triangle"))
        {
            triangle()
        }
        else if(imagetype.match("circle"))
        {
            circle();
        }
        else if(imagetype.match("ellipse"))
        {
            eclipse();
        }
//        else if(imagetype.match("i-text"))
//        {
//            textEditor1()
//        }
        else if(imagetype.match("image"))
        {
//            defaultsize=defaultsize;
            if(canvasobject.match("tappingTee1"))
            {
                tappingTee1();
            }
            else if(canvasobject.match("tJoint1()"))
            {
                tJoint1();
            }
            else if(canvasobject.match("stubBlang1()"))
            {
                stubBlang1();
            }
            else if(canvasobject.match("reducer1()"))
            {
                reducer1();
            }
            else if(canvasobject.match("lastDegelbow1()"))
            {
                lastDegelbow1();
            }
            else if(canvasobject.match("halfDegelbow1()"))
            {
                halfDegelbow1();
            }
            else if(canvasobject.match("fullDegelbow1()"))
            {
                fullDegelbow1();
            }
            else if(canvasobject.match("equalTee1()"))
            {
                equalTee1();
            }
            else if(canvasobject.match("endCap1()"))
            {
                endCap1()
            }
            else if(canvasobject.match("diTee1()"))
            {
                diTee1()
            }
            else if(canvasobject.match("diGatevalue1()"))
            {
                diGatevalue1()
            }
            else if(canvasobject.match("diFlanging1()"))
            {
                diFlanging1()
            }
            else if(canvasobject.match("diFlangesotcket1()"))
            {
                diFlangesotcket1()
            }
            else if(canvasobject.match("diColor1()"))
            {
                diColor1()
            }
            else if(canvasobject.match("diCap1()"))
            {
                diCap1()
            }
            else if(canvasobject.match("coupler1()"))
            {
                coupler1()
            }
            else if(canvasobject.match("beEndCateValue1()"))
            {
                beEndCateValue1()
            }
            else if(canvasobject.match("di90degElbow()"))
            {
                di90degElbow()
            }
            else if(canvasobject.match("di45DegElbow()"))
            {
                di45DegElbow()
            }
            else if(canvasobject.match("diReducer()"))
            {
                diReducer()
            }

        }
        else if(imagetype.match("line")||imagetype.match("path"))
        {
            canvas.getActiveObject().set('strokeWidth', defaultsize);
        }
        else if(imagetype.match("rect"))
        {
            rectangle();
        }
    }
    }
    fontwidth = defaultsize;
////    if (mode != 'line')
////        pencil();
    defaultflag=0;

}
$(document).on('change keyup','#drawing-line-width',function(e){
    if(parseInt($('#drawing-line-width').val())>100||parseInt($('#drawing-line-width').val())<10)
    {
        $('#drawing-line-width').val(10)
        redrawimages();
//    alert("Size should between 10 and 100");
        show_msgbox("REPORT SUBMISSION ENTRY","Size should between 10 and 100","error",false)

    }
    else{
        redrawimages();
    }

});

function onObjectSelected(e)
{
//    alert(11)
    if(canvas.getActiveObject()!=null&&canvas.getActiveObject()!=undefined)
    {
        var imagetype1=canvas.getActiveObject().get("type")
        if(imagetype1.match("circle"))
        {
            var size=parseInt(canvas.getActiveObject().get("radius"));
        }
        else if(imagetype1.match("ellipse"))
        {
            var size=parseInt(canvas.getActiveObject().get("rx"));
        }
        else if(imagetype1.match("i-text"))
        {
            var size=parseInt(canvas.getActiveObject().get("fontSize"));
        }
        else  if(imagetype1.match("image"))
        {
            var size=parseInt(canvas.getActiveObject().get("width"))-30;

        }
        else  if(imagetype1.match("line")||imagetype1.match("path"))
        {
            var size=parseInt(canvas.getActiveObject().get("strokeWidth"));

        }
        else  if(imagetype1.match("i-text"))
        {
            var size=parseInt(canvas.getActiveObject().get("fontSize"));

        }

//             else  if(imagetype1.match("path"))
//             {
//                 alert("pencil"+canvas.freeDrawingBrush.width+"wid"+canvas.getActiveObject().get("strokeWidth"))
//                 var size=parseInt(canvas.getActiveObject().get("strokeWidth"));
//
//             }
        else
        {
            var size=parseInt(canvas.getActiveObject().get("width"));
        }
        $('#drawing-line-width').val(size)
//                alert("pencil"+canvas.freeDrawingBrush.color+"wid"+canvas.getActiveObject().get("id"))
//                alert($('#drawing-line-width').val());
//        alert(canvas.getActiveObject().get("width"));
//        alert(canvas.getActiveObject().get("height"));
//        alert(size)

        if(parseInt($('#drawing-line-width').val())>100||parseInt($('#drawing-line-width').val())<10)
        {
            $('#drawing-line-width').val(10)
            redrawimages();
//    alert("Size should between 10 and 100");
            show_msgbox("REPORT SUBMISSION ENTRY","Size should between 10 and 100","error",false)

        }
    }
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
    canvas.isDrawingMode = false
    defaultflag=0;

}
function circle() {
//    obj_left=50;
//    obj_top=10;
//    if(defaultflag==1)
//    {
//        defaultsize=defaultsize
//    }
//    else
//    {
    defaultsize=parseInt($('#drawing-line-width').val());
//    }
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
    selector();
}
function textEditor1() {
//    if(defaultflag==1)
//    {
//        fontwidth=defaultsize
//    }
//    else
//    {
//        fontwidth=10;
//    }
    defaultsize=parseInt($('#drawing-line-width').val());
    mode = 'text';
    fillColor = false;
    canvas.isDrawingMode = false;
    canvas.add(new fabric.IText('TEXT', {
        id:"textEditor1",
        fontFamily: fontfamily,
        left: obj_left,
        top: obj_top,
        textID: "SommeID",
        fontSize: defaultsize,
        fill: color,
        fontWeight: fontbold,
        fontStyle: fontitalic,
        backgroundColor: 'transparent',
        textDecoration: fontunderline
    }));
//    alert("1")
//    selector()
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
    defaultflag=0;
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
    defaultflag=0;
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
    defaultflag=0;
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
//    if(defaultflag==1)
//    {
//        defaultsize=defaultsize
//    }
//    else
//    {
//        defaultsize=10;
//    }
    defaultsize=parseInt($('#drawing-line-width').val());

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
//    if(defaultflag==1)
//    {
//        defaultsize=defaultsize
//    }
//    else
//    {
//        defaultsize=10;
//    }
    defaultsize=parseInt($('#drawing-line-width').val());

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
    defaultflag=0;
    selector();
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
//    if(defaultflag==1)
//    {
//        defaultsize=defaultsize;
//    }
//    else
//    {
//        defaultsize=10;
//    }
    defaultsize=parseInt($('#drawing-line-width').val());

    canvas.isDrawingMode = true;
    canvas.freeDrawingBrush.width = defaultsize;
    canvas.freeDrawingBrush.color = color;
    mode = 'pencil';
    defaultflag=0;
//    selector()
}
function rectangle() {

//    if(defaultflag==1)
//    {
//        defaultsize=defaultsize;
//    }
//    else
//    {
//        defaultsize=10;
//    }
    defaultsize=parseInt($('#drawing-line-width').val());

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
    canvas.deactivateAllWithDispatch().renderAll();
    defaultflag=0;

}
function clearCanvas() {
    canvas.clear();
    defaultflag=0;
}
function selector() {
    setdefaultsize()
    mode='selector';
    canvas.isDrawingMode = false;
    canvas.forEachObject(function(o){ o.hasBorders = o.hasControls = true;o.selectable=true; });
//    defaultflag=0;

}
//var imagedefsize=30;
function tappingTee1() {

    canvas.isDrawingMode = false;
    var svg, jsonCanvas;
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="375.000000pt" height="510.000000pt" viewBox="0 0 375.000000 510.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,510.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M0 4800 l0 -250 485 0 c469 0 485 -1 495 -19 16 -30 14 -4043 -2 -4059 -9 -9 -133 -12 -495 -12 l-483 0 0 -230 0 -230 1244 0 1244 0 4 228 c2 125 3 228 2 230 -1 2 -218 3 -482 2 -428 -1 -482 1 -496 15 -14 15 -16 100 -16 874 0 725 2 860 14 877 14 19 38 19 1071 21 581 1 1059 4 1062 7 3 2 6 112 6 243 1 131 6 246 11 256 8 16 -46 17 -1062 17 -960 0 -1072 2 -1086 16 -14 14 -16 106 -16 864 0 525 4 858 10 874 l9 26 483 2 483 3 5 220 c3 121 4 232 2 248 l-3 27 -1245 0 -1244 0 0 -250z"/></g></svg>';

//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M1744 4377 c-2 -7 -3 -69 -2 -138 l3 -124 269 -3 c178 -1 274 -6 283 -13 11 -9 13 -216 13 -1163 0 -707 -4 -1157 -10 -1166 -8 -13 -54 -16 -282 -20 l-273 -5 0 -135 0 -135 705 0 705 0 3 137 3 138 -264 0 c-211 0 -267 3 -283 14 -18 14 -19 31 -19 505 0 389 3 493 13 503 10 10 140 14 615 18 l602 5 0 140 0 140 -607 5 c-333 3 -610 9 -615 13 -4 5 -9 223 -11 485 -1 372 1 483 11 502 l13 25 269 5 270 5 0 135 0 135 -703 3 c-572 2 -704 0 -708 -11z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"tappingTee1",
                width:defaultsize,
                height:defaultsize,
                left: obj_left,
                top: obj_top
//                    strokeWidth: strokeWidth
            });
            canvas.add(oImg);
        });
    selector();
//    setdefaultsize()

}
function setdefaultsize()
{
    if($('#drawing-line-width').val()!="")
    {

        defaultsize=30+parseInt($('#drawing-line-width').val());
    }
    else{
        defaultsize=30;
    }
//    var i=0;
//    canvas.forEachObject(function(obj) {
//        alert("obj")
//        index=canvas.getObjects().indexOf(canvas.getActiveObject());
//        alert("item"+index+"it"+canvas.item(index)
//            +i);
//        i=i+1;
//
//    });
//    canvas.setActiveObject(canvas.item(index));
//    alert("Testcount"+i);

}
function tJoint1() {
//    defaultsize=30+ defaultsize;
//     if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {         defaultsize=30    }

    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="252.000000pt" height="291.000000pt" viewBox="0 0 252.000000 291.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,291.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M52 2858 c-9 -9 -12 -81 -12 -255 0 -225 1 -243 18 -243 9 0 231 -2 494 -3 262 -1 480 -6 485 -10 12 -12 15 -1768 3 -1787 -8 -13 -59 -15 -359 -15 -214 0 -354 -4 -360 -10 -10 -10 -21 -505 -11 -505 3 -1 448 -1 990 -2 l985 0 5 253 c3 140 4 256 2 259 -2 3 -160 5 -352 5 -255 0 -352 3 -361 12 -9 9 -12 230 -12 894 0 841 1 883 19 896 14 10 111 13 476 13 l458 0 0 255 0 255 -1228 0 c-933 0 -1231 -3 -1240 -12z"/></g></svg>';

//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M2106 3474 c-8 -20 -8 -248 0 -268 5 -14 41 -16 293 -16 232 0 291 -3 307 -14 18 -14 19 -33 22 -537 2 -457 1 -525 -13 -545 -15 -24 -16 -24 -220 -24 -192 0 -205 -1 -215 -19 -16 -30 -13 -266 3 -280 16 -13 1154 -15 1175 -2 9 6 12 45 10 152 l-3 144 -207 3 c-186 2 -208 4 -217 20 -5 10 -9 233 -9 542 0 464 1 527 15 543 15 15 42 17 284 17 258 0 269 1 279 20 6 12 9 71 8 147 l-3 128 -751 3 c-679 2 -752 1 -758 -14z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"tJoint1()",
                width:defaultsize,
                height:defaultsize,
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector();
//    setdefaultsize()
}
function stubBlang1() {
//    if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {                 defaultsize=30     }

    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="168.000000pt" height="369.000000pt" viewBox="0 0 168.000000 369.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,369.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M630 2031 c0 -1495 -2 -1661 -16 -1675 -13 -14 -52 -16 -275 -16 -160 0 -268 4 -280 10 -10 6 -19 20 -19 30 0 13 -7 20 -20 20 -19 0 -20 -7 -20 -200 l0 -200 840 0 840 0 0 170 0 170 -327 0 c-181 0 -334 4 -341 9 -13 8 -17 537 -21 2649 l-1 692 -180 0 -180 0 0 -1659z"/></g></svg>';

//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M2757 4603 c-4 -3 -7 -561 -7 -1238 0 -1181 -1 -1233 -18 -1248 -16 -15 -53 -17 -315 -17 -163 0 -298 -4 -301 -8 -6 -11 -10 -291 -4 -297 9 -10 1540 -7 1549 2 5 5 8 74 7 154 l-3 144 -292 5 c-192 3 -296 9 -303 16 -8 8 -11 362 -11 1239 1 886 -2 1232 -10 1242 -9 10 -46 13 -149 13 -75 0 -140 -3 -143 -7z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"stubBlang1()",
                width:defaultsize,
                height:defaultsize,
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()
//    setdefaultsize()

}
function reducer1() {
//     if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {             defaultsize=30;     }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="255.000000pt" height="354.000000pt" viewBox="0 0 255.000000 354.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,354.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M0 3251 c0 -237 3 -294 15 -317 8 -16 15 -48 15 -71 0 -23 5 -54 11 -70 6 -15 14 -55 19 -88 19 -120 32 -191 46 -239 8 -26 14 -66 14 -88 0 -22 5 -48 10 -59 6 -10 15 -47 20 -82 18 -120 32 -193 46 -241 8 -26 14 -62 14 -80 0 -17 7 -53 15 -80 8 -27 15 -65 15 -83 0 -19 5 -43 11 -54 6 -11 14 -50 19 -87 9 -68 23 -140 42 -222 5 -25 14 -70 19 -100 20 -127 32 -188 45 -234 8 -26 14 -65 14 -86 0 -21 7 -54 15 -73 8 -20 15 -54 15 -75 0 -22 4 -52 10 -68 12 -35 31 -145 33 -194 l2 -35 -232 -3 -233 -2 0 -260 0 -260 1250 0 1250 0 2 247 3 247 25 13 c21 10 -18 13 -234 15 -301 3 -290 -1 -264 95 17 59 32 139 47 248 5 33 14 73 20 88 6 16 11 44 11 64 0 20 6 59 14 87 14 48 24 104 45 236 5 30 14 75 19 100 17 72 31 149 43 230 5 41 14 79 20 84 5 6 9 27 9 49 0 21 6 66 14 100 23 95 36 166 46 237 4 36 13 78 19 93 6 16 11 44 11 63 0 19 7 51 15 71 8 19 15 51 15 70 0 19 7 65 15 102 26 121 36 175 44 241 5 36 14 78 20 93 6 16 11 43 11 60 0 18 7 45 15 61 12 23 15 77 15 287 l0 259 -1250 0 -1250 0 0 -289z m1938 -253 c16 -16 15 -67 -3 -102 -8 -15 -15 -45 -15 -65 0 -20 -5 -54 -11 -76 -13 -52 -38 -184 -49 -260 -5 -33 -13 -73 -19 -88 -6 -16 -11 -43 -11 -62 0 -18 -6 -55 -14 -81 -13 -46 -25 -107 -45 -234 -5 -30 -14 -75 -20 -100 -15 -65 -31 -146 -41 -215 -5 -33 -13 -73 -19 -88 -6 -16 -11 -43 -11 -62 0 -18 -6 -55 -14 -81 -13 -46 -25 -107 -45 -234 -5 -30 -14 -75 -20 -100 -15 -62 -31 -144 -41 -210 -4 -30 -13 -75 -18 -100 -17 -71 -32 -150 -41 -210 -13 -88 -19 -99 -54 -105 -41 -8 -423 -2 -432 6 -3 4 -8 24 -11 45 -5 40 -21 123 -49 250 -8 40 -15 86 -15 104 0 17 -5 41 -11 52 -7 12 -15 47 -19 77 -17 118 -32 198 -46 241 -8 25 -14 59 -14 76 0 17 -5 44 -10 60 -6 16 -14 58 -20 94 -5 36 -14 90 -20 120 -6 30 -18 89 -26 130 -9 41 -20 95 -26 120 -5 25 -14 70 -18 100 -9 65 -27 157 -42 220 -6 25 -14 68 -18 95 -9 57 -27 150 -42 215 -5 25 -14 70 -18 100 -10 61 -23 130 -45 231 -18 81 -19 121 -3 137 17 17 1359 17 1376 0z"/></g></svg>';

//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M2236 4164 c-13 -33 -6 -299 9 -374 9 -41 22 -113 29 -160 7 -47 23 -128 35 -180 11 -52 25 -124 30 -160 6 -36 19 -105 29 -155 11 49 23 -112 27 -140 4 -27 15 -86 26 -130 10 -44 23 -109 28 -145 5 -36 21 -117 34 -180 33 -154 39 -202 27 -220 -7 -12 -39 -16 -142 -20 l-133 -5 -3 -148 -3 -147 768 2 768 3 0 145 0 145 -132 5 c-159 6 -172 13 -155 82 6 24 20 93 31 153 12 61 30 160 42 220 11 61 30 160 40 220 11 61 26 137 34 170 7 33 21 107 30 165 9 58 22 128 30 155 7 28 16 75 20 105 7 61 40 227 55 280 11 38 14 288 4 314 -6 14 -82 16 -764 16 -682 0 -758 -2 -764 -16z m1174 -307 c17 -9 24 -20 23 -37 -3 -38 -49 -284 -61 -321 -6 -19 -13 -59 -17 -89 -3 -30 -17 -109 -30 -175 -37 -181 -75 -381 -90 -465 -7 -41 -20 -111 -29 -155 -9 -44 -24 -124 -34 -178 -11 -58 -25 -105 -36 -115 -13 -13 -39 -18 -128 -20 -164 -5 -158 -11 -197 203 -11 61 -30 157 -41 215 -11 58 -36 188 -55 290 -20 102 -47 241 -60 310 -14 69 -29 150 -34 180 -5 30 -21 113 -35 183 -27 131 -26 173 2 180 10 3 193 5 407 6 299 0 396 -2 415 -12z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"reducer1()",
                width:defaultsize,
                height:defaultsize,
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()
//    defaultflag=0;
//    setdefaultsize()

}
function lastDegelbow1() {
//     if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {               defaultsize=30    }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="270.000000pt" height="504.000000pt" viewBox="0 0 270.000000 504.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,504.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M190 4790 l0 -250 480 0 c464 0 480 -1 490 -19 6 -13 10 -175 10 -457 l0 -438 -163 -165 c-374 -379 -865 -877 -912 -923 -27 -28 -61 -54 -74 -60 l-25 -9 30 -18 c16 -11 280 -270 587 -578 l557 -558 0 -383 c0 -245 -4 -390 -10 -403 -10 -18 -26 -19 -478 -19 -351 0 -471 -3 -480 -12 -9 -9 -12 -79 -12 -255 l0 -243 1255 0 1255 0 0 243 0 243 -33 12 c-24 9 -161 12 -495 12 -454 0 -461 0 -472 20 -8 15 -11 170 -10 503 l2 482 -466 465 c-256 256 -466 472 -466 480 0 9 209 224 465 480 l465 463 0 549 c0 360 4 556 10 569 10 18 26 19 472 19 334 0 471 3 495 12 l33 12 0 238 0 238 -1255 0 -1255 0 0 -250z"/></g></svg>';

//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M2224 4885 c-10 -7 -14 -47 -15 -152 0 -78 2 -147 6 -152 4 -7 103 -11 299 -11 271 0 294 -1 309 -18 15 -16 17 -51 16 -288 0 -148 -4 -277 -7 -286 -4 -10 -169 -182 -367 -384 -198 -201 -362 -368 -364 -370 -3 -2 163 -172 367 -377 l372 -373 0 -240 c0 -201 -3 -243 -16 -262 -15 -22 -17 -22 -309 -22 -197 0 -296 -4 -300 -11 -7 -11 -7 -290 0 -297 2 -2 356 -4 786 -4 650 0 783 2 790 13 14 22 11 273 -3 287 -9 9 -90 12 -304 12 -259 0 -293 2 -305 17 -11 13 -14 80 -15 327 l-2 312 -296 294 c-230 229 -296 301 -296 320 0 19 65 90 296 320 l295 295 -1 341 c0 208 3 351 9 367 l11 27 294 0 c254 0 297 2 310 16 13 13 16 42 16 156 0 127 -2 140 -18 145 -38 10 -1545 8 -1558 -2z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"lastDegelbow1()",
                width:defaultsize,// 100 + parseInt(strokeWidth),
                height:defaultsize,// 100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()
//    setdefaultsize()
}
function halfDegelbow1() {
//     if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {                  defaultsize=30;     }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="366.000000pt" height="465.000000pt" viewBox="0 0 366.000000 465.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,465.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M1830 4637 c0 -7 -74 -86 -165 -177 -91 -91 -165 -172 -165 -181 0 -9 151 -165 335 -347 184 -183 335 -338 335 -345 0 -15 -1139 -1152 -1162 -1160 -17 -6 -18 -61 -18 -960 0 -728 -3 -956 -12 -965 -9 -9 -133 -12 -495 -12 l-483 0 0 -245 0 -245 1245 0 1245 0 -2 243 -3 242 -479 3 c-334 1 -483 6 -493 13 -11 9 -13 159 -13 838 0 500 4 832 10 841 24 38 1024 1030 1039 1030 8 0 167 -151 351 -335 185 -184 340 -335 345 -335 10 0 75 60 266 245 l106 103 -869 869 c-477 477 -868 874 -868 880 0 7 -11 13 -25 13 -14 0 -25 -6 -25 -13z"/></g></svg>';

//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M3015 4207 c-49 -48 -85 -92 -85 -103 0 -12 70 -88 180 -197 99 -98 180 -186 180 -195 0 -10 -148 -165 -330 -346 l-330 -329 0 -528 c0 -512 -1 -529 -19 -539 -12 -6 -116 -10 -269 -10 -201 0 -252 -3 -261 -14 -8 -9 -11 -55 -9 -137 l3 -124 691 -3 691 -2 5 27 c3 16 2 78 -1 138 l-6 110 -261 3 c-221 2 -264 5 -275 18 -8 10 -12 45 -12 102 1 48 2 258 2 467 l1 379 287 288 c172 172 295 288 306 288 11 0 95 -75 198 -177 107 -106 188 -178 199 -178 11 0 57 38 104 84 67 65 83 87 76 100 -15 28 -949 956 -965 959 -9 2 -52 -33 -100 -81z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"halfDegelbow1()",
                width:defaultsize,// 100 + parseInt(strokeWidth),
                height:defaultsize,// 100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()
//    setdefaultsize()
}
function fullDegelbow1() {
//     if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {             defaultsize=30    }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="378.000000pt" height="387.000000pt" viewBox="0 0 378.000000 387.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,387.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M3227 3783 c-4 -3 -7 -207 -7 -453 0 -333 -3 -449 -12 -458 -9 -9 -270 -12 -1115 -12 l-1103 0 0 -1133 c0 -868 -3 -1136 -12 -1145 -9 -9 -133 -12 -495 -12 l-483 0 0 -260 0 -260 1222 0 c672 0 1232 3 1245 6 l23 6 -2 252 -3 251 -474 3 c-424 2 -477 4 -493 19 -17 15 -18 57 -18 873 0 817 1 858 18 873 17 16 90 17 841 17 548 0 829 -3 842 -10 18 -10 19 -27 19 -510 0 -490 0 -500 20 -510 13 -7 101 -10 253 -8 l232 3 3 1238 2 1237 -248 0 c-137 0 -252 -3 -255 -7z"/></g></svg>';
//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M3904 4445 c-11 -8 -14 -74 -16 -315 l-3 -305 -765 -5 -765 -5 -5 -776 c-4 -637 -7 -779 -19 -792 -12 -15 -48 -17 -332 -17 -293 0 -319 -1 -327 -17 -5 -10 -9 -88 -8 -173 l1 -155 844 -3 c624 -1 847 1 858 9 11 9 13 45 11 173 l-3 161 -333 5 c-281 4 -334 7 -342 20 -6 9 -10 250 -10 601 0 555 1 587 18 602 17 15 72 17 581 17 368 0 569 -4 582 -10 18 -10 19 -25 19 -348 0 -250 3 -341 12 -350 13 -13 291 -18 322 -6 14 6 16 89 16 845 0 728 -2 840 -15 845 -26 10 -308 9 -321 -1z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"fullDegelbow1()",
                width:defaultsize,// 100 + parseInt(strokeWidth),
                height:defaultsize,// 100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()
//    setdefaultsize()
}
function equalTee1() {
//     if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {         defaultsize=30;     }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="312.000000pt" height="414.000000pt" viewBox="0 0 312.000000 414.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,414.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M119 4123 c-8 -109 -4 -365 5 -371 6 -3 164 -5 352 -3 302 3 342 1 357 -13 14 -15 16 -165 16 -1650 0 -1459 -2 -1636 -16 -1648 -12 -9 -95 -12 -358 -13 -189 0 -347 -4 -352 -8 -4 -5 -9 -101 -10 -213 l-1 -204 954 0 954 0 -2 213 -3 212 -363 0 c-312 0 -364 2 -372 15 -11 16 -14 1377 -4 1404 6 14 76 16 693 16 521 0 690 -3 699 -12 9 -9 12 -108 12 -384 0 -278 3 -375 12 -380 6 -4 91 -7 188 -5 163 2 178 0 202 -18 14 -11 28 -17 32 -14 3 3 6 464 6 1024 l0 1017 -27 5 c-16 3 -113 4 -218 3 l-190 -1 -3 -393 -2 -393 -26 -9 c-15 -6 -284 -10 -689 -10 -590 0 -665 2 -679 16 -14 14 -16 92 -16 714 0 632 2 700 16 715 15 14 55 16 363 15 190 -1 352 -1 359 -1 9 1 12 47 12 196 l0 195 -950 0 c-898 0 -950 -1 -951 -17z"/></g></svg>';
//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
//        d="M1784 4589 c-11 -18 -13 -232 -2 -251 8 -16 31 -18 273 -18 223 0 266 -2 279 -15 13 -14 15 -150 15 -1185 0 -1035 -2 -1171 -15 -1185 -13 -13 -56 -15 -279 -15 -242 0 -265 -1 -273 -17 -10 -21 -8 -236 2 -252 11 -16 1408 -15 1424 1 8 8 12 51 12 128 0 77 -4 120 -12 128 -9 9 -83 12 -273 12 -194 0 -266 3 -278 13 -16 11 -18 50 -17 511 0 350 4 503 11 513 9 10 111 13 538 13 314 0 532 -4 541 -10 13 -8 15 -49 16 -262 1 -139 2 -263 3 -276 1 -22 1 -23 144 -20 l142 3 3 704 c1 512 -1 708 -9 717 -9 11 -45 14 -145 14 -121 0 -134 -2 -135 -17 -1 -10 -2 -134 -3 -276 -1 -243 -2 -259 -20 -273 -17 -12 -106 -14 -537 -14 -457 0 -519 2 -533 16 -14 14 -16 75 -16 519 0 392 3 506 13 512 6 4 136 10 287 13 l275 5 3 115 c1 63 0 125 -3 138 l-5 22 -710 0 c-523 0 -711 -3 -716 -11z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"equalTee1()",
                width: defaultsize,//100 + parseInt(strokeWidth),
                height:defaultsize,// 100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()
//    setdefaultsize()
}
function endCap1() {
//    if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {      defaultsize=30;     }
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
                height:defaultsize,//100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top,
                strokeWidth: strokeWidth
            });
            canvas.add(oImg);
        });
    selector()
//    setdefaultsize()
}
function diTee1() {
//    if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {           defaultsize=30     }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="396.000000pt" height="600.000000pt" viewBox="0 0 396.000000 600.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,600.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M1550 5414 c-271 -271 -427 -420 -442 -422 -19 -2 -106 79 -443 413 -230 228 -423 415 -428 415 -5 0 -60 -48 -123 -107 -104 -98 -114 -110 -114 -142 0 -34 25 -61 455 -491 l455 -455 -2 -1618 -3 -1617 -453 -452 c-413 -413 -452 -455 -452 -485 0 -29 15 -48 115 -148 63 -63 118 -115 122 -115 3 0 195 188 425 418 267 266 426 418 440 420 18 2 108 -82 438 -413 228 -228 422 -415 430 -415 16 0 267 246 260 254 -3 3 -210 213 -460 467 -250 253 -459 467 -463 475 -14 24 -10 1233 4 1250 10 12 119 14 717 14 l706 0 169 -167 c93 -93 304 -302 469 -466 164 -163 303 -297 308 -297 5 0 65 54 132 120 68 66 129 120 136 120 6 0 12 9 12 20 0 12 -7 20 -16 20 -16 0 -827 799 -846 834 -9 16 45 73 403 433 228 227 424 416 437 419 27 7 30 40 4 48 -24 8 -242 218 -242 234 0 6 -5 12 -11 12 -5 0 -223 -211 -482 -470 l-472 -470 -712 0 c-449 0 -714 4 -718 10 -4 7 -8 955 -5 1543 0 24 72 100 460 490 253 254 460 468 460 477 0 8 -57 71 -126 139 l-126 124 -418 -419z"/></g></svg>';
//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
//        d="M2013 5058 c-35 -35 -69 -71 -75 -82 -9 -16 27 -56 288 -317 l299 -299 3 -1008 c1 -554 0 -1022 -3 -1041 -5 -27 -59 -86 -301 -326 -162 -161 -294 -297 -294 -301 0 -14 143 -154 157 -154 7 0 124 111 260 248 226 225 286 282 304 282 4 0 125 -119 270 -265 145 -146 269 -265 275 -265 20 0 154 131 154 150 0 10 -126 144 -295 312 l-295 294 0 400 c0 298 3 403 12 412 9 9 126 12 466 12 l454 0 301 -300 c165 -165 303 -300 307 -300 3 0 41 36 85 80 l80 80 -267 266 c-192 191 -268 273 -268 289 0 12 11 33 24 46 12 13 129 129 260 258 130 129 236 242 236 251 0 16 -146 167 -154 159 -3 -2 -138 -137 -301 -299 l-296 -295 -443 -3 c-280 -2 -453 1 -470 7 l-26 11 0 499 0 499 295 295 c162 162 295 303 295 313 0 19 -132 154 -151 154 -7 0 -130 -119 -273 -265 -164 -166 -269 -265 -281 -265 -12 0 -119 100 -283 265 -145 146 -269 265 -274 265 -6 0 -40 -28 -75 -62z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"diTee1()",
                width:defaultsize,// 100 + parseInt(strokeWidth),
                height:defaultsize,// 100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()
//    setdefaultsize()
}
function diGatevalue1() {
//    if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {               defaultsize=30     }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="246.000000pt" height="357.000000pt" viewBox="0 0 246.000000 357.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,357.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M0 3230 c0 -156 4 -270 9 -270 5 0 23 -21 40 -47 17 -27 39 -59 50 -73 11 -14 101 -135 200 -270 99 -135 192 -261 206 -280 14 -19 51 -69 81 -110 31 -41 114 -153 185 -249 90 -122 129 -183 129 -201 0 -24 -19 -56 -55 -96 -5 -6 -17 -22 -25 -34 -15 -22 -265 -363 -301 -410 -11 -14 -30 -41 -43 -60 -13 -19 -34 -46 -46 -60 -12 -14 -29 -36 -38 -50 -14 -22 -225 -310 -289 -395 -13 -17 -38 -51 -55 -77 -18 -27 -36 -48 -40 -48 -5 0 -8 -112 -8 -250 l0 -250 1230 0 1230 0 0 285 c0 278 0 285 -20 285 -11 0 -20 4 -20 8 0 4 -8 18 -17 31 -10 12 -31 40 -48 62 -16 21 -43 56 -58 76 -198 267 -283 384 -302 414 -13 20 -29 42 -37 50 -7 8 -31 38 -52 68 -21 30 -43 59 -48 65 -5 6 -51 70 -103 141 -52 72 -113 154 -136 183 -47 59 -47 66 10 137 18 22 75 99 127 170 52 72 98 135 103 140 4 6 25 35 46 65 21 30 41 57 45 60 4 3 24 30 45 60 21 30 49 69 62 85 14 17 67 89 119 160 52 72 106 145 121 164 16 18 32 41 37 50 5 9 19 28 30 43 12 14 32 40 45 57 13 17 29 31 37 31 12 0 14 52 14 305 l0 305 -1230 0 -1230 0 0 -270z m1806 -275 c4 -9 -6 -31 -22 -51 -16 -20 -42 -55 -59 -78 -16 -22 -40 -52 -52 -66 -13 -14 -23 -28 -23 -31 0 -6 -253 -350 -300 -409 -11 -14 -33 -44 -48 -68 -45 -70 -67 -67 -125 17 -17 25 -36 52 -42 58 -14 17 -48 63 -190 258 -68 94 -136 186 -150 205 -86 115 -116 161 -110 170 4 7 192 10 561 10 485 0 555 -2 560 -15z m-521 -1721 c14 -15 25 -30 25 -35 0 -4 15 -25 33 -48 17 -22 56 -73 85 -113 159 -218 314 -427 339 -457 42 -50 46 -60 33 -77 -11 -12 -94 -14 -554 -14 -391 0 -545 3 -553 11 -12 12 3 45 35 78 8 8 25 30 38 50 13 20 32 47 43 61 10 14 60 81 110 150 50 69 95 130 99 135 31 40 162 218 182 248 30 44 52 47 85 11z"/></g></svg>';
//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
//        d="M2255 4310 c-3 -5 -7 -74 -8 -152 l-2 -142 77 -106 c43 -58 100 -134 127 -170 49 -64 83 -110 118 -160 10 -14 58 -78 106 -143 48 -65 93 -128 99 -142 13 -29 13 -28 -114 -200 -49 -66 -115 -155 -146 -198 -31 -43 -70 -96 -87 -117 -41 -54 -78 -103 -131 -177 l-45 -61 -2 -154 -2 -153 727 -3 c711 -2 727 -2 738 17 5 11 10 79 9 154 0 131 -1 135 -27 164 -15 17 -58 74 -97 127 -38 54 -83 115 -100 136 -16 21 -48 64 -70 95 -22 32 -60 84 -85 116 -25 32 -61 81 -80 109 -19 28 -45 63 -57 77 -33 38 -29 61 25 134 42 55 103 138 207 279 46 63 150 203 214 289 l62 83 1 137 c0 75 0 144 -1 154 -1 16 -43 17 -725 17 -469 0 -727 -4 -731 -10z m1044 -304 c14 -17 5 -43 -32 -87 -16 -19 -82 -107 -148 -196 -145 -197 -145 -197 -216 -90 -26 39 -51 74 -54 77 -6 5 -130 169 -176 233 -26 35 -29 63 -10 70 6 3 150 6 318 6 248 1 309 -2 318 -13z m-253 -1071 c16 -24 76 -107 134 -185 125 -168 136 -188 121 -203 -9 -9 -95 -12 -294 -13 -330 0 -357 2 -357 26 0 14 53 92 187 273 124 167 126 170 156 156 13 -6 36 -30 53 -54z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"diGatevalue1()",
                width:defaultsize,// 100 + parseInt(strokeWidth),
                height:defaultsize,// 100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()
//    setdefaultsize()
}
function diFlanging1() {
//    if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {               defaultsize=30    }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="168.000000pt" height="369.000000pt" viewBox="0 0 168.000000 369.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,369.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M630 2031 c0 -1495 -2 -1661 -16 -1675 -13 -14 -52 -16 -275 -16 -160 0 -268 4 -280 10 -10 6 -19 20 -19 30 0 13 -7 20 -20 20 -19 0 -20 -7 -20 -200 l0 -200 840 0 840 0 0 170 0 170 -327 0 c-181 0 -334 4 -341 9 -13 8 -17 537 -21 2649 l-1 692 -180 0 -180 0 0 -1659z"/></g></svg>';

//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
//        d="M2704 4537 c-2 -7 -6 -522 -7 -1145 -2 -912 -5 -1134 -15 -1144 -10 -10 -78 -13 -289 -15 -218 -1 -277 -4 -284 -15 -12 -19 -11 -255 1 -263 5 -3 328 -6 717 -6 531 0 708 3 714 12 12 21 10 257 -3 266 -7 3 -129 7 -271 7 -240 1 -260 2 -272 20 -11 15 -14 213 -15 1148 0 622 -3 1134 -6 1139 -3 5 -64 9 -135 9 -98 0 -131 -3 -135 -13z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"diFlanging1()",
                width: defaultsize,//100 + parseInt(strokeWidth),
                height:defaultsize,// 100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()
}
function diFlangesotcket1() {
//    if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {            defaultsize=30     }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="240.000000pt" height="375.000000pt" viewBox="0 0 240.000000 375.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,375.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M130 3574 c0 -102 -4 -183 -10 -195 -11 -19 -3 -19 389 -19 388 0 401 -1 411 -20 7 -13 10 -327 8 -1000 l-3 -980 -463 -463 -462 -462 0 -99 0 -99 72 -71 c39 -39 82 -85 94 -103 l22 -32 473 474 c261 261 479 475 485 475 6 0 218 -209 472 -465 254 -256 465 -465 470 -465 4 0 68 61 142 135 74 74 143 135 152 135 10 0 18 7 18 15 0 8 -6 15 -14 15 -7 0 -227 214 -487 477 -261 262 -486 488 -502 503 -15 14 -32 42 -37 61 -6 21 -10 391 -10 986 0 852 2 953 16 967 13 14 63 16 394 16 309 0 379 2 383 14 3 7 6 95 6 195 l1 181 -1010 0 -1010 0 0 -176z"/></g></svg>';
//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
//        d="M2102 4398 l3 -143 271 -1 c150 -1 277 -4 283 -8 8 -5 11 -202 11 -709 l0 -701 -360 -361 c-198 -198 -360 -365 -360 -370 0 -12 182 -195 194 -195 4 0 151 144 326 319 175 175 327 322 338 326 17 5 73 -46 316 -287 162 -161 310 -308 329 -327 l35 -33 96 93 c53 51 96 97 96 103 0 6 -162 173 -360 371 l-360 361 0 701 c0 511 3 704 11 709 7 4 129 7 273 6 167 0 265 3 273 10 14 11 19 231 7 262 -6 14 -78 16 -715 16 l-710 0 3 -142z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"diFlangesotcket1()",
                width:defaultsize,// 100 + parseInt(strokeWidth),
                height:defaultsize,// 100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()
}
function diColor1() {
//    if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {         defaultsize=30     }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="249.000000pt" height="249.000000pt" viewBox="0 0 249.000000 249.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,249.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M0 1245 l0 -1245 1245 0 1245 0 0 1245 0 1245 -1245 0 -1245 0 0 -1245z m1921 695 c18 -10 19 -29 19 -705 l0 -694 -22 -15 c-20 -14 -104 -16 -710 -16 -611 0 -688 2 -702 16 -14 14 -16 92 -16 708 0 525 3 695 12 704 16 16 1389 17 1419 2z"/></g></svg>';
//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
//        d="M2343 3583 c-11 -22 -9 -1255 1 -1271 5 -9 155 -12 624 -12 339 0 627 3 640 6 l22 6 0 632 c0 478 -3 635 -12 644 -9 9 -165 12 -640 12 -588 0 -628 -1 -635 -17z m1005 -255 c9 -9 12 -107 12 -380 0 -357 -1 -368 -20 -378 -13 -7 -136 -10 -375 -8 -303 3 -357 5 -365 18 -6 9 -10 165 -10 372 0 311 2 359 16 372 13 14 61 16 373 16 264 0 360 -3 369 -12z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"diColor1()",
                width:defaultsize,// 100 + parseInt(strokeWidth),
                height:defaultsize,// 100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()
}
function diCap1() {
//    if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {          defaultsize=30;     }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="258.000000pt" height="129.000000pt" viewBox="0 0 258.000000 129.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,129.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M30 1046 c0 -153 -4 -247 -10 -251 -7 -4 -7 -12 0 -25 6 -11 10 -157 10 -379 l0 -361 238 0 c130 0 247 -3 260 -6 l22 -6 0 369 c0 274 3 372 12 381 9 9 183 12 720 12 l708 0 11 -27 c7 -18 10 -151 8 -373 l-2 -345 255 -3 255 -2 5 486 c3 267 3 551 0 630 l-5 144 -1243 0 -1244 0 0 -244z"/></g></svg>';

//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
//        d="M2216 3344 c-8 -20 -8 -747 0 -768 5 -14 29 -16 159 -16 138 0 154 2 159 18 3 9 5 113 5 230 -1 149 2 217 10 225 19 19 882 21 907 2 18 -12 19 -32 22 -244 l3 -232 157 3 157 3 0 395 0 395 -786 3 c-711 2 -787 1 -793 -14z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"diCap1()",
                width: defaultsize,//100 + parseInt(strokeWidth),
                height: defaultsize,//100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()

}
function coupler1() {
//    if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {               defaultsize=30     }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="249.000000pt" height="249.000000pt" viewBox="0 0 249.000000 249.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,249.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M0 1245 l0 -1245 1245 0 1245 0 0 1245 0 1245 -1245 0 -1245 0 0 -1245z m1921 695 c18 -10 19 -29 19 -705 l0 -694 -22 -15 c-20 -14 -104 -16 -710 -16 -611 0 -688 2 -702 16 -14 14 -16 92 -16 708 0 525 3 695 12 704 16 16 1389 17 1419 2z"/></g></svg>';

//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
//        d="M2067 4466 c-7 -18 -9 -238 -8 -1138 l1 -748 944 0 c713 0 947 3 952 12 8 13 12 108 11 293 0 72 -1 460 -1 863 l0 732 -947 0 c-800 0 -948 -2 -952 -14z m1483 -396 c13 -8 15 -78 14 -534 0 -419 -3 -527 -13 -540 -12 -14 -71 -16 -545 -16 -400 0 -535 3 -544 12 -17 17 -17 1059 0 1076 15 15 1064 17 1088 2z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"coupler1()",
                width: defaultsize,//100 + parseInt(strokeWidth),
                height: defaultsize,//100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
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
//    if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {              defaultsize=30   }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="258.000000pt" height="534.000000pt" viewBox="0 0 258.000000 534.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,534.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M1020 4922 c0 -319 -3 -422 -12 -435 -12 -15 -55 -17 -483 -17 -259 -1 -476 -4 -482 -8 -8 -5 -13 -84 -15 -270 -2 -144 0 -262 3 -262 3 0 37 -43 75 -95 38 -53 89 -122 114 -154 25 -32 52 -68 60 -81 8 -12 20 -28 25 -34 6 -6 21 -27 35 -46 13 -19 34 -48 47 -65 13 -16 66 -88 118 -160 52 -71 99 -134 103 -140 23 -29 79 -106 92 -125 8 -12 20 -28 25 -34 6 -6 28 -35 49 -65 21 -30 42 -59 47 -65 75 -93 116 -159 111 -179 -5 -21 -38 -76 -53 -87 -3 -3 -24 -30 -45 -61 -21 -31 -49 -69 -61 -85 -13 -16 -65 -87 -118 -159 -52 -71 -105 -143 -118 -159 -24 -31 -86 -116 -114 -156 -31 -44 -282 -385 -293 -397 -6 -7 -30 -41 -55 -77 l-45 -64 0 -256 0 -256 484 0 c377 0 487 -3 493 -12 4 -7 10 -211 13 -452 l5 -439 253 -1 252 -1 0 437 c0 384 2 438 16 452 14 14 73 16 500 16 l484 0 0 256 0 256 -32 46 c-18 26 -41 56 -51 67 -10 11 -34 43 -53 70 -42 60 -101 142 -131 181 -13 16 -66 88 -118 159 -53 72 -105 143 -118 159 -28 37 -86 117 -131 181 -20 28 -45 61 -56 75 -41 50 -197 266 -215 296 -18 32 -18 32 9 60 15 16 37 45 49 64 12 19 27 41 34 49 8 7 33 40 56 73 51 72 80 112 107 147 11 14 49 67 85 116 36 50 69 95 74 101 5 6 26 35 47 65 21 30 42 59 47 64 17 21 203 275 261 355 33 47 63 87 66 90 3 3 20 23 37 46 l32 41 2 253 c1 239 0 254 -18 261 -10 4 -230 8 -490 8 -456 1 -473 2 -483 20 -6 13 -10 168 -10 435 l0 416 -255 0 -255 0 0 -418z m816 -996 c6 -15 -10 -52 -33 -74 -8 -8 -32 -40 -53 -71 -22 -31 -49 -67 -59 -81 -11 -14 -80 -108 -155 -210 -75 -102 -145 -196 -156 -210 -11 -14 -33 -44 -47 -67 -34 -53 -58 -60 -89 -26 -13 14 -24 30 -24 34 0 5 -13 23 -28 41 -16 18 -46 58 -67 88 -21 30 -41 57 -44 60 -4 3 -35 46 -71 95 -60 84 -209 287 -227 310 -64 81 -72 95 -67 109 6 14 62 16 560 16 470 0 555 -2 560 -14z m-470 -1802 c38 -53 81 -110 95 -128 14 -17 43 -56 64 -86 21 -30 41 -57 44 -60 4 -3 24 -29 45 -59 21 -30 42 -59 47 -65 5 -6 49 -65 98 -131 64 -87 86 -123 79 -132 -8 -10 -132 -13 -557 -13 -300 0 -552 4 -560 9 -16 10 -14 19 17 57 36 46 256 346 273 374 9 14 27 36 39 50 12 14 37 48 56 75 18 28 44 62 56 77 13 15 39 50 58 77 51 74 64 70 146 -45z"/></g></svg>';
//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
//        d="M3078 5504 l-3 -336 -23 -19 c-22 -17 -45 -19 -388 -18 -200 0 -368 -2 -374 -6 -6 -4 -10 -82 -10 -207 l0 -201 55 -68 c30 -37 55 -73 55 -78 0 -6 14 -26 30 -44 27 -31 133 -173 347 -465 134 -183 167 -228 200 -271 58 -77 73 -46 -229 -456 -56 -77 -155 -210 -218 -295 -63 -86 -128 -175 -145 -198 -16 -23 -45 -63 -62 -88 l-33 -46 0 -187 c0 -132 4 -191 12 -199 9 -9 108 -12 384 -12 333 0 373 -2 385 -17 11 -13 15 -88 19 -357 l5 -341 193 -3 192 -2 0 338 c0 289 2 341 16 360 l15 22 369 0 c202 0 375 3 384 6 14 5 16 32 16 200 0 166 -2 199 -17 222 -17 26 -101 141 -272 372 -413 557 -448 608 -435 625 5 7 21 30 36 53 15 22 55 78 89 124 75 101 114 153 274 373 135 186 165 226 210 285 17 21 49 66 73 98 l42 60 0 195 c0 130 -4 198 -11 203 -6 3 -174 6 -373 5 -346 -2 -364 -1 -387 18 l-24 19 -5 334 -5 333 -192 3 -192 2 -3 -336z m628 -788 c23 -9 16 -53 -14 -88 -15 -18 -53 -68 -85 -110 -31 -43 -81 -111 -110 -150 -30 -40 -67 -91 -83 -114 -101 -142 -125 -167 -151 -157 -7 2 -38 40 -70 85 -32 44 -127 173 -210 286 -84 114 -153 215 -153 225 0 10 9 21 19 25 23 8 835 6 857 -2z m-404 -1388 c16 -14 50 -58 213 -283 55 -76 124 -168 153 -204 37 -46 51 -73 50 -91 l-3 -25 -440 0 -440 0 -3 22 c-2 15 12 41 40 76 39 49 177 236 323 440 57 77 76 89 107 65z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"beEndCateValue1()",
                width: defaultsize,//100 + parseInt(strokeWidth),
                height: defaultsize,//100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()

}
function di90degElbow() {
//    if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {             defaultsize=30;     }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.0" xmlns="http://www.w3.org/2000/svg"  width="834.000000pt" height="825.000000pt" viewBox="0 0 834.000000 825.000000"  preserveAspectRatio="xMidYMid meet"><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011 </metadata> <g transform="translate(0.000000,825.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"> <path d="M272 7867 c-116 -117 -212 -219 -212 -227 0 -20 330 -350 350 -350 8 0 251 -236 540 -525 289 -289 531 -525 538 -525 7 0 33 -19 57 -41 l45 -42 0 -2163 0 -2164 124 0 124 0 26 -31 c25 -30 26 -35 26 -165 l0 -134 2252 0 2252 0 256 -255 c140 -140 262 -255 270 -255 8 0 218 -202 465 -450 315 -315 456 -450 470 -449 12 1 108 92 235 221 l214 219 -219 219 c-121 121 -227 220 -235 220 -8 0 -229 215 -492 477 l-478 478 57 57 c32 32 64 58 73 58 8 0 251 236 540 525 289 289 532 525 540 525 20 0 200 181 200 200 0 20 -421 440 -440 440 -8 0 -164 -148 -345 -330 -181 -181 -337 -330 -345 -330 -8 0 -231 -216 -495 -480 l-480 -480 -1965 0 c-1516 0 -1969 3 -1978 12 -9 9 -12 472 -12 2024 l0 2011 45 42 c24 22 50 41 57 41 7 0 249 236 538 525 289 289 532 525 540 525 9 0 91 75 183 168 142 142 167 172 167 197 0 26 -29 59 -198 228 -168 168 -202 197 -227 197 -26 0 -82 -52 -435 -405 -223 -223 -412 -405 -420 -405 -8 0 -137 -121 -285 -270 -148 -148 -277 -270 -285 -270 -8 0 -137 122 -285 270 -148 149 -277 270 -285 270 -8 0 -197 182 -420 405 -223 223 -412 405 -420 405 -8 0 -111 -96 -228 -213z"/> </g> </svg>';

//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.0" xmlns="http://www.w3.org/2000/svg"  width="600.000000pt" height="540.000000pt" viewBox="0 0 600.000000 540.000000"  preserveAspectRatio="xMidYMid meet"> <metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,540.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"> <path d="M1325 4169 c-49 -48 -75 -80 -75 -94 0 -15 26 -46 81 -96 44 -41 165 -157 268 -259 104 -102 200 -196 215 -209 l26 -23 0 -843 c0 -928 -5 -855 60 -855 39 0 60 -24 60 -69 0 -20 5 -42 12 -49 9 -9 220 -12 886 -12 l874 0 206 -199 c114 -110 241 -233 282 -275 41 -42 78 -76 83 -76 16 0 157 143 157 159 0 8 -119 132 -266 274 -168 165 -264 266 -262 276 2 9 120 131 263 271 143 139 262 260 263 268 5 19 -128 152 -151 152 -17 0 -59 -39 -456 -426 -102 -100 -196 -187 -208 -193 -15 -8 -241 -11 -777 -11 -673 0 -756 2 -770 16 -14 14 -16 101 -16 806 l0 790 53 49 c123 115 547 536 547 545 0 12 -144 154 -155 154 -5 0 -77 -66 -161 -147 -232 -227 -378 -364 -396 -374 -19 -10 -58 26 -407 369 -85 83 -156 152 -158 152 -1 0 -36 -32 -78 -71z"/></g></svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"di90degElbow()",
                width: defaultsize,//100 + parseInt(strokeWidth),
                height: defaultsize,//100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()

}
function di45DegElbow() {
//    if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {             defaultsize=30;     }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="600.000000pt" height="393.000000pt" viewBox="0 0 600.000000 393.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,393.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M1080 3437 c0 -390 -3 -497 -13 -511 -12 -17 -43 -18 -472 -17 -324 1 -462 -2 -472 -10 -19 -17 -19 -292 1 -308 10 -8 158 -11 531 -11 l518 1 25 -23 c95 -89 274 -276 322 -338 32 -41 106 -122 164 -179 68 -68 107 -115 112 -134 3 -16 29 -52 57 -80 28 -29 67 -70 85 -92 19 -22 136 -143 260 -270 125 -126 247 -256 272 -288 25 -32 94 -108 153 -170 94 -96 111 -109 125 -99 47 34 76 51 82 47 4 -3 10 -27 14 -55 5 -37 11 -50 24 -51 9 -1 487 -2 1062 -4 575 -1 1052 -3 1061 -4 16 -1 92 -70 108 -98 4 -9 72 -80 150 -158 90 -91 145 -155 153 -176 6 -19 28 -53 49 -75 20 -23 44 -55 52 -70 8 -16 56 -69 106 -117 81 -80 92 -87 108 -75 33 24 203 209 203 221 0 7 -88 100 -196 207 -190 189 -210 212 -275 313 -17 26 -34 47 -39 47 -5 0 -14 17 -21 37 -6 21 -34 62 -60 91 -27 30 -49 59 -49 66 0 7 22 37 50 67 31 34 52 67 56 89 8 42 74 120 274 321 179 180 262 275 258 293 -4 17 -194 216 -206 216 -12 0 -190 -175 -237 -233 -57 -69 -75 -99 -75 -123 0 -13 -70 -91 -199 -221 -110 -111 -206 -215 -215 -231 -8 -16 -29 -41 -46 -56 l-32 -26 -985 0 -986 0 -31 36 c-17 19 -112 117 -210 217 -99 100 -209 218 -244 262 -36 44 -158 175 -272 290 -115 116 -226 230 -247 255 -21 25 -65 70 -96 101 -35 35 -57 65 -57 79 0 15 -44 68 -139 164 -76 78 -145 155 -154 171 -8 17 -25 39 -37 50 -51 46 -50 36 -50 615 l0 540 -160 0 -160 0 0 -493z"/></g></svg>';

//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.0" xmlns="http://www.w3.org/2000/svg"  width="600.000000pt" height="561.000000pt" viewBox="0 0 600.000000 561.000000"  preserveAspectRatio="xMidYMid meet"> <metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,561.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"> <path d="M1192 4372 c-11 -7 -13 -84 -12 -383 0 -326 -2 -377 -15 -396 l-16 -23 -383 0 c-316 0 -386 -2 -395 -14 -8 -9 -11 -48 -9 -112 l3 -99 440 -5 c242 -3 442 -7 445 -8 2 -1 286 -283 630 -627 344 -344 629 -625 633 -625 5 0 21 7 38 15 34 18 49 13 49 -19 0 -12 5 -27 12 -34 9 -9 222 -12 898 -12 l886 0 209 -211 c116 -115 247 -247 293 -291 l83 -81 80 79 c43 43 79 84 79 90 0 6 -80 92 -177 191 -302 305 -353 360 -353 383 0 15 85 106 270 291 149 148 270 273 270 277 0 11 -154 162 -165 162 -6 0 -157 -147 -337 -327 l-328 -328 -798 -3 c-438 -2 -812 0 -831 3 -27 5 -137 110 -650 622 l-616 615 -5 436 -5 437 -105 3 c-58 1 -111 -2 -118 -6z"/> </g> </svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"di45DegElbow()",
                width: defaultsize,//100 + parseInt(strokeWidth),
                height: defaultsize,//100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()

}
function diReducer() {
    // if(defaultflag==1)     {     defaultsize=30+ defaultsize;     }     else     {              defaultsize=30;     }
    canvas.isDrawingMode = false;
    mode = 'image';
    var svg = '<?xml version="1.0" standalone="no"?><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="255.000000pt" height="354.000000pt" viewBox="0 0 255.000000 354.000000" preserveAspectRatio="xMidYMid meet"><script id="tinyhippos-injected"/><metadata>Created by potrace 1.10, written by Peter Selinger 2001-2011</metadata><g transform="translate(0.000000,354.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M0 3251 c0 -237 3 -294 15 -317 8 -16 15 -48 15 -71 0 -23 5 -54 11 -70 6 -15 14 -55 19 -88 19 -120 32 -191 46 -239 8 -26 14 -66 14 -88 0 -22 5 -48 10 -59 6 -10 15 -47 20 -82 18 -120 32 -193 46 -241 8 -26 14 -62 14 -80 0 -17 7 -53 15 -80 8 -27 15 -65 15 -83 0 -19 5 -43 11 -54 6 -11 14 -50 19 -87 9 -68 23 -140 42 -222 5 -25 14 -70 19 -100 20 -127 32 -188 45 -234 8 -26 14 -65 14 -86 0 -21 7 -54 15 -73 8 -20 15 -54 15 -75 0 -22 4 -52 10 -68 12 -35 31 -145 33 -194 l2 -35 -232 -3 -233 -2 0 -260 0 -260 1250 0 1250 0 2 247 3 247 25 13 c21 10 -18 13 -234 15 -301 3 -290 -1 -264 95 17 59 32 139 47 248 5 33 14 73 20 88 6 16 11 44 11 64 0 20 6 59 14 87 14 48 24 104 45 236 5 30 14 75 19 100 17 72 31 149 43 230 5 41 14 79 20 84 5 6 9 27 9 49 0 21 6 66 14 100 23 95 36 166 46 237 4 36 13 78 19 93 6 16 11 44 11 63 0 19 7 51 15 71 8 19 15 51 15 70 0 19 7 65 15 102 26 121 36 175 44 241 5 36 14 78 20 93 6 16 11 43 11 60 0 18 7 45 15 61 12 23 15 77 15 287 l0 259 -1250 0 -1250 0 0 -289z m1938 -253 c16 -16 15 -67 -3 -102 -8 -15 -15 -45 -15 -65 0 -20 -5 -54 -11 -76 -13 -52 -38 -184 -49 -260 -5 -33 -13 -73 -19 -88 -6 -16 -11 -43 -11 -62 0 -18 -6 -55 -14 -81 -13 -46 -25 -107 -45 -234 -5 -30 -14 -75 -20 -100 -15 -65 -31 -146 -41 -215 -5 -33 -13 -73 -19 -88 -6 -16 -11 -43 -11 -62 0 -18 -6 -55 -14 -81 -13 -46 -25 -107 -45 -234 -5 -30 -14 -75 -20 -100 -15 -62 -31 -144 -41 -210 -4 -30 -13 -75 -18 -100 -17 -71 -32 -150 -41 -210 -13 -88 -19 -99 -54 -105 -41 -8 -423 -2 -432 6 -3 4 -8 24 -11 45 -5 40 -21 123 -49 250 -8 40 -15 86 -15 104 0 17 -5 41 -11 52 -7 12 -15 47 -19 77 -17 118 -32 198 -46 241 -8 25 -14 59 -14 76 0 17 -5 44 -10 60 -6 16 -14 58 -20 94 -5 36 -14 90 -20 120 -6 30 -18 89 -26 130 -9 41 -20 95 -26 120 -5 25 -14 70 -18 100 -9 65 -27 157 -42 220 -6 25 -14 68 -18 95 -9 57 -27 150 -42 215 -5 25 -14 70 -18 100 -10 61 -23 130 -45 231 -18 81 -19 121 -3 137 17 17 1359 17 1376 0z"/></g></svg>';

//    var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" width="600.000000pt" height="594.000000pt" viewBox="0 0 600.000000 594.000000"  preserveAspectRatio="xMidYMid meet"> <metadata> Created by potrace 1.10, written by Peter Selinger 2001-2011 </metadata> <g transform="translate(0.000000,594.000000) scale(0.100000,-0.100000)" fill="' + color + '" stroke="none"><path d="M2273 4308 c-6 -7 -11 -84 -12 -173 -1 -157 4 -198 45 -390 9 -38 21 -110 29 -160 8 -49 19 -110 25 -135 5 -25 19 -94 30 -155 11 -60 23 -117 26 -125 3 -8 10 -40 15 -70 38 -219 50 -285 115 -598 45 -224 52 -212 -125 -212 -124 0 -141 -2 -149 -17 -13 -25 -14 -281 -2 -304 10 -18 33 -19 824 -19 733 0 814 2 820 16 3 9 6 78 6 154 0 76 -3 145 -6 154 -5 14 -27 16 -139 16 -73 0 -140 3 -149 6 -19 7 -21 50 -5 109 6 22 15 67 20 100 13 86 38 224 49 265 11 45 24 112 56 290 36 207 54 292 64 315 5 11 14 58 19 105 5 47 14 105 20 130 45 188 64 323 68 489 2 96 2 185 -2 198 l-5 23 -814 0 c-639 0 -816 -3 -823 -12z m1288 -325 c15 -15 15 -55 0 -118 -7 -27 -19 -86 -26 -130 -28 -158 -35 -192 -70 -360 -20 -93 -43 -213 -50 -265 -8 -52 -22 -124 -30 -160 -9 -36 -22 -101 -29 -145 -32 -183 -87 -452 -97 -478 -6 -15 -21 -30 -33 -33 -11 -2 -81 -3 -155 -2 l-135 3 -17 45 c-17 45 -31 118 -52 265 -7 44 -15 85 -18 90 -4 6 -11 39 -18 75 -12 67 -45 241 -62 320 -5 25 -14 79 -19 120 -5 41 -14 91 -20 110 -14 52 -36 158 -65 320 -14 80 -33 177 -41 215 -23 109 -22 122 9 134 37 14 914 8 928 -6z"/></g> </svg>';
    var encoded = window.btoa(svg);
    fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
        function (oImg) {
            oImg.set({
                id:"diReducer()",
                width: defaultsize,//100 + parseInt(strokeWidth),
                height: defaultsize,//100 + parseInt(strokeWidth),
                left: obj_left,
                top: obj_top
            });
            canvas.add(oImg);
        });
    selector()

}
function eraser() {
    mode = 'pencil';
    canvas.isDrawingMode = true;
    canvas.freeDrawingBrush.width = $('#drawing-line-width').val();
    canvas.freeDrawingBrush.color = 'white';
}