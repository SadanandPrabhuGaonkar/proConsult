<?php
    defined('C5_EXECUTE') or die("Access Denied.");
    /*
    ========================================

    Don't remove this template! It's used in the Dashboard.

    ========================================
    */
?>
<style>
    .formidable .option_other { display: none; margin-top:15px; }
    .formidable textarea { height: 300px; }
    .formidable .counter {clear:both;line-height:18px;color:#999;font-size:12px;}
    .formidable .counter span {display:inline-block;}
    .formidable .option_other{display:none;}
    .formidable select[multiple] { padding-right: 20px; background: none; }

    .formidable .captcha_image{overflow:hidden;}
    .formidable .captcha_image div{line-height:18px;color:#999;font-size:12px;}
    .formidable .captcha_input{line-height:18px;color:#999;font-size:12px;overflow:hidden;}
    .formidable .captcha_input input{width:190px!important;}
    .formidable .captcha_input div { clear: both;}

    .formidable .form-control.day { width: 75px; display: inline; }
    .formidable .form-control.month { width: 75px; display: inline; }
    .formidable .form-control.year { width: 100px; display: inline; }
    .formidable .form-control.hour { width: 75px; display: inline; }
    .formidable .form-control.minute { width: 75px; display: inline; }
    .formidable .form-control.second { width: 75px; display: inline; }
    .formidable .form-control.ampm { width: 75px; display: inline; }

    .formidable .form-control.firstname {width:36%; margin-right: 2%; float:left; display:inline;}
    .formidable .form-control.prefix {width:20%; margin-right: 2%; float:left; display:inline;}
    .formidable .form-control.lastname {width:62%; float:left; display:inline;}
    .formidable .form-control.prefix + .form-control.lastname { width:40% !important; }

    .formidable .range input[type="range"] { margin: 7px 0; }

    .formidable .captcha_holder { display: inline-block; }
    .formidable .captcha_input br { display: none; }

    .formidable .w100 { width:100%; }
    .formidable .w50 { width:50%; }
    .formidable .w33 { width: 33%; }
    .formidable .w25 { width:25%; }
    .formidable .w20 { width: 20%; }
    .formidable .w12 { width:12.5%; }
    .formidable .w6 { width:6.25%; }
    .formidable .auto { padding-right: 10px; float: left; }

    .formidable span.error { display: block; margin-top: 5px;}
    .formidable .please_wait_loader { display: inline-block;margin-left: 10px; display:none; }

    @-webkit-keyframes passing-through {
      0% { opacity: 0; -webkit-transform: translateY(40px); -moz-transform: translateY(40px); -ms-transform: translateY(40px); -o-transform: translateY(40px); transform: translateY(40px); }
      30%, 70% { opacity: 1; -webkit-transform: translateY(0px); -moz-transform: translateY(0px); -ms-transform: translateY(0px); -o-transform: translateY(0px); transform: translateY(0px); }
      100% { opacity: 0; -webkit-transform: translateY(-40px); -moz-transform: translateY(-40px); -ms-transform: translateY(-40px); -o-transform: translateY(-40px); transform: translateY(-40px); }
    }
    @-moz-keyframes passing-through {
      0% { opacity: 0; -webkit-transform: translateY(40px); -moz-transform: translateY(40px); -ms-transform: translateY(40px); -o-transform: translateY(40px); transform: translateY(40px); }
      30%, 70% { opacity: 1; -webkit-transform: translateY(0px); -moz-transform: translateY(0px); -ms-transform: translateY(0px); -o-transform: translateY(0px); transform: translateY(0px); }
      100% { opacity: 0; -webkit-transform: translateY(-40px); -moz-transform: translateY(-40px); -ms-transform: translateY(-40px); -o-transform: translateY(-40px); transform: translateY(-40px); }
    }
    @keyframes passing-through {
      0% { opacity: 0; -webkit-transform: translateY(40px); -moz-transform: translateY(40px); -ms-transform: translateY(40px); -o-transform: translateY(40px); transform: translateY(40px); }
      30%, 70% { opacity: 1; -webkit-transform: translateY(0px); -moz-transform: translateY(0px); -ms-transform: translateY(0px); -o-transform: translateY(0px); transform: translateY(0px); }
      100% { opacity: 0; -webkit-transform: translateY(-40px); -moz-transform: translateY(-40px); -ms-transform: translateY(-40px); -o-transform: translateY(-40px); transform: translateY(-40px); }
    }
    @-webkit-keyframes slide-in {
      0% { opacity: 0; -webkit-transform: translateY(40px); -moz-transform: translateY(40px); -ms-transform: translateY(40px); -o-transform: translateY(40px); transform: translateY(40px); }
      30% { opacity: 1; -webkit-transform: translateY(0px); -moz-transform: translateY(0px); -ms-transform: translateY(0px); -o-transform: translateY(0px); transform: translateY(0px); }
    }
    @-moz-keyframes slide-in {
      0% { opacity: 0; -webkit-transform: translateY(40px); -moz-transform: translateY(40px); -ms-transform: translateY(40px); -o-transform: translateY(40px); transform: translateY(40px); }
      30% { opacity: 1; -webkit-transform: translateY(0px); -moz-transform: translateY(0px); -ms-transform: translateY(0px); -o-transform: translateY(0px); transform: translateY(0px); }
    }
    @keyframes slide-in {
      0% { opacity: 0; -webkit-transform: translateY(40px); -moz-transform: translateY(40px); -ms-transform: translateY(40px); -o-transform: translateY(40px); transform: translateY(40px); }
      30% { opacity: 1; -webkit-transform: translateY(0px); -moz-transform: translateY(0px); -ms-transform: translateY(0px); -o-transform: translateY(0px); transform: translateY(0px); }
    }
    @-webkit-keyframes pulse {
      0% { -webkit-transform: scale(1); -moz-transform: scale(1); -ms-transform: scale(1); -o-transform: scale(1); transform: scale(1); }
      10% { -webkit-transform: scale(1.1); -moz-transform: scale(1.1); -ms-transform: scale(1.1); -o-transform: scale(1.1); transform: scale(1.1); }
      20% { -webkit-transform: scale(1); -moz-transform: scale(1); -ms-transform: scale(1); -o-transform: scale(1); transform: scale(1); }
    }
    @-moz-keyframes pulse {
      0% { -webkit-transform: scale(1); -moz-transform: scale(1); -ms-transform: scale(1); -o-transform: scale(1); transform: scale(1); }
      10% { -webkit-transform: scale(1.1); -moz-transform: scale(1.1); -ms-transform: scale(1.1); -o-transform: scale(1.1); transform: scale(1.1); }
      20% { -webkit-transform: scale(1); -moz-transform: scale(1); -ms-transform: scale(1); -o-transform: scale(1); transform: scale(1); }
    }
    @keyframes pulse {
      0% { -webkit-transform: scale(1); -moz-transform: scale(1); -ms-transform: scale(1); -o-transform: scale(1); transform: scale(1); }
      10% { -webkit-transform: scale(1.1); -moz-transform: scale(1.1); -ms-transform: scale(1.1); -o-transform: scale(1.1); transform: scale(1.1); }
      20% { -webkit-transform: scale(1); -moz-transform: scale(1); -ms-transform: scale(1); -o-transform: scale(1); transform: scale(1); }
    }

    .formidable .file_upload, .formidable .file_upload * { box-sizing: border-box; }
    .formidable .file_upload { border-radius: 2px; border: 1px solid #dedede; background: white; padding: 10px; }
    .formidable .file_upload .dz-clickable { cursor: pointer; }
    .formidable .file_upload .dz-clickable * { cursor: default; }
    .formidable .file_upload .dz-clickable .dz-message, .formidable .file_upload .dz-clickable .dz-message * { cursor: pointer; }
    .formidable .file_upload .dz-started .dz-message { display: none; }
    .formidable .file_upload .dz-drag-hover { border: 1px dashed #ddd; }
    .formidable .file_upload .dz-drag-hover .dz-message { opacity: 0.5; }
    .formidable .file_upload .dz-message { text-align: center; padding: 2em 0; color: #555555; }
    .formidable .file_upload .dz-preview { position: relative; display: inline-block; vertical-align: top; margin: 16px; min-height: 100px; }
    .formidable .file_upload .dz-preview:hover { z-index: 1000; }
    .formidable .file_upload .dz-preview:hover .dz-details { opacity: 1; }
    .formidable .file_upload .dz-preview.dz-file-preview .dz-image { border-radius: 10px; background: #999; background: linear-gradient(to bottom, #eee, #ddd); }
    .formidable .file_upload .dz-preview.dz-file-preview .dz-details { opacity: 1; }
    .formidable .file_upload .dz-preview.dz-image-preview { background: white; }
    .formidable .file_upload .dz-preview.dz-image-preview .dz-details { -webkit-transition: opacity 0.2s linear; -moz-transition: opacity 0.2s linear; -ms-transition: opacity 0.2s linear; -o-transition: opacity 0.2s linear; transition: opacity 0.2s linear; }
    .formidable .file_upload .dz-preview .dz-remove { font-size: 12px; text-align: center; display: block; cursor: pointer; border: none; margin-top:5px; }
    .formidable .file_upload .dz-preview:hover .dz-details { opacity: 1; }
    .formidable .file_upload .dz-preview .dz-details { z-index: 20; position: absolute; top: 0; left: 0; opacity: 0; font-size: 13px; min-width: 100%; max-width: 100%; padding: 2em 1em; text-align: center; color: rgba(0, 0, 0, 0.9); line-height: 150%; }
    .formidable .file_upload .dz-preview .dz-details .dz-size { margin-bottom: 1em; font-size: 16px; }

    .formidable .file_upload .dz-preview .dz-details .dz-filename span, .formidable .file_upload .dz-preview .dz-details .dz-size span { word-wrap: break-word; background-color: rgba(255, 255, 255, 0.4); padding: 0 0.4em; border-radius: 3px; }
    .formidable .file_upload .dz-preview:hover .dz-image img { -webkit-transform: scale(1.05, 1.05); -moz-transform: scale(1.05, 1.05); -ms-transform: scale(1.05, 1.05); -o-transform: scale(1.05, 1.05); transform: scale(1.05, 1.05); -webkit-filter: blur(8px); filter: blur(8px); }
    .formidable .file_upload .dz-preview .dz-image { border-radius: 20px; overflow: hidden; width: 120px; height: 120px; position: relative; display: block; z-index: 10; }
    .formidable .file_upload .dz-preview .dz-image img { display: block; }
    .formidable .file_upload .dz-preview.dz-success .dz-success-mark { -webkit-animation: passing-through 3s cubic-bezier(0.77, 0, 0.175, 1); -moz-animation: passing-through 3s cubic-bezier(0.77, 0, 0.175, 1); -ms-animation: passing-through 3s cubic-bezier(0.77, 0, 0.175, 1); -o-animation: passing-through 3s cubic-bezier(0.77, 0, 0.175, 1); animation: passing-through 3s cubic-bezier(0.77, 0, 0.175, 1); }

    .formidable .file_upload .dz-preview.dz-error .dz-error-mark { opacity: 1; -webkit-animation: slide-in 3s cubic-bezier(0.77, 0, 0.175, 1); -moz-animation: slide-in 3s cubic-bezier(0.77, 0, 0.175, 1); -ms-animation: slide-in 3s cubic-bezier(0.77, 0, 0.175, 1); -o-animation: slide-in 3s cubic-bezier(0.77, 0, 0.175, 1); animation: slide-in 3s cubic-bezier(0.77, 0, 0.175, 1); }
    .formidable .file_upload .dz-preview .dz-success-mark, .formidable .file_upload .dz-preview .dz-error-mark { pointer-events: none; opacity: 0; z-index: 500; position: absolute; display: block; top: 50%; left: 50%; margin-left: -27px; margin-top: -27px; }
    .formidable .file_upload .dz-preview .dz-success-mark svg, .formidable .file_upload .dz-preview .dz-error-mark svg { display: block; width: 54px; height: 54px; }
    .formidable .file_upload .dz-preview.dz-processing .dz-progress { opacity: 1; -webkit-transition: all 0.2s linear; -moz-transition: all 0.2s linear; -ms-transition: all 0.2s linear; -o-transition: all 0.2s linear; transition: all 0.2s linear; }
    .formidable .file_upload .dz-preview.dz-complete .dz-progress { opacity: 0; -webkit-transition: opacity 0.4s ease-in; -moz-transition: opacity 0.4s ease-in; -ms-transition: opacity 0.4s ease-in; -o-transition: opacity 0.4s ease-in; transition: opacity 0.4s ease-in; }
    .formidable .file_upload .dz-preview:not(.dz-processing) .dz-progress { -webkit-animation: pulse 6s ease infinite; -moz-animation: pulse 6s ease infinite; -ms-animation: pulse 6s ease infinite; -o-animation: pulse 6s ease infinite; animation: pulse 6s ease infinite; }
    .formidable .file_upload .dz-preview .dz-progress { opacity: 1; z-index: 1000; pointer-events: none; position: absolute; height: 16px; left: 50%; top: 50%; margin-top: -8px; width: 80px; margin-left: -40px; background: rgba(255, 255, 255, 0.9); -webkit-transform: scale(1); border-radius: 8px; overflow: hidden; }
    .formidable .file_upload .dz-preview .dz-progress .dz-upload { background: #333; background: linear-gradient(to bottom, #666, #444); position: absolute; top: 0; left: 0; bottom: 0; width: 0; -webkit-transition: width 300ms ease-in-out; -moz-transition: width 300ms ease-in-out; -ms-transition: width 300ms ease-in-out; -o-transition: width 300ms ease-in-out; transition: width 300ms ease-in-out; }

    .formidable .file_upload .dz-preview.dz-error .dz-error-message { width: 120px; display: block; }
    .formidable .file_upload .dz-preview.dz-error .dz-image, .formidable .file_upload .dz-preview.dz-error .dz-details, .formidable .file_upload .dz-preview.dz-error .dz-progress, .formidable .file_upload .dz-preview.dz-error .dz-error-mark, .formidable .file_upload .dz-preview.dz-error .dz-remove { display: none; }

    .formidable .slider { display: block; vertical-align: middle; position: relative }
    .formidable .slider.slider-horizontal { margin-top: 9px; width: 100%; height: 20px; }
    .formidable .slider.slider-horizontal .slider-track { height: 10px; width: 100%; margin-top: -5px; top: 50%; left: 0 }
    .formidable .slider.slider-horizontal .slider-selection,
    .formidable .slider.slider-horizontal .slider-track-low,
    .formidable .slider.slider-horizontal .slider-track-high { height: 100%; top: 0; bottom: 0 }
    .formidable .slider.slider-horizontal .slider-tick,
    .formidable .slider.slider-horizontal .slider-handle { margin-left: -10px }
    .formidable .slider.slider-horizontal .slider-tick.triangle,
    .formidable .slider.slider-horizontal .slider-handle.triangle { position: relative; top: 50%; transform: translateY(-50%); border-width: 0 10px 10px 10px; width: 0; height: 0; border-bottom-color: #0480be; margin-top: 0 }
    .formidable .slider.slider-horizontal .slider-tick-container { white-space: nowrap; position: absolute; top: 0; left: 0; width: 100% }
    .formidable .slider.slider-horizontal .slider-tick-label-container { white-space: nowrap; margin-top: 20px }
    .formidable .slider.slider-horizontal .slider-tick-label-container .slider-tick-label { padding-top: 4px; display: inline-block; text-align: center }
    .formidable .slider.slider-horizontal.slider-rtl .slider-track { left: initial; right: 0 }
    .formidable .slider.slider-horizontal.slider-rtl .slider-tick,
    .formidable .slider.slider-horizontal.slider-rtl .slider-handle { margin-left: initial; margin-right: -10px }
    .formidable .slider.slider-horizontal.slider-rtl .slider-tick-container { left: initial; right: 0 }
    .formidable .slider.slider-vertical { height: 210px; width: 20px }
    .formidable .slider.slider-vertical .slider-track { width: 10px; height: 100%; left: 25%; top: 0 }
    .formidable .slider.slider-vertical .slider-selection { width: 100%; left: 0; top: 0; bottom: 0 }
    .formidable .slider.slider-vertical .slider-track-low,
    .formidable .slider.slider-vertical .slider-track-high { width: 100%; left: 0; right: 0 }
    .formidable .slider.slider-vertical .slider-tick,
    .formidable .slider.slider-vertical .slider-handle { margin-top: -10px }
    .formidable .slider.slider-vertical .slider-tick.triangle,
    .formidable .slider.slider-vertical .slider-handle.triangle { border-width: 10px 0 10px 10px; width: 1px; height: 1px; border-left-color: #0480be; border-right-color: #0480be; margin-left: 0; margin-right: 0 }
    .formidable .slider.slider-vertical .slider-tick-label-container { white-space: nowrap }
    .formidable .slider.slider-vertical .slider-tick-label-container .slider-tick-label { padding-left: 4px }
    .formidable .slider.slider-vertical.slider-rtl .slider-track { left: initial; right: 25% }
    .formidable .slider.slider-vertical.slider-rtl .slider-selection { left: initial; right: 0 }
    .formidable .slider.slider-vertical.slider-rtl .slider-tick.triangle,
    .formidable .slider.slider-vertical.slider-rtl .slider-handle.triangle { border-width: 10px 10px 10px 0 }
    .formidable .slider.slider-vertical.slider-rtl .slider-tick-label-container .slider-tick-label { padding-left: initial; padding-right: 4px }
    .formidable .slider.slider-disabled .slider-handle { background-image: -webkit-linear-gradient(top, #dfdfdf 0%, #bebebe 100%); background-image: -o-linear-gradient(top, #dfdfdf 0%, #bebebe 100%); background-image: linear-gradient(to bottom, #dfdfdf 0%, #bebebe 100%); background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffdfdfdf', endColorstr='#ffbebebe', GradientType=0) }
    .formidable .slider.slider-disabled .slider-track { background-image: -webkit-linear-gradient(top, #e5e5e5 0%, #e9e9e9 100%); background-image: -o-linear-gradient(top, #e5e5e5 0%, #e9e9e9 100%); background-image: linear-gradient(to bottom, #e5e5e5 0%, #e9e9e9 100%); background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffe5e5e5', endColorstr='#ffe9e9e9', GradientType=0); cursor: not-allowed }
    .formidable .slider input { display: none }
    .formidable .slider .tooltip.top { margin-top: -36px }
    .formidable .slider .tooltip-inner { white-space: nowrap; max-width: none }
    .formidable .slider .hide { display: none }
    .formidable .slider-track { position: absolute; cursor: pointer; background-image: -webkit-linear-gradient(top, #f5f5f5 0%, #f9f9f9 100%); background-image: -o-linear-gradient(top, #f5f5f5 0%, #f9f9f9 100%); background-image: linear-gradient(to bottom, #f5f5f5 0%, #f9f9f9 100%); background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fff5f5f5', endColorstr='#fff9f9f9', GradientType=0); -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1); box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1); border-radius: 4px }
    .formidable .slider-selection { position: absolute; background-image: -webkit-linear-gradient(top, #f9f9f9 0%, #f5f5f5 100%); background-image: -o-linear-gradient(top, #f9f9f9 0%, #f5f5f5 100%); background-image: linear-gradient(to bottom, #f9f9f9 0%, #f5f5f5 100%); background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fff9f9f9', endColorstr='#fff5f5f5', GradientType=0); -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15); box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15); -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border-radius: 4px }
    .formidable .slider-selection.tick-slider-selection { background-image: -webkit-linear-gradient(top, #89cdef 0%, #81bfde 100%); background-image: -o-linear-gradient(top, #89cdef 0%, #81bfde 100%); background-image: linear-gradient(to bottom, #89cdef 0%, #81bfde 100%); background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff89cdef', endColorstr='#ff81bfde', GradientType=0) }
    .formidable .slider-track-low,
    .formidable .slider-track-high { position: absolute; background: transparent; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border-radius: 4px }
    .formidable .slider-handle { position: absolute; top: 0; width: 20px; height: 20px; background-color: #337ab7; background-image: -webkit-linear-gradient(top, #149bdf 0%, #0480be 100%); background-image: -o-linear-gradient(top, #149bdf 0%, #0480be 100%); background-image: linear-gradient(to bottom, #149bdf 0%, #0480be 100%); background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff149bdf', endColorstr='#ff0480be', GradientType=0); filter: none; -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, .2), 0 1px 2px rgba(0, 0, 0, .05); box-shadow: inset 0 1px 0 rgba(255, 255, 255, .2), 0 1px 2px rgba(0, 0, 0, .05); border: 0px solid transparent }
    .formidable .slider-handle.round { border-radius: 50% }
    .formidable .slider-handle.triangle { background: transparent none }
    .formidable .slider-handle.custom { background: transparent none }
    .formidable .slider-handle.custom::before { line-height: 20px; font-size: 20px; content: '\2605'; color: #726204 }
    .formidable .slider-tick { position: absolute; width: 20px; height: 20px; background-image: -webkit-linear-gradient(top, #f9f9f9 0%, #f5f5f5 100%); background-image: -o-linear-gradient(top, #f9f9f9 0%, #f5f5f5 100%); background-image: linear-gradient(to bottom, #f9f9f9 0%, #f5f5f5 100%); background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fff9f9f9', endColorstr='#fff5f5f5', GradientType=0); -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15); box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15); -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; filter: none; opacity: 0.8; border: 0px solid transparent }
    .formidable .slider-tick.round { border-radius: 50% }
    .formidable .slider-tick.triangle { background: transparent none }
    .formidable .slider-tick.custom { background: transparent none }
    .formidable .slider-tick.custom::before { line-height: 20px; font-size: 20px; content: '\2605'; color: #726204 }
    .formidable .slider-tick.in-selection { background-image: -webkit-linear-gradient(top, #89cdef 0%, #81bfde 100%); background-image: -o-linear-gradient(top, #89cdef 0%, #81bfde 100%); background-image: linear-gradient(to bottom, #89cdef 0%, #81bfde 100%); background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff89cdef', endColorstr='#ff81bfde', GradientType=0); opacity: 1 }

    .formidable .rating-loading{width:25px;height:25px;font-size:0;color:#fff;background:url(../img/loading.gif) top left no-repeat;border:none}
    .formidable .rating-container .rating-stars{position:relative;cursor:pointer;vertical-align:middle;display:inline-block;overflow:hidden;white-space:nowrap}
    .formidable .rating-container .rating-input{position:absolute;cursor:pointer;width:100%;height:1px;bottom:0;left:0;font-size:1px;border:none;background:0 0;padding:0;margin:0}
    .formidable .rating-disabled .rating-input,.rating-disabled .rating-stars{cursor:not-allowed}
    .formidable .rating-container .star{display:inline-block;margin:0 3px;text-align:center}
    .formidable .rating-container .empty-stars{color:#aaa}
    .formidable .rating-container .filled-stars{position:absolute;left:0;top:0;margin:auto;color:#fde16d;white-space:nowrap;overflow:hidden;-webkit-text-stroke:1px #777;text-shadow:1px 1px #999}
    .formidable .rating-rtl{float:right}
    .formidable .rating-animate .filled-stars{transition:width .25s ease;-o-transition:width .25s ease;-moz-transition:width .25s ease;-webkit-transition:width .25s ease}
    .formidable .rating-rtl .filled-stars{left:auto;right:0;-moz-transform:matrix(-1,0,0,1,0,0) translate3d(0,0,0);-webkit-transform:matrix(-1,0,0,1,0,0) translate3d(0,0,0);-o-transform:matrix(-1,0,0,1,0,0) translate3d(0,0,0);transform:matrix(-1,0,0,1,0,0) translate3d(0,0,0)}
    .formidable .rating-rtl.is-star .filled-stars{right:.06em}
    .formidable .rating-rtl.is-heart .empty-stars{margin-right:.07em}
    .formidable .rating-xl{font-size:4em}
    .formidable .rating-lg{font-size:3em}
    .formidable .rating-md{font-size:2.5em}
    .formidable .rating-sm{font-size:2em}
    .formidable .rating-xs{font-size:1.5em}
    .formidable .rating-container .clear-rating{color:#aaa;cursor:not-allowed;display:inline-block;vertical-align:middle;font-size:60%;padding-right:5px}
    .formidable .clear-rating-active{cursor:pointer!important}
    .formidable .clear-rating-active:hover{color:#843534}
    .formidable .rating-container .caption{color:#999;display:inline-block;vertical-align:middle;font-size:60%;margin-top:-.6em;margin-left:5px;margin-right:0}
    .formidable .rating-rtl .caption{margin-right:5px;margin-left:0}
    @media print{
        .formidable .rating-container .clear-rating{display:none}
    }
    .formidable .theme-formidable-fa .star { font-size: 1.1em; }
    .formidable .theme-formidable-fa .caption { margin-top: -0.2em; }
</style>

<div class="ccm-ui">
<?php if (!$f->getFormID()) { ?>
    <div class="alert alert-danger">
        <?php echo t('Can\'t find the Formidable Form'); ?>
    </div>
<?php } else { ?>

    <div id="formidable_container_<?php echo $f->getFormID() ?>" class="formidable <?php echo $error?'error':'' ?>">
        <div class="container-fluid">

            <div id="formidable_message_<?php echo $f->getFormID() ?>" class="formidable_message">
                <?php if ($limits) { ?><div class="alert alert-warning"><?php echo $limits; ?></div><?php } ?>
                <?php if ($schedule) { ?><div class="alert alert-info"><?php echo $schedule; ?></div><?php } ?>
                <?php if ($error) { ?>
                    <div class="alert alert-danger">
                        <?php foreach ((array)$error as $er) { ?>
                            <div><?php echo $er ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

            <form id="ff_<?php echo $f->getFormID() ?>" name="formidable_form" method="post" class="form-horizontal <?php echo $f->getAttribute('class'); ?>" role="form" action="<?php echo \URL::to('/formidable/dialog/formidable'); ?>">
                <input type="hidden" name="dashboard" id="dashboard" value="<?php echo $view_type?$view_type:'preview'; ?>">
                <input type="hidden" name="formID" id="formID" value="<?php echo $f->getFormID(); ?>">
                <input type="hidden" name="cID" id="cID" value="<?php echo $f->getCollectionID(); ?>">
                <input type="hidden" name="bID" id="bID" value="<?php echo $f->getBlockID(); ?>">
                <input type="hidden" name="resolution" id="resolution" value="">
                <input type="hidden" name="ccm_token" id="ccm_token" value="<?php echo $f->getToken(); ?>">
                <input type="hidden" name="locale" id="locale" value="<?php echo $f->getLocale; ?>">
                <?php
            $layout = $f->getLayout();
            if ($layout && count($layout) && is_array($layout)) {
                foreach($layout as $row) { ?>
                    <div class="formidable_row row">
                    <?php
                        $i=0;
                        $width = round(12/count($row));
                        if ($view_type != 'preview') $width = 12;
                        foreach($row as $column) { ?>
                            <div class="formidable_column col-sm-<?php echo $width; ?> <?php echo ($i==(count($row)-1)?' last':''); ?>">
                            <?php
                                echo ($view_type == 'preview')?$column->getContainerStart():'';
                                $elements = $column->getElements();
                                if($elements && count($elements) && is_array($elements)) {
                                    foreach($elements as $element) {
                                        if ($element->getElementType() == 'captcha') continue;
                                        elseif (in_array($element->getElementType(), array('hidden', 'captcha', 'hr', 'heading', 'line'))) echo $element->getInput();
                                        else { ?>
                                            <div class="element form-group <?php echo $element->getHandle(); ?>">
                                                <?php if ($column->hasElementsWithLabels()) { ?>
                                                    <?php if ($element->getPropertyValue('label_hide')) { ?>
                                                        <div class="col-sm-3"></div>
                                                    <?php } else { ?>
                                                        <label for="<?php echo $element->getHandle(); ?>" class="col-sm-3 control-label">
                                                            <?php echo $element->getLabel(); ?>
                                                            <?php if ($element->getPropertyValue('required')) { ?>
                                                                <span class="required">*</span>
                                                            <?php } ?>
                                                        </label>
                                                    <?php } ?>
                                                <?php } ?>
                                                <div class="input <?php echo $column->hasElementsWithLabels()?'col-sm-9':'col-sm-12'; ?> <?php echo $element->getPropertyValue('label_hide')?'no_label':'has_label'; ?>">

                                                    <?php
                                                        // Changing elements format (for checkboxes and radios)
                                                        //$element->setFormat('<div class="radio {SIZE}"><label for="{ID}">{ELEMENT} {TITLE}</label></div>');
                                                        echo $element->getInput();
                                                    ?>

                                                    <?php if ($element->getPropertyValue('min_max')) { ?>
                                                        <div class="help-block">
                                                            <div id="<?php echo $element->getHandle() ?>_counter" class="counter" type="<?php echo $element->getPropertyValue('min_max_type') ?>" min="<?php echo $element->getPropertyValue('min_value') ?>" max="<?php echo $element->getPropertyValue('max_value') ?>">
                                                                <?php if ($element->getPropertyValue('max_value') > 0) { ?>
                                                                    <?php  echo t('You have') ?> <span id="<?php echo $element->getHandle() ?>_count"><?php echo $element->getPropertyValue('max_value') ?></span> <?php  echo ($element->getPropertyValue('min_max_type_value')!='value')?$element->getPropertyValue('min_max_type_value'):t('characters'); ?> <?php echo t('left')?>.
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <?php if ($element->getPropertyValue('option_other')) { ?>
                                                    <?php if ($column->hasElementsWithLabels()) { ?>
                                                        <div class="col-sm-3 control-label"></div>
                                                    <?php } ?>
                                                    <div class="input option_other <?php echo $column->hasElementsWithLabels()?'col-sm-9':'col-sm-12'; ?> <?php echo $element->getPropertyValue('label_hide')?'no_label':'has_label'; ?>">
                                                        <?php echo $element->getOther(); ?>
                                                    </div>
                                                <?php } ?>

                                                <?php if ($element->getPropertyValue('confirmation')) { ?>
                                                    <div class="clearfix"></div>
                                                    <?php if ($column->hasElementsWithLabels()) { ?>
                                                        <?php if ($element->getPropertyValue('label_hide')) { ?>
                                                            <div class="col-sm-3"></div>
                                                        <?php } else { ?>
                                                            <label for="<?php echo $element->getHandle(); ?>" class="col-sm-3 control-label">
                                                                <?php echo t('Confirm %s', $element->getLabel()) ?>
                                                                <?php if ($element->getPropertyValue('required')) { ?>
                                                                    <span class="required">*</span>
                                                                <?php } ?>
                                                            </label>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <div class="input <?php echo $column->hasElementsWithLabels()?'col-sm-9':'col-sm-12'; ?> <?php echo $element->getPropertyValue('label_hide')?'no_label':'has_label'; ?>">
                                                        <?php echo $element->getConfirm(); ?>
                                                    </div>
                                                <?php } ?>

                                                <?php if ($element->getPropertyValue('tooltip') && !$review) { ?>
                                                    <div class="tooltip" id="<?php echo "tooltip_".$element->getElementID(); ?>">
                                                        <?php echo $element->getPropertyValue('tooltip_value'); ?>
                                                    </div>
                                                <?php } ?>

                                            </div>
                                        <?php
                                            }
                                        }
                                    }
                                    echo ($view_type == 'preview')?$column->getContainerEnd():'';
                                    $i++;
                                ?>
                            </div>
                        <?php } ?>

                    </div>
                    <?php
                }
            } ?>

            <?php if (!$f->hasButtons()) { ?>
                <div class="formidable_row row">
                    <div class="formidable_column col-sm-12">
                        <div class="element form-group form-actions">
                            <div class="col-sm-3"></div>
                            <div id="ff_buttons" class="buttons col-sm-9">
                                <?php echo Core::make('helper/form')->submit('submit', t('Submit'), array(), 'submit btn btn-success'); ?>
                                <div class="please_wait_loader"><img src="<?php echo BASE_URL ?>/packages/formidable_full/images/loader.gif" alt="<?php echo t('Please wait...'); ?>"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </form>
        </div>
    </div>

    <script>
        <?php echo $f->getJavascript(); ?>
        $(function() {
            $('form[id="ff_<?php echo $f->getFormID(); ?>"]').formidable({
                'error_messages_on_top': false,
                'error_messages_on_top_class': 'alert alert-danger',
                'warning_messages_class': 'alert alert-warning',
                'error_messages_beneath_field': true,
                'error_messages_beneath_field_class': 'text-danger error',
                'success_messages_class': 'alert alert-success',
                'remove_form_on_success': true,
                errorCallback: function() { },
                successCallback: function() { }
            });
            <?php echo $f->getJquery(); ?>
        });
    </script>
<?php }
