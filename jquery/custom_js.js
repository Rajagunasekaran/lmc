$('#drawing-color').change(function () {
                color = this.value;
                canvas.freeDrawingBrush.color = this.value;
                pencil();
            });
            $('#drawing-line-width').change(function () {
                strokeWidth = parseInt(this.value, 10) || 1;
                pencil();
            });
            var mode, color = '#36bac9', fillColor = false, strokeWidth = 5;

            var drawingColorEl = $('drawing-color'),
                    drawingLineWidthEl = $('drawing-line-width');
            drawingColorEl.onchange = function () {
                color = this.value;
                canvas.freeDrawingBrush.color = this.value;
                pencil();
            };
            drawingLineWidthEl.onchange = function () {
                strokeWidth = parseInt(this.value, 10) || 1;
                pencil();

            };

            var canvas = new fabric.Canvas('canvas');
            function onObjectSelected(e)
            {
                if (fillColor == true) {
                    e.target.setFill(color);
                    fillColor = false;
                }
            }
            ;
            canvas.on({
                'object:selected': onObjectSelected,
            });
            var imageLoader = document.getElementById('imageLoader');
            imageLoader.addEventListener('change', handleImage, false);
            function handleImage(e) {
                mode = 'image';
                var reader = new FileReader();
                reader.onload = function (event) {
                    var img = new Image();
                    img.onload = function () {
                        var imgInstance = new fabric.Image(img, {
                            scaleX: 1,
                            scaleY: 1
                        })
                        canvas.add(imgInstance);
                    }
                    img.src = event.target.result;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
            function handleRemove() {
                canvas.clear().renderAll();
            }
            function text() {
                canvas.isDrawingMode = false;
                canvas.add(new fabric.IText('Custom shape cant change color dynamically only others', {
                    fontFamily: 'RED',
                    fontSize: 10,
                    left: 50,
                    top: 10,
                }));
            }
            var line, isDown;
            function line() {
                fillColor = false;
                canvas.isDrawingMode = false;
                mode = 'line';
                canvas.on('mouse:down', function (o) {
                    if (mode == 'line') {
                        isDown = true;
                        var pointer = canvas.getPointer(o.e);
                        var points = [pointer.x, pointer.y, pointer.x, pointer.y];
                        line = new fabric.Line(points, {
                            strokeWidth: strokeWidth,
                            stroke: color,
                            originX: 'center',
                            originY: 'center'
                        });
                        canvas.add(line);
                    }
                });
                canvas.on('mouse:move', function (o) {
                    if (mode == 'line') {
                        if (!isDown)
                            return;
                        var pointer = canvas.getPointer(o.e);
                        line.set({x2: pointer.x, y2: pointer.y});
                        canvas.renderAll();
                    }
                });
                canvas.on('mouse:up', function (o) {
                    if (mode == 'line') {
                        isDown = false;
                    }
                });
            }
            function circle() {
                fillColor = false;
                canvas.isDrawingMode = false;
                mode = 'circle';
                canvas.add(new fabric.Circle({
                    left: 50,
                    top: 10,
                    radius: 30,
                    fill: 'transparent',
                    stroke: color,
                    strokeWidth: strokeWidth
                }));
            }
            drawingColorEl.onchange = function () {
                canvas.freeDrawingBrush.color = this.value;
            };
            function save() {
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
            function image() {
                canvas.isDrawingMode = false;
                mode = 'image';
                fabric.Image.fromURL('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAVwAAAFLCAMAAACtG8Q9AAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAnUExURf///wD/AM3/zTz/PAz/DCP/I7//v4n/iWn/aef/56D/oMb/xgf/B2HKPZAAAASgSURBVHgB7d3NbhNZAITRJCRDCLz/8w4/uqvPmiC5WjBwenOlkl2xT5dZECl+eHARIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIEDg1wo8XXD92nf0G/30x+fl9fj1+vLhN3p7v/alfOMYX3DPLR3Dfq2z3GP7sMd9tNyjC/dIXHDCvQD1VMI9EheccC9APZVwj8QFJ9wLUE8l3CNxwQn3AtRTCfdIXHDCvQD1VMI9EheccC9APZVwj8QF5+vHd67ov7zzhI9vF7zMP7QyuP5HcXen4e4s0wQ3JLsA7s4yTXBDsgvg7izTBDckuwDuzjJNcEOyC+DuLNMENyS7AO7OMk1wQ7IL4O4s0wQ3JLsA7s4yTXBDsgvg7izTBDckuwDuzjJNcEOyC+DuLNMENyS7AO7OMk1wQ7IL4O4s0wQ3JLsA7s4yTXBDsgvg7izTBDckuwDuzjJNcEOyC+DuLNMENyS7AO7OMk1wQ7IL4O4s0wQ3JLsA7s4yTXBDsgvg7izTBDckuwDuzjJNcEOyC+DuLNMENyS7AO7OMk1wQ7IL4O4s0wQ3JLsA7s4yTXBDsgvg7izTBDckuwDuzjJNcEMyCz7BnVmm6HNwV8Hz68en/Li/LFhZpuf1L4O89XaDsgrgPlzx5T0/bg9cuLc+zbNs9a9AeizXcmcrvVWUxa0Cy7XcW4ObZauhpsdyLXe20ltFWdwqsFzLvTW4WbYaanos13JnK71VlMWtAsu13FuDW2X9TYTlrmwf8puILy//bK5Ps9f4/y3KUH3x0e5mwt1ZpgluSHYB3J1lmuCGZBfA3VmmCW5IdgHcnWWa4IZkF8DdWaYJbkh2AdydZZrghmQXwN1ZpgluSHYB3J1lmuCGZBfA3VmmCW5IdgHcnWWa4IZkF8DdWaYJbkh2AdydZZrghmQXwN1ZpgluSHYB3J1lmuCGZBfA3VmmCW5IdgHcnWWa4IZkF8DdWaYJbkh2AdydZZrghmQXwN1ZpgluSHYB3J1lmuCGZBfA3VmmCW5IdgHcnWWa4IZkF8DdWaYJbkh2AdydZZrghmQXwN1ZpgluSHYB3J1lmuCGZBfA3VmmCW5IdgHcnWWa4Ibkp4On967ivveMz98e8NMv4E9+4OPzO1dw/+sZ3x/8/PrG9vtmand34u8Rnk/j3ZQtgAv3CFx4dnh3J5Z77tfdlC2AC/cIXHh2eHcnlnvu192ULYAL9whceHZ4dyeWe+7X3ZQtgAv3CFx4dnh3J5Z77tfdlC2AC/cIXHh2eHcnlvvtfj29vX542V++i+vCD4NqAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAguBfwG84yP+fjB12QAAAABJRU5ErkJggg==',
                        function (oImg) {
                            oImg.set({
                                width: 30,
                                height: 30,
                                left: 50,
                                top: 10,
                                //height: 700,
                                //width: 700,                                
                            });
                            canvas.add(oImg);
                        });
            }
            //coding for pencil
            function pencil() {
                canvas.isDrawingMode = true;
                canvas.freeDrawingBrush.width = strokeWidth;
                canvas.freeDrawingBrush.color = color;
            }
            function rectangle() {
                var rect = new fabric.Rect({
                    left: 100,
                    top: 50,
                    width: 100,
                    height: 100,
                    stroke: color,
                    fill: 'transparent',
                    strokeWidth: strokeWidth
                });
                canvas.add(rect);
            }
            function setColor() {
                canvas.isDrawingMode = false;
                fillColor = true;
            }
            function clearCanvas() {
                canvas.clear();
            }
            function selector() {
                canvas.isDrawingMode = false;
            }
            function tappingTee1() {
                canvas.isDrawingMode = false;
                var svg, jsonCanvas;
//    svg = new String('<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="#FF0000" stroke="none"><path fill="url(#SVGID_1_)" d="M2460 4401 l0 -280 -312 -5 -311 -6 -2 -166 -1 -166 210 -284 c307 -416 356 -486 356 -514 0 -13 -126 -197 -281 -407 l-280 -382 5 -167 6 -167 300 -4 300 -3 6 -285 5 -285 169 0 169 0 5 285 6 285 294 3 c354 5 336 -4 336 170 l0 141 -134 183 c-74 101 -209 285 -301 410 l-165 227 74 103 c41 57 174 239 295 404 l221 302 -1 158 -1 159 -314 6 -314 5 0 280 0 279 -170 0 -170 0 0 -279z m520 -641 c0 -20 -336 -480 -350 -480 -14 0 -350 460 -350 480 0 13 132 20 350 20 218 0 350 -7 350 -20z m-165 -1311 c91 -126 165 -238 165 -249 0 -21 -684 -30 -705 -9 -15 15 329 489 355 488 11 0 94 -104 185 -230z"/></g></svg>');

//    canvas = new fabric.Canvas('canvas');
//canvas = new fabric.Canvas('canvas');
//    fabric.loadSVGFromString(svg, function(objects, options) {
//      var obj = fabric.util.groupSVGElements(objects, options);
//      canvas.add(obj).centerObject(obj).renderAll();
//      obj.setCoords();
//    });
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M1744 4377 c-2 -7 -3 -69 -2 -138 l3 -124 269 -3 c178 -1 274 -6 283 -13 11 -9 13 -216 13 -1163 0 -707 -4 -1157 -10 -1166 -8 -13 -54 -16 -282 -20 l-273 -5 0 -135 0 -135 705 0 705 0 3 137 3 138 -264 0 c-211 0 -267 3 -283 14 -18 14 -19 31 -19 505 0 389 3 493 13 503 10 10 140 14 615 18 l602 5 0 140 0 140 -607 5 c-333 3 -610 9 -615 13 -4 5 -9 223 -11 485 -1 372 1 483 11 502 l13 25 269 5 270 5 0 135 0 135 -703 3 c-572 2 -704 0 -708 -11z"/></g></svg>';
                var encoded = window.btoa(svg);
//document.body.style.background = "url(data:image/svg+xml;base64,"+encoded+")";
//        canvas = new fabric.Canvas('canvas');
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30,
                                height: 30,
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
//                canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAVwAAAFLCAMAAACtG8Q9AAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAnUExURf///wD/AM3/zTz/PAz/DCP/I7//v4n/iWn/aef/56D/oMb/xgf/B2HKPZAAAASgSURBVHgB7d3NbhNZAITRJCRDCLz/8w4/uqvPmiC5WjBwenOlkl2xT5dZECl+eHARIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIEDg1wo8XXD92nf0G/30x+fl9fj1+vLhN3p7v/alfOMYX3DPLR3Dfq2z3GP7sMd9tNyjC/dIXHDCvQD1VMI9EheccC9APZVwj8QFJ9wLUE8l3CNxwQn3AtRTCfdIXHDCvQD1VMI9EheccC9APZVwj8QF5+vHd67ov7zzhI9vF7zMP7QyuP5HcXen4e4s0wQ3JLsA7s4yTXBDsgvg7izTBDckuwDuzjJNcEOyC+DuLNMENyS7AO7OMk1wQ7IL4O4s0wQ3JLsA7s4yTXBDsgvg7izTBDckuwDuzjJNcEOyC+DuLNMENyS7AO7OMk1wQ7IL4O4s0wQ3JLsA7s4yTXBDsgvg7izTBDckuwDuzjJNcEOyC+DuLNMENyS7AO7OMk1wQ7IL4O4s0wQ3JLsA7s4yTXBDsgvg7izTBDckuwDuzjJNcEOyC+DuLNMENyS7AO7OMk1wQ7IL4O4s0wQ3JLsA7s4yTXBDsgvg7izTBDckuwDuzjJNcEMyCz7BnVmm6HNwV8Hz68en/Li/LFhZpuf1L4O89XaDsgrgPlzx5T0/bg9cuLc+zbNs9a9AeizXcmcrvVWUxa0Cy7XcW4ObZauhpsdyLXe20ltFWdwqsFzLvTW4WbYaanos13JnK71VlMWtAsu13FuDW2X9TYTlrmwf8puILy//bK5Ps9f4/y3KUH3x0e5mwt1ZpgluSHYB3J1lmuCGZBfA3VmmCW5IdgHcnWWa4IZkF8DdWaYJbkh2AdydZZrghmQXwN1ZpgluSHYB3J1lmuCGZBfA3VmmCW5IdgHcnWWa4IZkF8DdWaYJbkh2AdydZZrghmQXwN1ZpgluSHYB3J1lmuCGZBfA3VmmCW5IdgHcnWWa4IZkF8DdWaYJbkh2AdydZZrghmQXwN1ZpgluSHYB3J1lmuCGZBfA3VmmCW5IdgHcnWWa4IZkF8DdWaYJbkh2AdydZZrghmQXwN1ZpgluSHYB3J1lmuCGZBfA3VmmCW5IdgHcnWWa4Ibkp4On967ivveMz98e8NMv4E9+4OPzO1dw/+sZ3x/8/PrG9vtmand34u8Rnk/j3ZQtgAv3CFx4dnh3J5Z77tfdlC2AC/cIXHh2eHcnlnvu192ULYAL9whceHZ4dyeWe+7X3ZQtgAv3CFx4dnh3J5Z77tfdlC2AC/cIXHh2eHcnlvvtfj29vX542V++i+vCD4NqAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAguBfwG84yP+fjB12QAAAABJRU5ErkJggg==',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });
            }
//            function tappingTee1() {
//                canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUYAAAEhCAMAAAA0+PkgAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAA2UExURf///zD/MDz/PE7/Tgb/Brv/u17/XgD/ABD/EJn/meb766v/q4f/hwL/Apub/1hY/2pq/wAA/2nZ3sgAAAMiSURBVHgB7d3LbhpBEAVQwEyACcTJ//9s/Fh1WZaVmktk1KcXlkpyXTNnri15FrDbOQQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQI3F9gudzlrPd/5d/qJzzdjrlzO72e82U2xN3u6e3Kk1+Oh2/Vk//zYvKMJ4yRVmLE2Pwb4Je6CTeuYRw9mhPGJty4hnH0aE4Ym3DjGsbRozlhbMKNaxhHj+aEsQk3rmEcPZoTxibcsLYeIv9GDyGzPZpYl+uPr89A9DLczl/sXIYbZXgXqIxTPk/cXgaM2w1fEjBijAhEQrQRY0QgEqKNGCMCkZAPbTxHYmcLqYxHjJ0KVMYTRowdgciONmKMCERCtBFjRCASoo0YIwKREG3EGBGIhGgjxohAJEQbMUYEIiHaiDEiEAnRRowRgUiINmKMCERCtBFjRCASoo0YIwKREG3EGBGIhGjjlIzr78hlp0MerY3Pf9ICkbxHY1x/RS47HfJojOnrD+VhjEBixBgRiIRoI8aIQCREGzFGBCIh2ogxIhAJ0cb7MO4jsbOFfGjjbO9skrnhGCOOGDFGBCIh2ogxIhAJ0cZ/YbzuPzmV8Xb85Bv3h+sy34dKFOPt70B4K4lTjtsZT1O6lYvGWEB6I8aeW9nCWEB6I8aeW9nCWEB6I8aeW9nCWEB6I8aeW9nCWEB6I8aeW9nCWEB6I8aeW9nCWEB6I8ae27C1Xvavj2fPW44HZeuy/Fw2n+HGGAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECMwj8BWILabN9+vJ+AAAAAElFTkSuQmCC',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left:50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });
//            }
            function tJoint1() {
                canvas.isDrawingMode = false;
                mode = 'image';
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M2106 3474 c-8 -20 -8 -248 0 -268 5 -14 41 -16 293 -16 232 0 291 -3 307 -14 18 -14 19 -33 22 -537 2 -457 1 -525 -13 -545 -15 -24 -16 -24 -220 -24 -192 0 -205 -1 -215 -19 -16 -30 -13 -266 3 -280 16 -13 1154 -15 1175 -2 9 6 12 45 10 152 l-3 144 -207 3 c-186 2 -208 4 -217 20 -5 10 -9 233 -9 542 0 464 1 527 15 543 15 15 42 17 284 17 258 0 269 1 279 20 6 12 9 71 8 147 l-3 128 -751 3 c-679 2 -752 1 -758 -14z"/></g></svg>';
                var encoded = window.btoa(svg);
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
//                fabric.Image.fromURL('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUYAAAEhCAMAAAA0+PkgAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAA2UExURf///zD/MDz/PE7/Tgb/Brv/u17/XgD/ABD/EJn/meb766v/q4f/hwL/Apub/1hY/2pq/wAA/2nZ3sgAAAMiSURBVHgB7d3LbhpBEAVQwEyACcTJ//9s/Fh1WZaVmktk1KcXlkpyXTNnri15FrDbOQQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQI3F9gudzlrPd/5d/qJzzdjrlzO72e82U2xN3u6e3Kk1+Oh2/Vk//zYvKMJ4yRVmLE2Pwb4Je6CTeuYRw9mhPGJty4hnH0aE4Ym3DjGsbRozlhbMKNaxhHj+aEsQk3rmEcPZoTxibcsLYeIv9GDyGzPZpYl+uPr89A9DLczl/sXIYbZXgXqIxTPk/cXgaM2w1fEjBijAhEQrQRY0QgEqKNGCMCkZAPbTxHYmcLqYxHjJ0KVMYTRowdgciONmKMCERCtBFjRCASoo0YIwKREG3EGBGIhGgjxohAJEQbMUYEIiHaiDEiEAnRRowRgUiINmKMCERCtBFjRCASoo0YIwKREG3EGBGIhGjjlIzr78hlp0MerY3Pf9ICkbxHY1x/RS47HfJojOnrD+VhjEBixBgRiIRoI8aIQCREGzFGBCIh2ogxIhAJ0cb7MO4jsbOFfGjjbO9skrnhGCOOGDFGBCIh2ogxIhAJ0cZ/YbzuPzmV8Xb85Bv3h+sy34dKFOPt70B4K4lTjtsZT1O6lYvGWEB6I8aeW9nCWEB6I8aeW9nCWEB6I8aeW9nCWEB6I8aeW9nCWEB6I8aeW9nCWEB6I8aeW9nCWEB6I8ae27C1Xvavj2fPW44HZeuy/Fw2n+HGGAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECMwj8BWILabN9+vJ+AAAAAElFTkSuQmCC',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });
            }
            function stubBlang1() {
                canvas.isDrawingMode = false;
                mode = 'image';
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M2757 4603 c-4 -3 -7 -561 -7 -1238 0 -1181 -1 -1233 -18 -1248 -16 -15 -53 -17 -315 -17 -163 0 -298 -4 -301 -8 -6 -11 -10 -291 -4 -297 9 -10 1540 -7 1549 2 5 5 8 74 7 154 l-3 144 -292 5 c-192 3 -296 9 -303 16 -8 8 -11 362 -11 1239 1 886 -2 1232 -10 1242 -9 10 -46 13 -149 13 -75 0 -140 -3 -143 -7z"/></g></svg>';
                var encoded = window.btoa(svg);
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
//                canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAT0AAAFACAMAAAD5+VuwAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAkUExURf///wD/ADz/PCD/IM3/zRD/EA3/DU7/Tnf/d6//r/H/8Tj/ONZ5JXsAAAPbSURBVHgB7d3BSqNREIRRo6OJ5v3fdwZGNxZ9u0Dc6Pk3SvUXQg64DD48eAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIEDg9v483d9/eWXSC1w+P/f+tcrPeJdHJr0Avd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3yjL0/HeSRBqX0HseU4cQCL2XSAyjAL2RpjjQK5DGhN5IUxzoFUhjQm+kKQ70CqQxoTfSFAd6BdKY0BtpigO9AmlM6I00xYFegTQm9Eaa4kCvQBoTeiNNcaBXII0JvZGmONArkMaE3khTHOgVSGNCb6QpDvQKpDGhN9IUB3oF0pjQG2mKA70CaUzojTTFgV6BNCb0RpriQK9AGhN6I01xoFcgjQm9kaY40CuQxoTeSFMc6BVIY0JvpCkO9AqkMaE30hSH0PNdoULtIwm9x4+Ln7sAvd1oLujNNvuF3m40F/Rmm/1CbzeaC3qzzZ/1Cb37+pJ/wdv8lj/ocn3entC7vIwv+d9eb78E7+GaOF9dftF3x79B7/KD/jSXj0JvATqe6R15liO9Beh4pnfkWY70FqDjmd6RZznSW4COZ3pHnuVIbwE6nukdeZYjvQXoeKZ35FmO9Bag45nekedwfHu9XZ++4Tm8pRMBAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgS+IPAXy8oUbCkm/s4AAAAASUVORK5CYII=',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });
            }
            function reducer1() {
//                canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAT0AAAFACAMAAAD5+VuwAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAkUExURf///wD/ADz/PCD/IM3/zRD/EA3/DU7/Tnf/d6//r/H/8Tj/ONZ5JXsAAAPbSURBVHgB7d3BSqNREIRRo6OJ5v3fdwZGNxZ9u0Dc6Pk3SvUXQg64DD48eAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIEDg9v483d9/eWXSC1w+P/f+tcrPeJdHJr0Avd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3ypJemvQLvd4qS3pp0i/0eqss6aVJv9DrrbKklyb9Qq+3yjL0/HeSRBqX0HseU4cQCL2XSAyjAL2RpjjQK5DGhN5IUxzoFUhjQm+kKQ70CqQxoTfSFAd6BdKY0BtpigO9AmlM6I00xYFegTQm9Eaa4kCvQBoTeiNNcaBXII0JvZGmONArkMaE3khTHOgVSGNCb6QpDvQKpDGhN9IUB3oF0pjQG2mKA70CaUzojTTFgV6BNCb0RpriQK9AGhN6I01xoFcgjQm9kaY40CuQxoTeSFMc6BVIY0JvpCkO9AqkMaE30hSH0PNdoULtIwm9x4+Ln7sAvd1oLujNNvuF3m40F/Rmm/1CbzeaC3qzzZ/1Cb37+pJ/wdv8lj/ocn3entC7vIwv+d9eb78E7+GaOF9dftF3x79B7/KD/jSXj0JvATqe6R15liO9Beh4pnfkWY70FqDjmd6RZznSW4COZ3pHnuVIbwE6nukdeZYjvQXoeKZ35FmO9Bag45nekedwfHu9XZ++4Tm8pRMBAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgS+IPAXy8oUbCkm/s4AAAAASUVORK5CYII=',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left:50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });
                canvas.isDrawingMode = false;
                mode = 'image';
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M2236 4164 c-13 -33 -6 -299 9 -374 9 -41 22 -113 29 -160 7 -47 23 -128 35 -180 11 -52 25 -124 30 -160 6 -36 19 -105 29 -155 11 49 23 -112 27 -140 4 -27 15 -86 26 -130 10 -44 23 -109 28 -145 5 -36 21 -117 34 -180 33 -154 39 -202 27 -220 -7 -12 -39 -16 -142 -20 l-133 -5 -3 -148 -3 -147 768 2 768 3 0 145 0 145 -132 5 c-159 6 -172 13 -155 82 6 24 20 93 31 153 12 61 30 160 42 220 11 61 30 160 40 220 11 61 26 137 34 170 7 33 21 107 30 165 9 58 22 128 30 155 7 28 16 75 20 105 7 61 40 227 55 280 11 38 14 288 4 314 -6 14 -82 16 -764 16 -682 0 -758 -2 -764 -16z m1174 -307 c17 -9 24 -20 23 -37 -3 -38 -49 -284 -61 -321 -6 -19 -13 -59 -17 -89 -3 -30 -17 -109 -30 -175 -37 -181 -75 -381 -90 -465 -7 -41 -20 -111 -29 -155 -9 -44 -24 -124 -34 -178 -11 -58 -25 -105 -36 -115 -13 -13 -39 -18 -128 -20 -164 -5 -158 -11 -197 203 -11 61 -30 157 -41 215 -11 58 -36 188 -55 290 -20 102 -47 241 -60 310 -14 69 -29 150 -34 180 -5 30 -21 113 -35 183 -27 131 -26 173 2 180 10 3 193 5 407 6 299 0 396 -2 415 -12z"/></g></svg>';
                var encoded = window.btoa(svg);
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
            }
            function lastDegelbow1() {
//                canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAATUAAAFDCAMAAABsumnqAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAA2UExURf////3//Xr/ejz/PKv/qxD/EE7/TgD/AEX/RQr/Cm3/bVr/WnD/cNf/1wX/Bff/95H/kR3/HeVducIAAASiSURBVHgB7dzdUqNKFAZQiAqJMT++/8sePDUXU0PvZvwqlmOyuryxtzsJy68hQXAYDAIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIEvkjgcnr+knEav+gF/xsPO51fbjcO8zLO79fn+zYbhuljQ287nv6NPHzlq6CW6FKjlggkPbJGLRFIemSNWiKQ9MgatUQg6ZE1aolA0iNr1BKBpEfWArXL0/m2p4mWR7vvM0Xj5Xl33G+NNepWxy747f2glr8647pW+0Fb+G0vlVpCT41aIpD0yBq1RCDpkTVqiUDSI2vUEoGkR9aoJQJJj6xRSwSSHlmjlggkPbJGLRFIemSNWiKQ9MgatUQg6ZE1aolA0iNr1BKBpEfWqCUCSY+sBWoXap9Xu+ypfVqthTZ/+lEerOGy///u4j/i9mAIn93csYkma13HAo1aT22cWstzWa29pkevVUmb59Oj09TbXybtcKZWsdVo07Xqefj5Dtp4eXidAmA8FgeCwzQM41/dplA88h1Pd9CIVb/38VjcW/WRNKMtIGltl+4stC5PUawPBPZpBdkwHl/+OMPx69vDBK1CG6CVNGVhfC3fp0lapQatkunNv5bv0yStdLM8S5q6AK22KStvDgSlTVmAVtLUBWi1TVl584mgtCkLO2ilTVnYORCUNmUBWklTF3Yv7Y8EznLUZoN9WgenKl3t0yqaen7XXp2z5VmbDddDWw1ajTZanjVOs3K6jMO1/SeC+exvBE2zZXJ3rZI2Q6vQhtN7kbT53ZnbUm0YXgu2w1unSQlblIHypLe09Tyx9XTKGraSplfA1tMpa9hKml4BW0+nrGEraXoFbD2dsoatpOkVsPV0yhq2kqZXwNbTKWsV24vPpKXZUqjYnDjqqZVs0haxSVvEJm0Rm7RFbNIWsUlbxCZtXbbywmZvd3tuFZu09dSGis2+LWKTtojtsHMNSAeuWqRnh4SOWrlvm7ElbIejRdpxKxfpsdOkVLCdn69sOgIF2zAu1/QapUCbbflnYmWHwiLQZCOzJdBi2+pRH97WN79Q2RZYXyi+3eMnqCUZoEYtEUh6ZI1aIpD0yBq1RCDpkTVqiUDSI2vUEoGkR9aoJQJJj6xRSwSSHlmjlggkPbJGLRFIemSNWiKQ9MgatUQg6ZE1aonAume8Tvt992uVtfNGw7RbP829zUzzvFzJ0flaqXV/+uOqkKd7M1pvz6J243GmlohSo7ZensvM7Veo/VoSNWrUmgvUCm2zbMzar20ANcvUmiwbk9Q2gJplak2WjUlqG0DNMrUmy8YktQ2gZplak2VjclrfJxV9IPitab/xlD+/PF5+/jZ8wxa4QfYb0D0lAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIDA3Qr8B+uZqLy+L5/2AAAAAElFTkSuQmCC',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });

                canvas.isDrawingMode = false;
                mode = 'image';
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M2224 4885 c-10 -7 -14 -47 -15 -152 0 -78 2 -147 6 -152 4 -7 103 -11 299 -11 271 0 294 -1 309 -18 15 -16 17 -51 16 -288 0 -148 -4 -277 -7 -286 -4 -10 -169 -182 -367 -384 -198 -201 -362 -368 -364 -370 -3 -2 163 -172 367 -377 l372 -373 0 -240 c0 -201 -3 -243 -16 -262 -15 -22 -17 -22 -309 -22 -197 0 -296 -4 -300 -11 -7 -11 -7 -290 0 -297 2 -2 356 -4 786 -4 650 0 783 2 790 13 14 22 11 273 -3 287 -9 9 -90 12 -304 12 -259 0 -293 2 -305 17 -11 13 -14 80 -15 327 l-2 312 -296 294 c-230 229 -296 301 -296 320 0 19 65 90 296 320 l295 295 -1 341 c0 208 3 351 9 367 l11 27 294 0 c254 0 297 2 310 16 13 13 16 42 16 156 0 127 -2 140 -18 145 -38 10 -1545 8 -1558 -2z"/></g></svg>';
                var encoded = window.btoa(svg);
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
            }
            function halfDegelbow1() {
                canvas.isDrawingMode = false;
                mode = 'image';
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M3015 4207 c-49 -48 -85 -92 -85 -103 0 -12 70 -88 180 -197 99 -98 180 -186 180 -195 0 -10 -148 -165 -330 -346 l-330 -329 0 -528 c0 -512 -1 -529 -19 -539 -12 -6 -116 -10 -269 -10 -201 0 -252 -3 -261 -14 -8 -9 -11 -55 -9 -137 l3 -124 691 -3 691 -2 5 27 c3 16 2 78 -1 138 l-6 110 -261 3 c-221 2 -264 5 -275 18 -8 10 -12 45 -12 102 1 48 2 258 2 467 l1 379 287 288 c172 172 295 288 306 288 11 0 95 -75 198 -177 107 -106 188 -178 199 -178 11 0 57 38 104 84 67 65 83 87 76 100 -15 28 -949 956 -965 959 -9 2 -52 -33 -100 -81z"/></g></svg>';
                var encoded = window.btoa(svg);
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
//                                 canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,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',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });
            }
            function fullDegelbow1() {
//                                                     canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASEAAAEWCAMAAAAevNE8AAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAkUExURf///wD/AKv/qwr/Cjz/PE7/Tkj/SEL/Qsb/xnH/ce//7xj/GMAvAowAAANgSURBVHgB7dzLUh0xDAVAnoGQ///fVKrYcLQ4LjCU76RnJ8t4pEawmDtwd+ciQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQI/J/B8n9fP3fs27kSofZ8IEWoCLW+GCDWBljdDhJpAy5shQk2g5c0QoSbQ8maIUBNoeTNEqAm0vBki1ARa3gwRagItb4YINYGWN0OEmkDLmyFCTaDlzRChJtDyZug/FXp7bY0v5y86Q6/5RsvO+G1Z9+SNO0XyrJP7Xq8tu9oZr1dx8s6dInnWyX2v15Zd7YzXqzh5506RPOvkvtdry652xutVnLxzp0iedXLf67VlVzvj9SpO3rlTJM86ue/12rKrnfF6FSfv3CmSZ53c93pt2dXOeL2Kk3fuFMmzTu57vbbsame8XsXJO3eK5Fkn971eW3a1M16v4uSdQ+Tx5ZPXn3HUyX2v1zbaeln/2o87L/oU9m4IPX3sez0i1KwIEXr/efNTFqPg91CAjJDQIIkFQgEyQkKDJBYIBcgICQ2SWCAUICMkNEhigVCAjJDQIIkFQgEyQkKDJBYIBcgICQ2SWCAUICMkNEhigVCAjJDQIIkFQgEyQkKDJBYIBcgICQ2SWCAUICMkNEhigVCAjJDQIIkFQgEyQkKDJBY2Cj3GdR+3utFwn9DrQ14X/TvXT78dc6MjUsveN0P1Vje6gVD7xhEi1ARa3gwRagItb4YINYGWN0OEmkDLmyFCTaDlzRChJtDyZohQE2h5M0SoCbS8GSLUBFreDBEqAs+/yjVm6LF8wa/n598P+/7fbqn/+9MD4OsLT2/X+KTwHf/rIOOEi33kOPr7+gKhZkiIUBNoeTNEqAm0vBki1ARa3gwRagItb4YINYGWN0OEmkDLX2mG3l4f3tuNt+W/FF5IaL4kny/Nfyq+0PPFfw/RLvU48PsfyboDAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQIECBAgQIAAAQK3JfAXw4gbqy8SvxgAAAAASUVORK5CYII=',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });
                canvas.isDrawingMode = false;
                mode = 'image';
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path d="M3904 4445 c-11 -8 -14 -74 -16 -315 l-3 -305 -765 -5 -765 -5 -5 -776 c-4 -637 -7 -779 -19 -792 -12 -15 -48 -17 -332 -17 -293 0 -319 -1 -327 -17 -5 -10 -9 -88 -8 -173 l1 -155 844 -3 c624 -1 847 1 858 9 11 9 13 45 11 173 l-3 161 -333 5 c-281 4 -334 7 -342 20 -6 9 -10 250 -10 601 0 555 1 587 18 602 17 15 72 17 581 17 368 0 569 -4 582 -10 18 -10 19 -25 19 -348 0 -250 3 -341 12 -350 13 -13 291 -18 322 -6 14 6 16 89 16 845 0 728 -2 840 -15 845 -26 10 -308 9 -321 -1z"/></g></svg>';
                var encoded = window.btoa(svg);
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
            }
            function equalTee1() {
//                 canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAVcAAAFZCAMAAAAb5ZxaAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAhUExURf///wD/AE7/Thf/F3X/dYn/id//3wT/BM3/zQr/Cqz/rDY1pZ4AAAQ9SURBVHgB7d3LUhsxAERRXjGG///gLMhGTEAVRZfy42inidSWzzQsiMvz8GAQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIDAKvL/sH+Mr3Ofs6XH/uE/J8V1zHT12zbjukhxzuI4eu2Zcd0mOOVxHj10zrrskxxyuo8euGdddkmMO19Fj14zrLskxh+vosWvGdZfkmMN19Ng147pLcszhOnrsmn24vr59OV4Pf5/9cumff3jcdbZrzjnNxrHQsx2na/b4sbMfXX/spW/6hbg2t5cr10agSdVXro1Ak6qvXBuBJlVfuTYCTaq+cm0EmlR95doINKn6yrURaFL1lWsj0KTqK9dGoEnV1+twPTfHvLrU3X19vzqB5sC7XZ+aY15dKtfmlu12fXz+PJpzX3rqdtfD570uXaA5H1eujUCTqq9cG4EmVV+5NgJNqr5ybQSaVH3l2gg0qfrKtRFoUvWVayPQpOor10agSdVXro1Ak6qvXBuBJlVfuTYCTaq+cm0EmlR95doINKn6yrURaFL1lWsj0KTqa+J6OnxcdfuF5NwXH/p8/OLHzbIXT5Ac8Hmz4jEuOfbFh+prc4v0lWsj0KTqK9dGoEnVV66NQJOqr1wbgSZVX7k2Ak2qvnJtBJpUfU1cT2+HP+y9/tc4xN3pY3r8P0zS1weuXBuBJlVfuTYCTaq+cm0EmlR95doINKn6yrURaFL1lWsj0KTqK9dGoEnVV66NQJOqr1wbgSZVX7k2Ak2qvnJtBJpUfeXaCDSp+sq1EWhS9ZVrI9Ck6ivXRqBJ1dfrcP38FL5nnyv+81HrzffvTh/Qufv3wOGuvByu3MWF3PVOH9Sbu57uop6HN5m7Hl7xPi5wbe4zV66NQJOqr1wbgSZVX7k2Ak2qvnJtBJpUfeXaCDSp+sq1EWhS9ZVrI9Ck6ivXRqBJ1VeujUCTqq9LrqfzZBxdJxvO56WD3Nimp7fJOH6f4DcbPp4lc2NES2/n6fH7b3E8sn634WP10kFubNPxx/wvkv946caIlt4O1yW26SauU6KlBVyX2KabuE6JlhZwXWKbbuI6JVpawHWJbbqJ65RoaQHXJbbpJq5ToqUFXJfYppu4TomWFnBdYptu4jolWlrw/mv/WDqITQQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIEPgxgd81bC1iGZekHgAAAABJRU5ErkJggg==',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });
                canvas.isDrawingMode = false;
                mode = 'image';
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M1784 4589 c-11 -18 -13 -232 -2 -251 8 -16 31 -18 273 -18 223 0 266 -2 279 -15 13 -14 15 -150 15 -1185 0 -1035 -2 -1171 -15 -1185 -13 -13 -56 -15 -279 -15 -242 0 -265 -1 -273 -17 -10 -21 -8 -236 2 -252 11 -16 1408 -15 1424 1 8 8 12 51 12 128 0 77 -4 120 -12 128 -9 9 -83 12 -273 12 -194 0 -266 3 -278 13 -16 11 -18 50 -17 511 0 350 4 503 11 513 9 10 111 13 538 13 314 0 532 -4 541 -10 13 -8 15 -49 16 -262 1 -139 2 -263 3 -276 1 -22 1 -23 144 -20 l142 3 3 704 c1 512 -1 708 -9 717 -9 11 -45 14 -145 14 -121 0 -134 -2 -135 -17 -1 -10 -2 -134 -3 -276 -1 -243 -2 -259 -20 -273 -17 -12 -106 -14 -537 -14 -457 0 -519 2 -533 16 -14 14 -16 75 -16 519 0 392 3 506 13 512 6 4 136 10 287 13 l275 5 3 115 c1 63 0 125 -3 138 l-5 22 -710 0 c-523 0 -711 -3 -716 -11z"/></g></svg>';
                var encoded = window.btoa(svg);
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
            }
            function endCap1() {
                canvas.isDrawingMode = false;
                mode = 'image';
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M1558 4550 c-23 -6 -23 -6 -28 -220 -3 -118 -3 -361 0 -540 l5 -325 214 -3 c165 -2 216 1 223 10 4 7 8 147 7 311 0 215 3 303 12 313 17 20 1221 21 1238 1 7 -9 11 -110 11 -312 1 -198 5 -303 12 -312 8 -10 61 -13 219 -13 l209 0 2 303 c5 728 4 780 -12 784 -23 6 2086 9 -2112 3z"/></g></svg>';
                var encoded = window.btoa(svg);
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
//                                                      canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOcAAAEOCAMAAABFFvDsAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAeUExURf///wD/AAz/DM3/zST/JN7/3jz/PLb/tnb/dvr/+g9pyykAAAIBSURBVHgB7drJsoIwEAXQADLk/3/YYQlS1Uwhz3eycEGFVPr0VbFMSgYBAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAr8s0F0z+trMmvbM0bxHO41dzpUV+tnZuS9Drq7KlM4t8bPakGpr5itbl9RZWWbf21HnboGhwnbq5+52NjX2s2/317N25+Of5LZV530Ca+E7cF0/72vnFd8r+qmfVwvkA583a7dWl9tunIblZh8bx2KF+p4TXr+HF7tsNkZoXDxR1fjcp85oW/UzKlVknvdnkFlug1Blpslt0Flug1Blpslt0Flug1Blpslt0Flug1Blpslt0Flug1Blpslt0Flug1Blpslt0Pmv5HZ+HKzZeGRtnJ8oq/J/+9TPRjdtO+SU82yBvpuCSbh3Wr+tzi+bPb7Cl0VdIkCAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAIHfE3gC/CEOXCZH+9MAAAAASUVORK5CYII=',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        }); 
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
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
//                                                      canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,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',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });  
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
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });

//                   canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,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',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });      
            }
            function diFlanging1() {
                mode = 'image';
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M2704 4537 c-2 -7 -6 -522 -7 -1145 -2 -912 -5 -1134 -15 -1144 -10 -10 -78 -13 -289 -15 -218 -1 -277 -4 -284 -15 -12 -19 -11 -255 1 -263 5 -3 328 -6 717 -6 531 0 708 3 714 12 12 21 10 257 -3 266 -7 3 -129 7 -271 7 -240 1 -260 2 -272 20 -11 15 -14 213 -15 1148 0 622 -3 1134 -6 1139 -3 5 -64 9 -135 9 -98 0 -131 -3 -135 -13z"/></g></svg>';
                var encoded = window.btoa(svg);
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
//                                     canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAVcAAAFhCAMAAADzzBmaAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAYUExURf///wD/AA//Dzz/PN//38//z1D/UJT/lL0q2RAAAAOKSURBVHgB7dhJbsJAAEVBzOT73zgYsUDqJEikXxa4nAlZ8TMUf8Xh4CBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIENiZwOVxrOvjwXlnANHLXZbj7Ws7tr+3R6foRjvL3kmff3GdsoBn0vtjrlynCDQRe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDRVe+XaCDTVYa/H5j57q3Jt3nGuXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqvXBuBpmqv/+R6au6zt+qwV65TJsB1CuMQ4TqQTDnBdQrjEOE6kEw5wXUK4xDhOpBMOcH1Hcbr8dUxuC6vrjhd18s7z+WTrrm5Lsdl+/7xZ4T95Z+30Cf5vPtart+q/e3ku8/lk67j2rybXLk2Ak3VXrk2Ak3VXrk2Ak3VXrk2Ak3VXrk2Ak3VXrk2Ak3VXgPX83rdPi+9ffQ689j956/n6AgWIEmAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIEBgDwJfXeIS7Wvv4qcAAAAASUVORK5CYII=',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });    
            }
            function diFlangesotcket1() {
                mode = 'image';
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M2102 4398 l3 -143 271 -1 c150 -1 277 -4 283 -8 8 -5 11 -202 11 -709 l0 -701 -360 -361 c-198 -198 -360 -365 -360 -370 0 -12 182 -195 194 -195 4 0 151 144 326 319 175 175 327 322 338 326 17 5 73 -46 316 -287 162 -161 310 -308 329 -327 l35 -33 96 93 c53 51 96 97 96 103 0 6 -162 173 -360 371 l-360 361 0 701 c0 511 3 704 11 709 7 4 129 7 273 6 167 0 265 3 273 10 14 11 19 231 7 262 -6 14 -78 16 -715 16 l-710 0 3 -142z"/></g></svg>';
                var encoded = window.btoa(svg);
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
//                                       canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,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',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });   
            }
            function diColor1() {
                mode = 'image';
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M2343 3583 c-11 -22 -9 -1255 1 -1271 5 -9 155 -12 624 -12 339 0 627 3 640 6 l22 6 0 632 c0 478 -3 635 -12 644 -9 9 -165 12 -640 12 -588 0 -628 -1 -635 -17z m1005 -255 c9 -9 12 -107 12 -380 0 -357 -1 -368 -20 -378 -13 -7 -136 -10 -375 -8 -303 3 -357 5 -365 18 -6 9 -10 165 -10 372 0 311 2 359 16 372 13 14 61 16 373 16 264 0 360 -3 369 -12z"/></g></svg>';
                var encoded = window.btoa(svg);
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
//                                         canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAX0AAAF1CAMAAAAz9MoOAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAYUExURf///wD/AAT/BC7/Lg7/Dk3/TX7/fr//v+eWHmAAAAQWSURBVHgB7dzBbtswEATQ2I7b///jFmhP4oWdWUGF9XSLwB2RT5McYiRfXy4CBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAiMCrzmr/foBj867PkYvp7P748GGz3csP3vOPr7L2he/6H72/z0t6lOWEj/BNTtSPrbVCcspH8C6nYk/W2qExaeoP84YZsfGkn/yhdLn/6VAlc+W/fpXylw5bN1n/6VAlc+W/ev1H8t/D//7XovAX7DvP1C38uHW9ujfxb++D7y098mXKu7PfpX/4jv05V9wb77yzeP7m/z6/421QkL++77yZO/Ft3P7fpJ3e8N8wTdz+36Sd3vDfME3c/t+knd7w3zBN3P7fpJ3e8N8wTdz+36Sd3vDfME3c/t+knd7w3zBN3P7fpJ3e8N8wTdz+36Sd3vDfME3c/t+knd7w3zBN3P7fpJ3e8N8wTdz+36Sd3vDfME3c/t+knd7w3zBN3P7fpJ3e8N8wTdz+36Sd3vDfME3c/t+knd7w3zBN3P7fpJ3e8N8wTdz+36Sd3vDfME3c/t+knd7w3zBN3P7fpJ3e8N8wTdz+36Sd3vDfME3c/t+knd7w3zhOXv/Adu5Lu52+QA9hJxN8P8vAvdwI18N3ebHMBeIu5mmJ93oRu4ke/mbpMD2EvE3Qzz8y50Azfy3dxtcgB7ibibYX7ehW7gRr6bu00OYC8RdzPMz7vQDdzId3O3yQHsY8Tzbob5eY90A1/7b6jbr2NA+xhBn/62wJULj8Ud+Fr3t1/ogPYxgv62/nP+em0/3EICBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAwP8q8AuXyReOW1xIjAAAAABJRU5ErkJggg==',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });  
            }
            function diCap1() {
                mode = 'image';
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M2216 3344 c-8 -20 -8 -747 0 -768 5 -14 29 -16 159 -16 138 0 154 2 159 18 3 9 5 113 5 230 -1 149 2 217 10 225 19 19 882 21 907 2 18 -12 19 -32 22 -244 l3 -232 157 3 157 3 0 395 0 395 -786 3 c-711 2 -787 1 -793 -14z"/></g></svg>';
                var encoded = window.btoa(svg);
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
//                                         canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAATgAAAEcCAMAAABknFh4AAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAeUExURf///wD/AAz/DM3/zST/JN7/3jz/PLb/tnb/dvr/+g9pyykAAAJ3SURBVHgB7dvLjoJAAARAHvL6/x/W9bIJkEloIBGoOXgAezIUjUENVWUQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQ2C3QnjO63Qv79Qnq5shR/41mHNpp+vUD37u+76Ee+9JP92erqmPNvrP11e3r9qnrKXB7L4Mr5MGFZwkcuFAgjGlcBtc1x8u9sqVcLHW8W91cjCBbLrjM7Yz7OI0L2wgOXOEyDnFKMY0r6RT2PQJuKgCku+4P1w5jv9R5bRyLGR5wA/z5wXFx2HXhA3Ft17D47tGvve1228CFpxQcuFAgjGkcuFAgjGkcuFAgjGkcuFAgjGkcuFAgjGkcuFAgjGkcuFAgjGkcuFAgjGkcuFAgjGkcuFAgjGkcuFAgjC2erKk3Pk00zGeon/G/ajcb7bjt6Y5pmk3QtWN4Di8e67bBrRzt/hlWJrWJAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQIECAAAECBAgQ+Bd4A8rTDlxnF/RjAAAAAElFTkSuQmCC',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });   
            }
            function coupler1() {
                mode = 'image';
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M2067 4466 c-7 -18 -9 -238 -8 -1138 l1 -748 944 0 c713 0 947 3 952 12 8 13 12 108 11 293 0 72 -1 460 -1 863 l0 732 -947 0 c-800 0 -948 -2 -952 -14z m1483 -396 c13 -8 15 -78 14 -534 0 -419 -3 -527 -13 -540 -12 -14 -71 -16 -545 -16 -400 0 -535 3 -544 12 -17 17 -17 1059 0 1076 15 15 1064 17 1088 2z"/></g></svg>';
                var encoded = window.btoa(svg);
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
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
            }
            function beEndCateValue1() {
                mode = 'image';
                var svg = '<?xml version="1.0" standalone="no"?><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"      width="250px" height="312px"  viewBox="0 0 250 312" preserveAspectRatio="none"><g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="' + color + '" stroke="none"><path \n\
        d="M3078 5504 l-3 -336 -23 -19 c-22 -17 -45 -19 -388 -18 -200 0 -368 -2 -374 -6 -6 -4 -10 -82 -10 -207 l0 -201 55 -68 c30 -37 55 -73 55 -78 0 -6 14 -26 30 -44 27 -31 133 -173 347 -465 134 -183 167 -228 200 -271 58 -77 73 -46 -229 -456 -56 -77 -155 -210 -218 -295 -63 -86 -128 -175 -145 -198 -16 -23 -45 -63 -62 -88 l-33 -46 0 -187 c0 -132 4 -191 12 -199 9 -9 108 -12 384 -12 333 0 373 -2 385 -17 11 -13 15 -88 19 -357 l5 -341 193 -3 192 -2 0 338 c0 289 2 341 16 360 l15 22 369 0 c202 0 375 3 384 6 14 5 16 32 16 200 0 166 -2 199 -17 222 -17 26 -101 141 -272 372 -413 557 -448 608 -435 625 5 7 21 30 36 53 15 22 55 78 89 124 75 101 114 153 274 373 135 186 165 226 210 285 17 21 49 66 73 98 l42 60 0 195 c0 130 -4 198 -11 203 -6 3 -174 6 -373 5 -346 -2 -364 -1 -387 18 l-24 19 -5 334 -5 333 -192 3 -192 2 -3 -336z m628 -788 c23 -9 16 -53 -14 -88 -15 -18 -53 -68 -85 -110 -31 -43 -81 -111 -110 -150 -30 -40 -67 -91 -83 -114 -101 -142 -125 -167 -151 -157 -7 2 -38 40 -70 85 -32 44 -127 173 -210 286 -84 114 -153 215 -153 225 0 10 9 21 19 25 23 8 835 6 857 -2z m-404 -1388 c16 -14 50 -58 213 -283 55 -76 124 -168 153 -204 37 -46 51 -73 50 -91 l-3 -25 -440 0 -440 0 -3 22 c-2 15 12 41 40 76 39 49 177 236 323 440 57 77 76 89 107 65z"/></g></svg>';
                var encoded = window.btoa(svg);
                fabric.Image.fromURL('data:image/svg+xml;base64,' + encoded,
                        function (oImg) {
                            oImg.set({
                                width: 30 + parseInt(strokeWidth),
                                height: 30 + parseInt(strokeWidth),
                                left: 50,
                                top: 10,
                            });
                            canvas.add(oImg);
                        });
//                                          canvas.isDrawingMode = false;
//                mode = 'image';
//                fabric.Image.fromURL('data:image/png;base64,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',
//                        function (oImg) {
//                            oImg.set({
//                                width: 30,
//                                height: 30,
//                                left: 50,
//                                top: 10,
//                            });
//                            canvas.add(oImg);
//                        });   
            }
            function eraser() {
                mode = 'pencil';
                canvas.isDrawingMode = true;
                canvas.freeDrawingBrush.width = strokeWidth;
                canvas.freeDrawingBrush.color = 'white';
            }
