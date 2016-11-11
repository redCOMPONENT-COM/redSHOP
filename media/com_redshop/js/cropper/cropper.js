/*!
 * Cropper.js v0.8.1
 * https://github.com/fengyuanchen/cropperjs
 *
 * Copyright (c) 2015-2016 Fengyuan Chen
 * Released under the MIT license
 *
 * Date: 2016-09-03T04:55:16.458Z
 */

!function(e,t){if("object"==typeof exports&&"object"==typeof module)module.exports=t();else if("function"==typeof define&&define.amd)define([],t);else{var a=t();for(var i in a)("object"==typeof exports?exports:e)[i]=a[i]}}(this,function(){return function(e){function t(i){if(a[i])return a[i].exports;var o=a[i]={exports:{},id:i,loaded:!1};return e[i].call(o.exports,o,o.exports,t),o.loaded=!0,o.exports}var a={};return t.m=e,t.c=a,t.p="",t(0)}([function(e,t,a){"use strict";function i(e){if(e&&e.__esModule)return e;var t={};if(null!=e)for(var a in e)Object.prototype.hasOwnProperty.call(e,a)&&(t[a]=e[a]);return t.default=e,t}function o(e){return e&&e.__esModule?e:{default:e}}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}Object.defineProperty(t,"__esModule",{value:!0});var n=function(){function e(e,t){for(var a=0;a<t.length;a++){var i=t[a];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}return function(t,a,i){return a&&e(t.prototype,a),i&&e(t,i),t}}(),s=a(1),d=o(s),l=a(2),c=o(l),h=a(3),p=o(h),u=a(5),m=o(u),f=a(6),v=o(f),g=a(7),w=o(g),b=a(8),y=o(b),x=a(9),C=o(x),M=a(4),D=i(M),L="cropper",B=L+"-hidden",O="error",T="load",E="ready",N="crop",k=/^data:/,X=/^data:image\/jpeg.*;base64,/,W=void 0,Y=function(){function e(t,a){r(this,e);var i=this;i.element=t,i.options=D.extend({},d.default,D.isPlainObject(a)&&a),i.loaded=!1,i.ready=!1,i.complete=!1,i.rotated=!1,i.cropped=!1,i.disabled=!1,i.replaced=!1,i.limited=!1,i.wheeling=!1,i.isImg=!1,i.originalUrl="",i.canvasData=null,i.cropBoxData=null,i.previews=null,i.init()}return n(e,[{key:"init",value:function(){var e=this,t=e.element,a=t.tagName.toLowerCase(),i=void 0;if(!D.getData(t,L)){if(D.setData(t,L,e),"img"===a){if(e.isImg=!0,e.originalUrl=i=t.getAttribute("src"),!i)return;i=t.src}else"canvas"===a&&window.HTMLCanvasElement&&(i=t.toDataURL());e.load(i)}}},{key:"load",value:function(e){var t=this,a=t.options,i=t.element;if(e){if(t.url=e,t.imageData={},!a.checkOrientation||!window.ArrayBuffer)return void t.clone();if(k.test(e))return void(X?t.read(D.dataURLToArrayBuffer(e)):t.clone());var o=new XMLHttpRequest;o.onerror=o.onabort=function(){t.clone()},o.onload=function(){t.read(o.response)},a.checkCrossOrigin&&D.isCrossOriginURL(e)&&i.crossOrigin&&(e=D.addTimestamp(e)),o.open("get",e),o.responseType="arraybuffer",o.send()}}},{key:"read",value:function(e){var t=this,a=t.options,i=D.getOrientation(e),o=t.imageData,r=0,n=1,s=1;if(i>1)switch(t.url=D.arrayBufferToDataURL(e),i){case 2:n=-1;break;case 3:r=-180;break;case 4:s=-1;break;case 5:r=90,s=-1;break;case 6:r=90;break;case 7:r=90,n=-1;break;case 8:r=-90}a.rotatable&&(o.rotate=r),a.scalable&&(o.scaleX=n,o.scaleY=s),t.clone()}},{key:"clone",value:function(){var e=this,t=e.element,a=e.url,i=void 0,o=void 0,r=void 0,n=void 0;e.options.checkCrossOrigin&&D.isCrossOriginURL(a)&&(i=t.crossOrigin,i?o=a:(i="anonymous",o=D.addTimestamp(a))),e.crossOrigin=i,e.crossOriginUrl=o;var s=D.createElement("img");i&&(s.crossOrigin=i),s.src=o||a,e.image=s,e.onStart=r=D.proxy(e.start,e),e.onStop=n=D.proxy(e.stop,e),e.isImg?t.complete?e.start():D.addListener(t,T,r):(D.addListener(s,T,r),D.addListener(s,O,n),D.addClass(s,"cropper-hide"),t.parentNode.insertBefore(s,t.nextSibling))}},{key:"start",value:function(e){var t=this,a=t.isImg?t.element:t.image;e&&(D.removeListener(a,T,t.onStart),D.removeListener(a,O,t.onStop)),D.getImageSize(a,function(e,a){D.extend(t.imageData,{naturalWidth:e,naturalHeight:a,aspectRatio:e/a}),t.loaded=!0,t.build()})}},{key:"stop",value:function(){var e=this,t=e.image;D.removeListener(t,T,e.onStart),D.removeListener(t,O,e.onStop),D.removeChild(t),e.image=null}},{key:"build",value:function(){var e=this,t=e.options,a=e.element,i=e.image,o=void 0,r=void 0,n=void 0,s=void 0,d=void 0,l=void 0;if(e.loaded){e.ready&&e.unbuild();var h=D.createElement("div");h.innerHTML=c.default,e.container=o=a.parentNode,e.cropper=r=D.getByClass(h,"cropper-container")[0],e.canvas=n=D.getByClass(r,"cropper-canvas")[0],e.dragBox=s=D.getByClass(r,"cropper-drag-box")[0],e.cropBox=d=D.getByClass(r,"cropper-crop-box")[0],e.viewBox=D.getByClass(r,"cropper-view-box")[0],e.face=l=D.getByClass(d,"cropper-face")[0],D.appendChild(n,i),D.addClass(a,B),o.insertBefore(r,a.nextSibling),e.isImg||D.removeClass(i,"cropper-hide"),e.initPreview(),e.bind(),t.aspectRatio=Math.max(0,t.aspectRatio)||NaN,t.viewMode=Math.max(0,Math.min(3,Math.round(t.viewMode)))||0,t.autoCrop?(e.cropped=!0,t.modal&&D.addClass(s,"cropper-modal")):D.addClass(d,B),t.guides||D.addClass(D.getByClass(d,"cropper-dashed"),B),t.center||D.addClass(D.getByClass(d,"cropper-center"),B),t.background&&D.addClass(r,"cropper-bg"),t.highlight||D.addClass(l,"cropper-invisible"),t.cropBoxMovable&&(D.addClass(l,"cropper-move"),D.setData(l,"action","all")),t.cropBoxResizable||(D.addClass(D.getByClass(d,"cropper-line"),B),D.addClass(D.getByClass(d,"cropper-point"),B)),e.setDragMode(t.dragMode),e.render(),e.ready=!0,e.setData(t.data),e.completing=setTimeout(function(){D.isFunction(t.ready)&&D.addListener(a,E,t.ready,!0),D.dispatchEvent(a,E),D.dispatchEvent(a,N,e.getData()),e.complete=!0},0)}}},{key:"unbuild",value:function(){var e=this;e.ready&&(e.complete||clearTimeout(e.completing),e.ready=!1,e.complete=!1,e.initialImageData=null,e.initialCanvasData=null,e.initialCropBoxData=null,e.containerData=null,e.canvasData=null,e.cropBoxData=null,e.unbind(),e.resetPreview(),e.previews=null,e.viewBox=null,e.cropBox=null,e.dragBox=null,e.canvas=null,e.container=null,D.removeChild(e.cropper),e.cropper=null)}}],[{key:"noConflict",value:function(){return window.Cropper=W,e}},{key:"setDefaults",value:function(e){D.extend(d.default,D.isPlainObject(e)&&e)}}]),e}();D.extend(Y.prototype,p.default),D.extend(Y.prototype,m.default),D.extend(Y.prototype,v.default),D.extend(Y.prototype,w.default),D.extend(Y.prototype,y.default),D.extend(Y.prototype,C.default),"undefined"!=typeof window&&(W=window.Cropper,window.Cropper=Y),t.default=Y},function(e,t){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={viewMode:0,dragMode:"crop",aspectRatio:NaN,data:null,preview:"",responsive:!0,restore:!0,checkCrossOrigin:!0,checkOrientation:!0,modal:!0,guides:!0,center:!0,highlight:!0,background:!0,autoCrop:!0,autoCropArea:.8,movable:!0,rotatable:!0,scalable:!0,zoomable:!0,zoomOnTouch:!0,zoomOnWheel:!0,wheelZoomRatio:.1,cropBoxMovable:!0,cropBoxResizable:!0,toggleDragModeOnDblclick:!0,minCanvasWidth:0,minCanvasHeight:0,minCropBoxWidth:0,minCropBoxHeight:0,minContainerWidth:200,minContainerHeight:100,ready:null,cropstart:null,cropmove:null,cropend:null,crop:null,zoom:null}},function(e,t){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default='<div class="cropper-container"><div class="cropper-wrap-box"><div class="cropper-canvas"></div></div><div class="cropper-drag-box"></div><div class="cropper-crop-box"><span class="cropper-view-box"></span><span class="cropper-dashed dashed-h"></span><span class="cropper-dashed dashed-v"></span><span class="cropper-center"></span><span class="cropper-face"></span><span class="cropper-line line-e" data-action="e"></span><span class="cropper-line line-n" data-action="n"></span><span class="cropper-line line-w" data-action="w"></span><span class="cropper-line line-s" data-action="s"></span><span class="cropper-point point-e" data-action="e"></span><span class="cropper-point point-n" data-action="n"></span><span class="cropper-point point-w" data-action="w"></span><span class="cropper-point point-s" data-action="s"></span><span class="cropper-point point-ne" data-action="ne"></span><span class="cropper-point point-nw" data-action="nw"></span><span class="cropper-point point-sw" data-action="sw"></span><span class="cropper-point point-se" data-action="se"></span></div></div>'},function(e,t,a){"use strict";function i(e){if(e&&e.__esModule)return e;var t={};if(null!=e)for(var a in e)Object.prototype.hasOwnProperty.call(e,a)&&(t[a]=e[a]);return t.default=e,t}Object.defineProperty(t,"__esModule",{value:!0});var o=a(4),r=i(o);t.default={render:function(){var e=this;e.initContainer(),e.initCanvas(),e.initCropBox(),e.renderCanvas(),e.cropped&&e.renderCropBox()},initContainer:function(){var e=this,t=e.options,a=e.element,i=e.container,o=e.cropper,n=void 0;r.addClass(o,"cropper-hidden"),r.removeClass(a,"cropper-hidden"),e.containerData=n={width:Math.max(i.offsetWidth,Number(t.minContainerWidth)||200),height:Math.max(i.offsetHeight,Number(t.minContainerHeight)||100)},r.setStyle(o,{width:n.width,height:n.height}),r.addClass(a,"cropper-hidden"),r.removeClass(o,"cropper-hidden")},initCanvas:function(){var e=this,t=e.options.viewMode,a=e.containerData,i=e.imageData,o=90===Math.abs(i.rotate),n=o?i.naturalHeight:i.naturalWidth,s=o?i.naturalWidth:i.naturalHeight,d=n/s,l=a.width,c=a.height;a.height*d>a.width?3===t?l=a.height*d:c=a.width/d:3===t?c=a.width/d:l=a.height*d;var h={naturalWidth:n,naturalHeight:s,aspectRatio:d,width:l,height:c};h.oldLeft=h.left=(a.width-l)/2,h.oldTop=h.top=(a.height-c)/2,e.canvasData=h,e.limited=1===t||2===t,e.limitCanvas(!0,!0),e.initialImageData=r.extend({},i),e.initialCanvasData=r.extend({},h)},limitCanvas:function(e,t){var a=this,i=a.options,o=i.viewMode,r=a.containerData,n=a.canvasData,s=n.aspectRatio,d=a.cropBoxData,l=a.cropped&&d,c=void 0,h=void 0,p=void 0,u=void 0;e&&(c=Number(i.minCanvasWidth)||0,h=Number(i.minCanvasHeight)||0,o>1?(c=Math.max(c,r.width),h=Math.max(h,r.height),3===o&&(h*s>c?c=h*s:h=c/s)):o>0&&(c?c=Math.max(c,l?d.width:0):h?h=Math.max(h,l?d.height:0):l&&(c=d.width,h=d.height,h*s>c?c=h*s:h=c/s)),c&&h?h*s>c?h=c/s:c=h*s:c?h=c/s:h&&(c=h*s),n.minWidth=c,n.minHeight=h,n.maxWidth=1/0,n.maxHeight=1/0),t&&(o?(p=r.width-n.width,u=r.height-n.height,n.minLeft=Math.min(0,p),n.minTop=Math.min(0,u),n.maxLeft=Math.max(0,p),n.maxTop=Math.max(0,u),l&&a.limited&&(n.minLeft=Math.min(d.left,d.left+(d.width-n.width)),n.minTop=Math.min(d.top,d.top+(d.height-n.height)),n.maxLeft=d.left,n.maxTop=d.top,2===o&&(n.width>=r.width&&(n.minLeft=Math.min(0,p),n.maxLeft=Math.max(0,p)),n.height>=r.height&&(n.minTop=Math.min(0,u),n.maxTop=Math.max(0,u))))):(n.minLeft=-n.width,n.minTop=-n.height,n.maxLeft=r.width,n.maxTop=r.height))},renderCanvas:function(e){var t=this,a=t.canvasData,i=t.imageData,o=i.rotate,n=void 0,s=void 0;t.rotated&&(t.rotated=!1,s=r.getRotatedSizes({width:i.width,height:i.height,degree:o}),n=s.width/s.height,n!==a.aspectRatio&&(a.left-=(s.width-a.width)/2,a.top-=(s.height-a.height)/2,a.width=s.width,a.height=s.height,a.aspectRatio=n,a.naturalWidth=i.naturalWidth,a.naturalHeight=i.naturalHeight,o%180&&(s=r.getRotatedSizes({width:i.naturalWidth,height:i.naturalHeight,degree:o}),a.naturalWidth=s.width,a.naturalHeight=s.height),t.limitCanvas(!0,!1))),(a.width>a.maxWidth||a.width<a.minWidth)&&(a.left=a.oldLeft),(a.height>a.maxHeight||a.height<a.minHeight)&&(a.top=a.oldTop),a.width=Math.min(Math.max(a.width,a.minWidth),a.maxWidth),a.height=Math.min(Math.max(a.height,a.minHeight),a.maxHeight),t.limitCanvas(!1,!0),a.oldLeft=a.left=Math.min(Math.max(a.left,a.minLeft),a.maxLeft),a.oldTop=a.top=Math.min(Math.max(a.top,a.minTop),a.maxTop),r.setStyle(t.canvas,{width:a.width,height:a.height,left:a.left,top:a.top}),t.renderImage(),t.cropped&&t.limited&&t.limitCropBox(!0,!0),e&&t.output()},renderImage:function(e){var t=this,a=t.canvasData,i=t.imageData,o=void 0,n=void 0,s=void 0,d=void 0;i.rotate&&(n=r.getRotatedSizes({width:a.width,height:a.height,degree:i.rotate,aspectRatio:i.aspectRatio},!0),s=n.width,d=n.height,o={width:s,height:d,left:(a.width-s)/2,top:(a.height-d)/2}),r.extend(i,o||{width:a.width,height:a.height,left:0,top:0});var l=r.getTransform(i);r.setStyle(t.image,{width:i.width,height:i.height,marginLeft:i.left,marginTop:i.top,WebkitTransform:l,msTransform:l,transform:l}),e&&t.output()},initCropBox:function(){var e=this,t=e.options,a=t.aspectRatio,i=Number(t.autoCropArea)||.8,o=e.canvasData,n={width:o.width,height:o.height};a&&(o.height*a>o.width?n.height=n.width/a:n.width=n.height*a),e.cropBoxData=n,e.limitCropBox(!0,!0),n.width=Math.min(Math.max(n.width,n.minWidth),n.maxWidth),n.height=Math.min(Math.max(n.height,n.minHeight),n.maxHeight),n.width=Math.max(n.minWidth,n.width*i),n.height=Math.max(n.minHeight,n.height*i),n.oldLeft=n.left=o.left+(o.width-n.width)/2,n.oldTop=n.top=o.top+(o.height-n.height)/2,e.initialCropBoxData=r.extend({},n)},limitCropBox:function(e,t){var a=this,i=a.options,o=i.aspectRatio,r=a.containerData,n=a.canvasData,s=a.cropBoxData,d=a.limited,l=void 0,c=void 0,h=void 0,p=void 0;e&&(l=Number(i.minCropBoxWidth)||0,c=Number(i.minCropBoxHeight)||0,l=Math.min(l,r.width),c=Math.min(c,r.height),h=Math.min(r.width,d?n.width:r.width),p=Math.min(r.height,d?n.height:r.height),o&&(l&&c?c*o>l?c=l/o:l=c*o:l?c=l/o:c&&(l=c*o),p*o>h?p=h/o:h=p*o),s.minWidth=Math.min(l,h),s.minHeight=Math.min(c,p),s.maxWidth=h,s.maxHeight=p),t&&(d?(s.minLeft=Math.max(0,n.left),s.minTop=Math.max(0,n.top),s.maxLeft=Math.min(r.width,n.left+n.width)-s.width,s.maxTop=Math.min(r.height,n.top+n.height)-s.height):(s.minLeft=0,s.minTop=0,s.maxLeft=r.width-s.width,s.maxTop=r.height-s.height))},renderCropBox:function(){var e=this,t=e.options,a=e.containerData,i=e.cropBoxData;(i.width>i.maxWidth||i.width<i.minWidth)&&(i.left=i.oldLeft),(i.height>i.maxHeight||i.height<i.minHeight)&&(i.top=i.oldTop),i.width=Math.min(Math.max(i.width,i.minWidth),i.maxWidth),i.height=Math.min(Math.max(i.height,i.minHeight),i.maxHeight),e.limitCropBox(!1,!0),i.oldLeft=i.left=Math.min(Math.max(i.left,i.minLeft),i.maxLeft),i.oldTop=i.top=Math.min(Math.max(i.top,i.minTop),i.maxTop),t.movable&&t.cropBoxMovable&&r.setData(e.face,"action",i.width===a.width&&i.height===a.height?"move":"all"),r.setStyle(e.cropBox,{width:i.width,height:i.height,left:i.left,top:i.top}),e.cropped&&e.limited&&e.limitCanvas(!0,!0),e.disabled||e.output()},output:function(){var e=this;e.preview(),e.complete&&r.dispatchEvent(e.element,"crop",e.getData())}}},function(e,t){"use strict";function a(e){return ae.call(e).slice(8,-1).toLowerCase()}function i(e){return"number"==typeof e&&!isNaN(e)}function o(e){return"undefined"==typeof e}function r(e){return"object"===("undefined"==typeof e?"undefined":F(e))&&null!==e}function n(e){if(!r(e))return!1;try{var t=e.constructor,a=t.prototype;return t&&a&&ie.call(a,"isPrototypeOf")}catch(e){return!1}}function s(e){return"function"===a(e)}function d(e){return Array.isArray?Array.isArray(e):"array"===a(e)}function l(e,t){return t=t>=0?t:0,Array.from?Array.from(e).slice(t):oe.call(e,t)}function c(e){return"string"==typeof e&&(e=e.trim?e.trim():e.replace(V,"$1")),e}function h(e,t){if(e&&s(t)){var a=void 0;if(d(e)||i(e.length)){var o=e.length;for(a=0;a<o&&t.call(e,e[a],a,e)!==!1;a++);}else r(e)&&Object.keys(e).forEach(function(a){t.call(e,e[a],a,e)})}return e}function p(){for(var e=arguments.length,t=Array(e),a=0;a<e;a++)t[a]=arguments[a];var i=t[0]===!0,o=i?t[1]:t[0];return t.length>1&&(t.shift(),t.forEach(function(e){r(e)&&Object.keys(e).forEach(function(t){i&&r(o[t])?p(!0,o[t],e[t]):o[t]=e[t]})})),o}function u(e,t){for(var a=arguments.length,i=Array(a>2?a-2:0),o=2;o<a;o++)i[o-2]=arguments[o];return function(){for(var a=arguments.length,o=Array(a),r=0;r<a;r++)o[r]=arguments[r];return e.apply(t,i.concat(o))}}function m(e,t){var a=e.style;h(t,function(e,t){K.test(t)&&i(e)&&(e+="px"),a[t]=e})}function f(e,t){return e.classList?e.classList.contains(t):e.className.indexOf(t)>-1}function v(e,t){if(i(e.length))return void h(e,function(e){v(e,t)});if(e.classList)return void e.classList.add(t);var a=c(e.className);a?a.indexOf(t)<0&&(e.className=a+" "+t):e.className=t}function g(e,t){return i(e.length)?void h(e,function(e){g(e,t)}):e.classList?void e.classList.remove(t):void(e.className.indexOf(t)>=0&&(e.className=e.className.replace(t,"")))}function w(e,t,a){return i(e.length)?void h(e,function(e){w(e,t,a)}):void(a?v(e,t):g(e,t))}function b(e){return e.replace(q,"$1-$2").toLowerCase()}function y(e,t){return r(e[t])?e[t]:e.dataset?e.dataset[t]:e.getAttribute("data-"+b(t))}function x(e,t,a){r(a)?e[t]=a:e.dataset?e.dataset[t]=a:e.setAttribute("data-"+b(t),a)}function C(e,t){r(e[t])?delete e[t]:e.dataset?delete e.dataset[t]:e.removeAttribute("data-"+b(t))}function M(e,t,a){var i=c(t).split(G);return i.length>1?void h(i,function(t){M(e,t,a)}):void(e.removeEventListener?e.removeEventListener(t,a,!1):e.detachEvent&&e.detachEvent("on"+t,a))}function D(e,t,a,i){var o=c(t).split(G),r=a;return o.length>1?void h(o,function(t){D(e,t,a)}):(i&&(a=function(){for(var i=arguments.length,o=Array(i),n=0;n<i;n++)o[n]=arguments[n];return M(e,t,a),r.apply(e,o)}),void(e.addEventListener?e.addEventListener(t,a,!1):e.attachEvent&&e.attachEvent("on${type}",a)))}function L(e,t,a){if(e.dispatchEvent){var i=void 0;return s(Event)&&s(CustomEvent)?i=o(a)?new Event(t,{bubbles:!0,cancelable:!0}):new CustomEvent(t,{detail:a,bubbles:!0,cancelable:!0}):o(a)?(i=document.createEvent("Event"),i.initEvent(t,!0,!0)):(i=document.createEvent("CustomEvent"),i.initCustomEvent(t,!0,!0,a)),e.dispatchEvent(i)}return!e.fireEvent||e.fireEvent("on"+t)}function B(e){var t=e||window.event;if(t.target||(t.target=t.srcElement||document),!i(t.pageX)&&i(t.clientX)){var a=e.target.ownerDocument||document,o=a.documentElement,r=a.body;t.pageX=t.clientX+((o&&o.scrollLeft||r&&r.scrollLeft||0)-(o&&o.clientLeft||r&&r.clientLeft||0)),t.pageY=t.clientY+((o&&o.scrollTop||r&&r.scrollTop||0)-(o&&o.clientTop||r&&r.clientTop||0))}return t}function O(e){var t=document.documentElement,a=e.getBoundingClientRect();return{left:a.left+((window.scrollX||t&&t.scrollLeft||0)-(t&&t.clientLeft||0)),top:a.top+((window.scrollY||t&&t.scrollTop||0)-(t&&t.clientTop||0))}}function T(e){var t=e.length,a=0,i=0;return t&&(h(e,function(e){a+=e.pageX,i+=e.pageY}),a/=t,i/=t),{pageX:a,pageY:i}}function E(e,t){return e.getElementsByTagName(t)}function N(e,t){return e.getElementsByClassName?e.getElementsByClassName(t):e.querySelectorAll("."+t)}function k(e){return document.createElement(e)}function X(e,t){e.appendChild(t)}function W(e){e.parentNode&&e.parentNode.removeChild(e)}function Y(e){for(;e.firstChild;)e.removeChild(e.firstChild)}function S(e){var t=e.match(Z);return t&&(t[1]!==location.protocol||t[2]!==location.hostname||t[3]!==location.port)}function H(e){var t="timestamp="+(new Date).getTime();return e+(e.indexOf("?")===-1?"?":"&")+t}function P(e,t){if(e.naturalWidth&&!ee)return void t(e.naturalWidth,e.naturalHeight);var a=k("img");a.onload=function(){t(this.width,this.height)},a.src=e.src}function z(e){var t=[],a=e.rotate,o=e.scaleX,r=e.scaleY;return i(a)&&0!==a&&t.push("rotate("+a+"deg)"),i(o)&&1!==o&&t.push("scaleX("+o+")"),i(r)&&1!==r&&t.push("scaleY("+r+")"),t.length?t.join(" "):"none"}function R(e,t){var a=Math.abs(e.degree)%180,i=(a>90?180-a:a)*Math.PI/180,o=Math.sin(i),r=Math.cos(i),n=e.width,s=e.height,d=e.aspectRatio,l=void 0,c=void 0;return t?(l=n/(r+o/d),c=l/d):(l=n*r+s*o,c=n*o+s*r),{width:l,height:c}}function A(e,t){var a=k("canvas"),o=a.getContext("2d"),r=0,n=0,s=t.naturalWidth,d=t.naturalHeight,l=t.rotate,c=t.scaleX,h=t.scaleY,p=i(c)&&i(h)&&(1!==c||1!==h),u=i(l)&&0!==l,m=u||p,f=s*Math.abs(c||1),v=d*Math.abs(h||1),g=void 0,w=void 0,b=void 0;return p&&(g=f/2,w=v/2),u&&(b=R({width:f,height:v,degree:l}),f=b.width,v=b.height,g=f/2,w=v/2),a.width=f,a.height=v,m&&(r=-s/2,n=-d/2,o.save(),o.translate(g,w)),u&&o.rotate(l*Math.PI/180),p&&o.scale(c,h),o.drawImage(e,Math.floor(r),Math.floor(n),Math.floor(s),Math.floor(d)),m&&o.restore(),a}function _(e,t,a){var i="",o=t;for(a+=t;o<a;o++)i+=re(e.getUint8(o));return i}function j(e){var t=new DataView(e),a=t.byteLength,i=void 0,o=void 0,r=void 0,n=void 0,s=void 0,d=void 0,l=void 0,c=void 0,h=void 0,p=void 0;if(255===t.getUint8(0)&&216===t.getUint8(1))for(h=2;h<a;){if(255===t.getUint8(h)&&225===t.getUint8(h+1)){l=h;break}h++}if(l&&(o=l+4,r=l+10,"Exif"===_(t,o,4)&&(d=t.getUint16(r),s=18761===d,(s||19789===d)&&42===t.getUint16(r+2,s)&&(n=t.getUint32(r+4,s),n>=8&&(c=r+n)))),c)for(a=t.getUint16(c,s),p=0;p<a;p++)if(h=c+12*p+2,274===t.getUint16(h,s)){h+=8,i=t.getUint16(h,s),ee&&t.setUint16(h,1,s);break}return i}function U(e){var t=e.replace($,""),a=atob(t),i=a.length,o=new ArrayBuffer(i),r=new Uint8Array(o),n=void 0;for(n=0;n<i;n++)r[n]=a.charCodeAt(n);return o}function I(e){var t=new Uint8Array(e),a=t.length,i="",o=void 0;for(o=0;o<a;o++)i+=re(t[o]);return"data:image/jpeg;base64,"+btoa(i)}Object.defineProperty(t,"__esModule",{value:!0});var F="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol?"symbol":typeof e};t.typeOf=a,t.isNumber=i,t.isUndefined=o,t.isObject=r,t.isPlainObject=n,t.isFunction=s,t.isArray=d,t.toArray=l,t.trim=c,t.each=h,t.extend=p,t.proxy=u,t.setStyle=m,t.hasClass=f,t.addClass=v,t.removeClass=g,t.toggleClass=w,t.hyphenate=b,t.getData=y,t.setData=x,t.removeData=C,t.removeListener=M,t.dispatchEvent=L,t.getEvent=B,t.getOffset=O,t.getTouchesCenter=T,t.getByTag=E,t.getByClass=N,t.createElement=k,t.appendChild=X,t.removeChild=W,t.empty=Y,t.isCrossOriginURL=S,t.addTimestamp=H,t.getImageSize=P,t.getTransform=z,t.getRotatedSizes=R,t.getSourceCanvas=A,t.getStringFromCharCode=_,t.getOrientation=j,t.dataURLToArrayBuffer=U,t.arrayBufferToDataURL=I;var $=/^data:([^;]+);base64,/,q=/([a-z\d])([A-Z])/g,Z=/^(https?:)\/\/([^:\/\?#]+):?(\d*)/i,G=/\s+/,K=/^(width|height|left|top|marginLeft|marginTop)$/,V=/^\s+(.*)\s+$/,J=/(Macintosh|iPhone|iPod|iPad).*AppleWebKit/i,Q=window.navigator,ee=Q&&J.test(Q.userAgent),te=Object.prototype,ae=te.toString,ie=te.hasOwnProperty,oe=Array.prototype.slice,re=String.fromCharCode;t.addListener=D},function(e,t,a){"use strict";function i(e){if(e&&e.__esModule)return e;var t={};if(null!=e)for(var a in e)Object.prototype.hasOwnProperty.call(e,a)&&(t[a]=e[a]);return t.default=e,t}Object.defineProperty(t,"__esModule",{value:!0});var o=a(4),r=i(o),n="preview";t.default={initPreview:function(){var e=this,t=e.options.preview,a=r.createElement("img"),i=e.crossOrigin,o=i?e.crossOriginUrl:e.url;if(i&&(a.crossOrigin=i),a.src=o,r.appendChild(e.viewBox,a),e.image2=a,t){var s=document.querySelectorAll(t);e.previews=s,r.each(s,function(e){var t=r.createElement("img");r.setData(e,n,{width:e.offsetWidth,height:e.offsetHeight,html:e.innerHTML}),i&&(t.crossOrigin=i),t.src=o,t.style.cssText='display:block;width:100%;height:auto;min-width:0!important;min-height:0!important;max-width:none!important;max-height:none!important;image-orientation:0deg!important;"',r.empty(e),r.appendChild(e,t)})}},resetPreview:function(){r.each(this.previews,function(e){var t=r.getData(e,n);r.setStyle(e,{width:t.width,height:t.height}),e.innerHTML=t.html,r.removeData(e,n)})},preview:function(){var e=this,t=e.imageData,a=e.canvasData,i=e.cropBoxData,o=i.width,s=i.height,d=t.width,l=t.height,c=i.left-a.left-t.left,h=i.top-a.top-t.top,p=r.getTransform(t),u={WebkitTransform:p,msTransform:p,transform:p};e.cropped&&!e.disabled&&(r.setStyle(e.image2,r.extend({width:d,height:l,marginLeft:-c,marginTop:-h},u)),r.each(e.previews,function(e){var t=r.getData(e,n),a=t.width,i=t.height,p=a,m=i,f=1;o&&(f=a/o,m=s*f),s&&m>i&&(f=i/s,p=o*f,m=i),r.setStyle(e,{width:p,height:m}),r.setStyle(r.getByTag(e,"img")[0],r.extend({width:d*f,height:l*f,marginLeft:-c*f,marginTop:-h*f},u))}))}}},function(e,t,a){"use strict";function i(e){if(e&&e.__esModule)return e;var t={};if(null!=e)for(var a in e)Object.prototype.hasOwnProperty.call(e,a)&&(t[a]=e[a]);return t.default=e,t}Object.defineProperty(t,"__esModule",{value:!0});var o=a(4),r=i(o),n="mousedown touchstart pointerdown MSPointerDown",s="mousemove touchmove pointermove MSPointerMove",d="mouseup touchend touchcancel pointerup pointercancel MSPointerUp MSPointerCancel",l="wheel mousewheel DOMMouseScroll",c="dblclick",h="resize",p="cropstart",u="cropmove",m="cropend",f="crop",v="zoom";t.default={bind:function(){var e=this,t=e.options,a=e.element,i=e.cropper;r.isFunction(t.cropstart)&&r.addListener(a,p,t.cropstart),r.isFunction(t.cropmove)&&r.addListener(a,u,t.cropmove),r.isFunction(t.cropend)&&r.addListener(a,m,t.cropend),r.isFunction(t.crop)&&r.addListener(a,f,t.crop),r.isFunction(t.zoom)&&r.addListener(a,v,t.zoom),r.addListener(i,n,e.onCropStart=r.proxy(e.cropStart,e)),t.zoomable&&t.zoomOnWheel&&r.addListener(i,l,e.onWheel=r.proxy(e.wheel,e)),t.toggleDragModeOnDblclick&&r.addListener(i,c,e.onDblclick=r.proxy(e.dblclick,e)),r.addListener(document,s,e.onCropMove=r.proxy(e.cropMove,e)),r.addListener(document,d,e.onCropEnd=r.proxy(e.cropEnd,e)),t.responsive&&r.addListener(window,h,e.onResize=r.proxy(e.resize,e))},unbind:function(){var e=this,t=e.options,a=e.element,i=e.cropper;r.isFunction(t.cropstart)&&r.removeListener(a,p,t.cropstart),r.isFunction(t.cropmove)&&r.removeListener(a,u,t.cropmove),r.isFunction(t.cropend)&&r.removeListener(a,m,t.cropend),r.isFunction(t.crop)&&r.removeListener(a,f,t.crop),r.isFunction(t.zoom)&&r.removeListener(a,v,t.zoom),r.removeListener(i,n,e.onCropStart),t.zoomable&&t.zoomOnWheel&&r.removeListener(i,l,e.onWheel),t.toggleDragModeOnDblclick&&r.removeListener(i,c,e.onDblclick),r.removeListener(document,s,e.onCropMove),r.removeListener(document,d,e.onCropEnd),t.responsive&&r.removeListener(window,h,e.onResize)}}},function(e,t,a){"use strict";function i(e){if(e&&e.__esModule)return e;var t={};if(null!=e)for(var a in e)Object.prototype.hasOwnProperty.call(e,a)&&(t[a]=e[a]);return t.default=e,t}Object.defineProperty(t,"__esModule",{value:!0}),t.REGEXP_ACTIONS=void 0;var o=a(4),r=i(o),n=t.REGEXP_ACTIONS=/^(e|w|s|n|se|sw|ne|nw|all|crop|move|zoom)$/;t.default={resize:function(){var e=this,t=e.options.restore,a=e.container,i=e.containerData;if(!e.disabled&&i){var o=a.offsetWidth/i.width,n=void 0,s=void 0;1===o&&a.offsetHeight===i.height||(t&&(n=e.getCanvasData(),s=e.getCropBoxData()),e.render(),t&&(e.setCanvasData(r.each(n,function(e,t){n[t]=e*o})),e.setCropBoxData(r.each(s,function(e,t){s[t]=e*o}))))}},dblclick:function(){var e=this;e.disabled||e.setDragMode(r.hasClass(e.dragBox,"cropper-crop")?"move":"crop")},wheel:function(e){var t=this,a=r.getEvent(e),i=Number(t.options.wheelZoomRatio)||.1,o=1;t.disabled||(a.preventDefault(),t.wheeling||(t.wheeling=!0,setTimeout(function(){t.wheeling=!1},50),a.deltaY?o=a.deltaY>0?1:-1:a.wheelDelta?o=-a.wheelDelta/120:a.detail&&(o=a.detail>0?1:-1),t.zoom(-o*i,a)))},cropStart:function(e){var t=this,a=t.options,i=r.getEvent(e),o=i.touches,s=void 0,d=void 0,l=void 0;if(!t.disabled){if(o){if(s=o.length,s>1){if(!a.zoomable||!a.zoomOnTouch||2!==s)return;d=o[1],t.startX2=d.pageX,t.startY2=d.pageY,l="zoom"}d=o[0]}if(l=l||r.getData(i.target,"action"),n.test(l)){if(r.dispatchEvent(t.element,"cropstart",{originalEvent:i,action:l})===!1)return;i.preventDefault(),t.action=l,t.cropping=!1,t.startX=d?d.pageX:i.pageX,t.startY=d?d.pageY:i.pageY,"crop"===l&&(t.cropping=!0,r.addClass(t.dragBox,"cropper-modal"))}}},cropMove:function(e){var t=this,a=t.options,i=r.getEvent(e),o=i.touches,n=t.action,s=void 0,d=void 0;if(!t.disabled){if(o){if(s=o.length,s>1){if(!a.zoomable||!a.zoomOnTouch||2!==s)return;d=o[1],t.endX2=d.pageX,t.endY2=d.pageY}d=o[0]}if(n){if(r.dispatchEvent(t.element,"cropmove",{originalEvent:i,action:n})===!1)return;i.preventDefault(),t.endX=d?d.pageX:i.pageX,t.endY=d?d.pageY:i.pageY,t.change(i.shiftKey,"zoom"===n?i:null)}}},cropEnd:function(e){var t=this,a=t.options,i=r.getEvent(e),o=t.action;t.disabled||o&&(i.preventDefault(),t.cropping&&(t.cropping=!1,r.toggleClass(t.dragBox,"cropper-modal",t.cropped&&a.modal)),t.action="",r.dispatchEvent(t.element,"cropend",{originalEvent:i,action:o}))}}},function(e,t,a){"use strict";function i(e){if(e&&e.__esModule)return e;var t={};if(null!=e)for(var a in e)Object.prototype.hasOwnProperty.call(e,a)&&(t[a]=e[a]);return t.default=e,t}Object.defineProperty(t,"__esModule",{value:!0});var o=a(4),r=i(o),n="e",s="w",d="s",l="n",c="se",h="sw",p="ne",u="nw";t.default={change:function(e,t){var a=this,i=a.options,o=a.containerData,m=a.canvasData,f=a.cropBoxData,v=i.aspectRatio,g=a.action,w=f.width,b=f.height,y=f.left,x=f.top,C=y+w,M=x+b,D=0,L=0,B=o.width,O=o.height,T=!0,E=void 0;!v&&e&&(v=w&&b?w/b:1),a.limited&&(D=f.minLeft,L=f.minTop,B=D+Math.min(o.width,m.width,m.left+m.width),O=L+Math.min(o.height,m.height,m.top+m.height));var N={x:a.endX-a.startX,y:a.endY-a.startY};switch(v&&(N.X=N.y*v,N.Y=N.x/v),g){case"all":y+=N.x,x+=N.y;break;case n:if(N.x>=0&&(C>=B||v&&(x<=L||M>=O))){T=!1;break}w+=N.x,v&&(b=w/v,x-=N.Y/2),w<0&&(g=s,w=0);break;case l:if(N.y<=0&&(x<=L||v&&(y<=D||C>=B))){T=!1;break}b-=N.y,x+=N.y,v&&(w=b*v,y+=N.X/2),b<0&&(g=d,b=0);break;case s:if(N.x<=0&&(y<=D||v&&(x<=L||M>=O))){T=!1;break}w-=N.x,y+=N.x,v&&(b=w/v,x+=N.Y/2),w<0&&(g=n,w=0);break;case d:if(N.y>=0&&(M>=O||v&&(y<=D||C>=B))){T=!1;break}b+=N.y,v&&(w=b*v,y-=N.X/2),b<0&&(g=l,b=0);break;case p:if(v){if(N.y<=0&&(x<=L||C>=B)){T=!1;break}b-=N.y,x+=N.y,w=b*v}else N.x>=0?C<B?w+=N.x:N.y<=0&&x<=L&&(T=!1):w+=N.x,N.y<=0?x>L&&(b-=N.y,x+=N.y):(b-=N.y,x+=N.y);w<0&&b<0?(g=h,b=0,w=0):w<0?(g=u,w=0):b<0&&(g=c,b=0);break;case u:if(v){if(N.y<=0&&(x<=L||y<=D)){T=!1;break}b-=N.y,x+=N.y,w=b*v,y+=N.X}else N.x<=0?y>D?(w-=N.x,y+=N.x):N.y<=0&&x<=L&&(T=!1):(w-=N.x,y+=N.x),N.y<=0?x>L&&(b-=N.y,x+=N.y):(b-=N.y,x+=N.y);w<0&&b<0?(g=c,b=0,w=0):w<0?(g=p,w=0):b<0&&(g=h,b=0);break;case h:if(v){if(N.x<=0&&(y<=D||M>=O)){T=!1;break}w-=N.x,y+=N.x,b=w/v}else N.x<=0?y>D?(w-=N.x,y+=N.x):N.y>=0&&M>=O&&(T=!1):(w-=N.x,y+=N.x),N.y>=0?M<O&&(b+=N.y):b+=N.y;w<0&&b<0?(g=p,b=0,w=0):w<0?(g=c,w=0):b<0&&(g=u,b=0);break;case c:if(v){if(N.x>=0&&(C>=B||M>=O)){T=!1;break}w+=N.x,b=w/v}else N.x>=0?C<B?w+=N.x:N.y>=0&&M>=O&&(T=!1):w+=N.x,N.y>=0?M<O&&(b+=N.y):b+=N.y;w<0&&b<0?(g=u,b=0,w=0):w<0?(g=h,w=0):b<0&&(g=p,b=0);break;case"move":a.move(N.x,N.y),T=!1;break;case"zoom":a.zoom(function(e,t,a,i){var o=Math.sqrt(e*e+t*t),r=Math.sqrt(a*a+i*i);return(r-o)/o}(Math.abs(a.startX-a.startX2),Math.abs(a.startY-a.startY2),Math.abs(a.endX-a.endX2),Math.abs(a.endY-a.endY2)),t),a.startX2=a.endX2,a.startY2=a.endY2,T=!1;break;case"crop":if(!N.x||!N.y){T=!1;break}E=r.getOffset(a.cropper),y=a.startX-E.left,x=a.startY-E.top,w=f.minWidth,b=f.minHeight,N.x>0?g=N.y>0?c:p:N.x<0&&(y-=w,g=N.y>0?h:u),N.y<0&&(x-=b),a.cropped||(r.removeClass(a.cropBox,"cropper-hidden"),a.cropped=!0,a.limited&&a.limitCropBox(!0,!0))}T&&(f.width=w,f.height=b,f.left=y,f.top=x,a.action=g,a.renderCropBox()),a.startX=a.endX,a.startY=a.endY}}},function(e,t,a){"use strict";function i(e){if(e&&e.__esModule)return e;var t={};if(null!=e)for(var a in e)Object.prototype.hasOwnProperty.call(e,a)&&(t[a]=e[a]);return t.default=e,t}function o(e){if(Array.isArray(e)){for(var t=0,a=Array(e.length);t<e.length;t++)a[t]=e[t];return a}return Array.from(e)}Object.defineProperty(t,"__esModule",{value:!0});var r=a(4),n=i(r);t.default={crop:function(){var e=this;return e.ready&&!e.disabled&&(e.cropped||(e.cropped=!0,e.limitCropBox(!0,!0),e.options.modal&&n.addClass(e.dragBox,"cropper-modal"),n.removeClass(e.cropBox,"cropper-hidden")),e.setCropBoxData(e.initialCropBoxData)),e},reset:function(){var e=this;return e.ready&&!e.disabled&&(e.imageData=n.extend({},e.initialImageData),e.canvasData=n.extend({},e.initialCanvasData),e.cropBoxData=n.extend({},e.initialCropBoxData),e.renderCanvas(),e.cropped&&e.renderCropBox()),e},clear:function(){var e=this;return e.cropped&&!e.disabled&&(n.extend(e.cropBoxData,{left:0,top:0,width:0,height:0}),e.cropped=!1,e.renderCropBox(),e.limitCanvas(),e.renderCanvas(),n.removeClass(e.dragBox,"cropper-modal"),n.addClass(e.cropBox,"cropper-hidden")),e},replace:function(e,t){var a=this;return!a.disabled&&e&&(a.isImg&&(a.element.src=e),t?(a.url=e,a.image.src=e,a.ready&&(a.image2.src=e,n.each(a.previews,function(t){n.getByTag(t,"img")[0].src=e}))):(a.isImg&&(a.replaced=!0),a.options.data=null,a.load(e))),a},enable:function(){var e=this;return e.ready&&(e.disabled=!1,n.removeClass(e.cropper,"cropper-disabled")),e},disable:function(){var e=this;return e.ready&&(e.disabled=!0,n.addClass(e.cropper,"cropper-disabled")),
e},destroy:function(){var e=this,t=e.element,a=e.image;return e.loaded?(e.isImg&&e.replaced&&(t.src=e.originalUrl),e.unbuild(),n.removeClass(t,"cropper-hidden")):e.isImg?n.removeListener(t,"load",e.start):a&&n.removeChild(a),n.removeData(t,"cropper"),e},move:function(e,t){var a=this,i=a.canvasData;return a.moveTo(n.isUndefined(e)?e:i.left+Number(e),n.isUndefined(t)?t:i.top+Number(t))},moveTo:function(e,t){var a=this,i=a.canvasData,o=!1;return n.isUndefined(t)&&(t=e),e=Number(e),t=Number(t),a.ready&&!a.disabled&&a.options.movable&&(n.isNumber(e)&&(i.left=e,o=!0),n.isNumber(t)&&(i.top=t,o=!0),o&&a.renderCanvas(!0)),a},zoom:function(e,t){var a=this,i=a.canvasData;return e=Number(e),e=e<0?1/(1-e):1+e,a.zoomTo(i.width*e/i.naturalWidth,t)},zoomTo:function(e,t){var a=this,i=a.options,o=a.canvasData,r=o.width,s=o.height,d=o.naturalWidth,l=o.naturalHeight,c=void 0,h=void 0,p=void 0,u=void 0;if(e=Number(e),e>=0&&a.ready&&!a.disabled&&i.zoomable){if(c=d*e,h=l*e,n.dispatchEvent(a.element,"zoom",{originalEvent:t,oldRatio:r/d,ratio:c/d})===!1)return a;t?(p=n.getOffset(a.cropper),u=t.touches?n.getTouchesCenter(t.touches):{pageX:t.pageX,pageY:t.pageY},o.left-=(c-r)*((u.pageX-p.left-o.left)/r),o.top-=(h-s)*((u.pageY-p.top-o.top)/s)):(o.left-=(c-r)/2,o.top-=(h-s)/2),o.width=c,o.height=h,a.renderCanvas(!0)}return a},rotate:function(e){var t=this;return t.rotateTo((t.imageData.rotate||0)+Number(e))},rotateTo:function(e){var t=this;return e=Number(e),n.isNumber(e)&&t.ready&&!t.disabled&&t.options.rotatable&&(t.imageData.rotate=e%360,t.rotated=!0,t.renderCanvas(!0)),t},scale:function(e,t){var a=this,i=a.imageData,o=!1;return n.isUndefined(t)&&(t=e),e=Number(e),t=Number(t),a.ready&&!a.disabled&&a.options.scalable&&(n.isNumber(e)&&(i.scaleX=e,o=!0),n.isNumber(t)&&(i.scaleY=t,o=!0),o&&a.renderImage(!0)),a},scaleX:function(e){var t=this,a=t.imageData.scaleY;return t.scale(e,n.isNumber(a)?a:1)},scaleY:function(e){var t=this,a=t.imageData.scaleX;return t.scale(n.isNumber(a)?a:1,e)},getData:function(e){var t=this,a=t.options,i=t.imageData,o=t.canvasData,r=t.cropBoxData,s=void 0,d=void 0;return t.ready&&t.cropped?(d={x:r.left-o.left,y:r.top-o.top,width:r.width,height:r.height},s=i.width/i.naturalWidth,n.each(d,function(t,a){t/=s,d[a]=e?Math.round(t):t})):d={x:0,y:0,width:0,height:0},a.rotatable&&(d.rotate=i.rotate||0),a.scalable&&(d.scaleX=i.scaleX||1,d.scaleY=i.scaleY||1),d},setData:function(e){var t=this,a=t.options,i=t.imageData,o=t.canvasData,r={},s=void 0,d=void 0,l=void 0;return n.isFunction(e)&&(e=e.call(t.element)),t.ready&&!t.disabled&&n.isPlainObject(e)&&(a.rotatable&&n.isNumber(e.rotate)&&e.rotate!==i.rotate&&(i.rotate=e.rotate,t.rotated=s=!0),a.scalable&&(n.isNumber(e.scaleX)&&e.scaleX!==i.scaleX&&(i.scaleX=e.scaleX,d=!0),n.isNumber(e.scaleY)&&e.scaleY!==i.scaleY&&(i.scaleY=e.scaleY,d=!0)),s?t.renderCanvas():d&&t.renderImage(),l=i.width/i.naturalWidth,n.isNumber(e.x)&&(r.left=e.x*l+o.left),n.isNumber(e.y)&&(r.top=e.y*l+o.top),n.isNumber(e.width)&&(r.width=e.width*l),n.isNumber(e.height)&&(r.height=e.height*l),t.setCropBoxData(r)),t},getContainerData:function(){var e=this;return e.ready?e.containerData:{}},getImageData:function(){var e=this;return e.loaded?e.imageData:{}},getCanvasData:function(){var e=this,t=e.canvasData,a={};return e.ready&&n.each(["left","top","width","height","naturalWidth","naturalHeight"],function(e){a[e]=t[e]}),a},setCanvasData:function(e){var t=this,a=t.canvasData,i=a.aspectRatio;return n.isFunction(e)&&(e=e.call(t.element)),t.ready&&!t.disabled&&n.isPlainObject(e)&&(n.isNumber(e.left)&&(a.left=e.left),n.isNumber(e.top)&&(a.top=e.top),n.isNumber(e.width)?(a.width=e.width,a.height=e.width/i):n.isNumber(e.height)&&(a.height=e.height,a.width=e.height*i),t.renderCanvas(!0)),t},getCropBoxData:function(){var e=this,t=e.cropBoxData,a=void 0;return e.ready&&e.cropped&&(a={left:t.left,top:t.top,width:t.width,height:t.height}),a||{}},setCropBoxData:function(e){var t=this,a=t.cropBoxData,i=t.options.aspectRatio,o=void 0,r=void 0;return n.isFunction(e)&&(e=e.call(t.element)),t.ready&&t.cropped&&!t.disabled&&n.isPlainObject(e)&&(n.isNumber(e.left)&&(a.left=e.left),n.isNumber(e.top)&&(a.top=e.top),n.isNumber(e.width)&&(o=!0,a.width=e.width),n.isNumber(e.height)&&(r=!0,a.height=e.height),i&&(o?a.height=a.width/i:r&&(a.width=a.height*i)),t.renderCropBox()),t},getCroppedCanvas:function(e){var t=this;if(!t.ready||!window.HTMLCanvasElement)return null;if(!t.cropped)return n.getSourceCanvas(t.image,t.imageData);n.isPlainObject(e)||(e={});var a=t.getData(),i=a.width,r=a.height,s=i/r,d=void 0,l=void 0,c=void 0;n.isPlainObject(e)&&(d=e.width,l=e.height,d?(l=d/s,c=d/i):l&&(d=l*s,c=l/r));var h=Math.floor(d||i),p=Math.floor(l||r),u=n.createElement("canvas"),m=u.getContext("2d");u.width=h,u.height=p,e.fillColor&&(m.fillStyle=e.fillColor,m.fillRect(0,0,h,p));var f=function(){var e=n.getSourceCanvas(t.image,t.imageData),o=e.width,s=e.height,d=t.canvasData,l=[e],h=a.x+d.naturalWidth*(Math.abs(a.scaleX||1)-1)/2,p=a.y+d.naturalHeight*(Math.abs(a.scaleY||1)-1)/2,u=void 0,m=void 0,f=void 0,v=void 0,g=void 0,w=void 0;return h<=-i||h>o?h=u=f=g=0:h<=0?(f=-h,h=0,u=g=Math.min(o,i+h)):h<=o&&(f=0,u=g=Math.min(i,o-h)),u<=0||p<=-r||p>s?p=m=v=w=0:p<=0?(v=-p,p=0,m=w=Math.min(s,r+p)):p<=s&&(v=0,m=w=Math.min(r,s-p)),l.push(Math.floor(h),Math.floor(p),Math.floor(u),Math.floor(m)),c&&(f*=c,v*=c,g*=c,w*=c),g>0&&w>0&&l.push(Math.floor(f),Math.floor(v),Math.floor(g),Math.floor(w)),l}();return m.drawImage.apply(m,o(f)),u},setAspectRatio:function(e){var t=this,a=t.options;return t.disabled||n.isUndefined(e)||(a.aspectRatio=Math.max(0,e)||NaN,t.ready&&(t.initCropBox(),t.cropped&&t.renderCropBox())),t},setDragMode:function(e){var t=this,a=t.options,i=t.dragBox,o=t.face,r=void 0,s=void 0;return t.loaded&&!t.disabled&&(r="crop"===e,s=a.movable&&"move"===e,e=r||s?e:"none",n.setData(i,"action",e),n.toggleClass(i,"cropper-crop",r),n.toggleClass(i,"cropper-move",s),a.cropBoxMovable||(n.setData(o,"action",e),n.toggleClass(o,"cropper-crop",r),n.toggleClass(o,"cropper-move",s))),t}}}])});