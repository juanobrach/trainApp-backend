<?php

$color1 = jssupportticket::$_colors['color1'];
$color2 = jssupportticket::$_colors['color2'];
$color3 = jssupportticket::$_colors['color3'];
$color4 = jssupportticket::$_colors['color4'];
$color5 = jssupportticket::$_colors['color5'];
$color6 = jssupportticket::$_colors['color6'];
$color7 = jssupportticket::$_colors['color7'];
$color8 = jssupportticket::$_colors['color8'];
$color9 = jssupportticket::$_colors['color9'];


$jssupportticket_css = '';

/*Code for Css*/
$jssupportticket_css .= '

	form.js-ticket-form{display:inline-block; width: 100%;padding: 10px;}
	div.js-ticket-add-form-wrapper{float: left;width: 100%;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp{float: left;width: calc(100% / 2 - 10px);margin: 0px 5px; margin-bottom: 20px; }
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp.js-ticket-from-field-wrp-full-width{float: left;width: calc(100% / 1 - 10px); margin-bottom: 30px; }
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field-title{float: left;width: 100%;margin-bottom: 5px;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field{float: left;width: 100%;position: relative;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field input.js-ticket-form-field-input{float: left;width: 100%;border-radius: 0px;padding: 10px;line-height: initial;height: 50px;}

	select.js-ticket-select-field{float: left;width: 100%;border-radius: 0px;background: url('.jssupportticket::$_pluginpath.'includes/images/selecticon.png) 96% / 4% no-repeat !important; }
	div.js-ticket-custom-radio-box {width: 20% !important;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field select.js-ticket-form-field-select{float: left;width: 100%;border-radius: 0px;background: url('.jssupportticket::$_pluginpath.'includes/images/selecticon.png) 96% / 4% no-repeat #eee;}
	div.js-ticket-form-btn-wrp{float: left;width:calc(100% - 20px);margin: 0px 10px;text-align: center;padding: 25px 0px 10px 0px;}
	div.js-ticket-form-btn-wrp input.js-ticket-save-button{padding: 20px;margin-right: 10px;min-width: 120px;border-radius: 0px;line-height: initial;font-weight: normal;}
	div.js-ticket-form-btn-wrp a.js-ticket-cancel-button{display: inline-block; padding: 20px;min-width: 120px;border-radius: 0px;line-height: initial;text-decoration: none;}
	div.js-ticket-from-field-wrp div.js-ticket-from-field input.js-form-date-field {float: left;width: 100%;padding: 10px;line-height: initial;height: 60px;}
	div.js-ticket-from-field-wrp div.js-ticket-from-field textarea.js-ticket-custom-textarea {float: left;width: 100%;padding: 10px;line-height: initial;height: 60px;}
	div.js-ticket-from-field-wrp div.js-ticket-from-field select.js-form-input-field {float: left;width: 100%;padding: 10px;line-height: initial;height: 60px;}
	span.js-attachment-file-box {padding: 9px 10px 8px;}
	div.js-ticket-radio-box {width: 20% !important;}
	span#premade{display: inline-block;float: left; width: 80%;position: relative;}
	span#premade select#premadeid{background: url('.jssupportticket::$_pluginpath.'includes/images/selecticon.png) 98% / 2% no-repeat !important;}
	input#duedate{background-image: url('.jssupportticket::$_pluginpath.'includes/images/ticketdetailicon/calender.png);background-repeat: no-repeat;background-position: 97% 14px;}
	span#js-ticket-no-premade{display: inline-block;float: left;width: 100%;position: relative;padding: 10px;height: 60px;}

	div.js-ticket-radio-btn-wrp{float: left;width: 100%;padding: 11px}
	div.js-ticket-radio-btn-wrp input.js-ticket-form-field-radio-btn{margin-right: 5px; vertical-align: middle;width: auto;}
	div.js-ticket-radio-btn-wrp label#forsendmail{margin: 0px;display: inline-block; margin-right: 30px;}

	div.js-ticket-reply-attachments{display: inline-block;width: 100%;margin-bottom: 20px;}
	div.js-ticket-reply-attachments div.js-attachment-field-title{display: inline-block;width: 100%;padding: 15px 0px;}
	div.js-ticket-reply-attachments div.js-attachment-field{display: inline-block;width: 100%;}

	div.tk_attachment_value_wrapperform{float: left;width:100%;padding:0px 0px;}
	div.tk_attachment_value_wrapperform span.tk_attachment_value_text{float: left;width: calc(100% / 3 - 10px);padding: 5px 5px;margin: 5px 5px;position: relative;}
	div.tk_attachment_value_wrapperform span.tk_attachment_value_text input.js-attachment-inputbox{width: 100%;max-width: 100%;max-height:100%;}
	span.tk_attachment_value_text span.tk_attachment_remove{background: url("'.jssupportticket::$_pluginpath.'includes/images/close.png") no-repeat;background-size: 100% 100%;position: absolute;width: 30px;height: 30px;top: 5px;right:7px;cursor: pointer;}
	span.tk_attachments_configform{display: inline-block;float:left;line-height: 25px;margin-top: 10px;width: 100%; font-size: 14px;}
	span.tk_attachments_addform{position: relative;display: inline-block;padding: 8px 10px;cursor: pointer;margin-top: 10px;min-width: 120px;text-align: center;}

	div.js-ticket-assigned-tome{float: left;width: 100%;padding: 11px 10px;}
	div.js-ticket-assigned-tome input#assignedtome1{margin-right: 5px; vertical-align: middle;}
	div.js-ticket-assigned-tome label#forassignedtome{margin: 0px;display: inline-block;}
	label#forassigntome{margin: 0 0 0 2px;display: inline-block;line-height: initial;}

	#records{float: left;width: 100%;padding: 0px 10px;}

	div.js-ticket-select-user-field{float: left;width: 100%;position: relative;}
	div.js-ticket-select-user-field input#username-text{width: 100%;}
	div.js-ticket-select-user-btn{float: left;width: 30%;position: absolute;top: 0;right: 0;}
	div.js-ticket-select-user-btn a#userpopup{display: inline-block;width: 100%;text-align: center;padding: 15px 12px;text-decoration: none;outline: 0px;line-height: initial;height: 50px;}
	div.js-ticket-premade-msg-wrp div.js-ticket-premade-field-wrp select.js-ticket-premade-select{display: inline-block;width: 50%;float: left;border-radius: 0px;background: url('.jssupportticket::$_pluginpath.'includes/images/selecticon.png) 96% / 4% no-repeat #eee;}

	span.js-ticket-apend-radio-btn{display: inline-block;float: left;width: auto;padding: 10px;margin-left: 5px;height: 50px;line-height: initial;}
	span.js-ticket-apend-radio-btn input#append_premade1display{vertical-align: middle;}
	span.help-block{font-size:14px;}
	span.js-ticket-apend-radio-btn{display: inline-block;float: left;width: auto;padding:10px;margin-left: 5px;}
	input#append1{vertical-align: middle;}
	label#forappend{display: inline-block;margin: 0px 0 0 2px;line-height: initial;}


	div#userpopupblack{background: rgba(0,0,0,0.7);position: fixed;width: 100%;height: 100%;top:0px;left:0px;z-index: 9989;}
	div.js-ticket-popup-row{float: left;width: 100%;margin: 0;}

	form#userpopupsearch{margin-bottom: 10px;float: left;width: 100%;}
	form#userpopupsearch{margin-bottom: 0px;}
	form#userpopupsearch div.search-center div.js-search-value{padding: 0 5px;}
	form#userpopupsearch div.search-center div.js-search-value input{min-height: 28px;}
	form#userpopupsearch div.search-center div.js-search-value-button{padding: 0;}
	form#userpopupsearch div.search-center div.js-search-value-button div.js-button{padding: 0 5px; width: 50%; float: left; display: inline-block;}
	form#userpopupsearch div.search-center{width:99%;margin-left:4px;font-size:15px;float:left;font-weight: bold;}
	form#userpopupsearch div.search-center-history{width:100%;font-size:17px;float:left;padding: 20px 10px; font-weight: bold;}
	form#userpopupsearch div.search-center input{width: 100% !important;padding: 17px 15px;}
	form#userpopupsearch div.search-center-heading{padding:10px 0px 10px 10px;margin-bottom: 10px;}
	form#userpopupsearch div.search-center span.close{position: absolute;top:10px;right: 10px;background-image:url('.jssupportticket::$_pluginpath.'includes/images/ticketdetailicon/popup-close.png);background-size: 100%;width:20px;height: 20px;opacity: 1;}
	form#userpopupsearch div.search-center-history span.close-history{position: absolute;top: 22px;right: 16px;background:url('.jssupportticket::$_pluginpath.'includes/images/ticketdetailicon/popup-close.png) no-repeat;background-size: 100%;width:20px;height: 20px;cursor: pointer;}

	div#userpopup{position: fixed;top:50%;left:50%;width:60%; max-height: 70%; padding-top:0px;z-index: 99999;overflow-y: auto; overflow-x: hidden;transform: translate(-50%,-50%);}
	div#userpopupblack{background: rgba(0,0,0,0.7);position: fixed;width: 100%;height: 100%;top:0px;left:0px;z-index: 9989;}

	div.jsst-popup-header{width:100%;font-size:20px;float:left;padding: 20px 10px; font-weight: bold;line-height: initial;}
	div.popup-header-close-img{position: absolute;top:22px;right: 22px;background-image:url('.jssupportticket::$_pluginpath.'includes/images/close-icon-white.png);background-size: 100%;width:20px;height: 20px;opacity: 1;cursor: pointer;}

	div.js-ticket-popup-search-wrp{float: left;width: 100%;padding: 30px 5px 15px;}
	div.js-ticket-search-top{float: left;width: 100%;}
	div.js-ticket-search-top div.js-ticket-search-left{float: left;width: 70%;}
	div.js-ticket-search-top div.js-ticket-search-left div.js-ticket-search-fields-wrp{float: left;width: 100%;padding: 0px}
	div.js-ticket-search-top div.js-ticket-search-left div.js-ticket-search-fields-wrp input.js-ticket-search-input-fields{float: left;width: calc(100% / 3 - 10px);margin:0px 5px;padding: 10px;border-radius: 0px;line-height: initial;height: 50px;}
	div.js-ticket-search-top div.js-ticket-search-right{float: left;width: 30%;}
	div.js-ticket-search-top div.js-ticket-search-right div.js-ticket-search-btn-wrp{float: left;width: 100%;}
	div.js-ticket-search-top div.js-ticket-search-right div.js-ticket-search-btn-wrp input.js-ticket-search-btn{width: calc(100% / 2 - 5px);padding: 13px 0;border-radius: 0px;line-height: initial;height: 50px;float: left;margin-right: 5px;}
	div.js-ticket-search-top div.js-ticket-search-right div.js-ticket-search-btn-wrp input.js-ticket-reset-btn{width: calc(100% / 2 - 5px);padding: 13px 0;border-radius: 0px;line-height: initial;height: 50px;float: left;}

	div.js-ticket-table-wrp{float: left;width: 100%;padding: 0;}
	div.js-ticket-table-wrp div.js-ticket-table-header{float: left;width: 100%;}
	div.js-ticket-table-wrp div.js-ticket-table-header div.js-ticket-table-header-col{padding: 15px;text-align: left;width: 25%;float: left;line-height: initial;}
	div.js-ticket-table-wrp div.js-ticket-table-header div.js-ticket-table-header-col:first-child{width: 10%;}
	div.js-ticket-table-wrp div.js-ticket-table-header div.js-ticket-table-header-col:nth-child(3){width: 40%;}
	div.js-ticket-table-body{float: left;width: 100%;}
	div.js-ticket-table-body div.js-ticket-data-row{float: left;width: 100%;}
	div.js-ticket-table-body div.js-ticket-data-row div.js-ticket-table-body-col{padding: 15px;text-align: left;float: left;width: 25%;line-height: initial;}
	div.js-ticket-table-body div.js-ticket-data-row div.js-ticket-table-body-col a.js-userpopup-link {display: inline-block;text-decoration: none;}
	div.js-ticket-table-body div.js-ticket-data-row div.js-ticket-table-body-col:first-child{width: 10%;}
	div.js-ticket-table-body div.js-ticket-data-row div.js-ticket-table-body-col:nth-child(3){width: 40%;}


	div#records div.jsst_userpages{text-align: right;padding:5px; margin: 10px 5px;width: calc(100% - 10px);float:left;}
	div#records div.jsst_userpages a.jsst_userlink{display: inline-block;padding:5px 15px;margin-left:5px;text-decoration: none;background:rgba(0, 0, 0, 0.05) none repeat scroll 0 0;line-height: initial;}
	div#records div.jsst_userpages span.jsst_userlink{display: inline-block;padding:5px 15px;margin-left:5px;line-height: initial;}
	span.js-ticket-display-block{display: none;}

	span.help-block{color:red !important;bottom: -30px;font-size: 13px;}
	select ::-ms-expand {display:none !important;}
	select{-webkit-appearance:none !important;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field input.js-ticket-form-field-input.loading {background-image: url("'. jssupportticket::$_pluginpath.'includes/images/spinning-wheel.gif");background-size: 25px 25px;background-position:right center;background-repeat: no-repeat;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field span.jsst_product_found{background-image: url("'. jssupportticket::$_pluginpath.'includes/images/good.png");background-size: 25px 25px;background-position:right center;background-repeat: no-repeat;width:30px;height:30px;top:10px;right:10px;position:absolute;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field span.jsst_product_not_found{background-image: url("'. jssupportticket::$_pluginpath.'includes/images/close.png");background-size: 25px 25px;background-position:right center;background-repeat: no-repeat;width:30px;height:30px;top:10px;right:10px;position:absolute;}

	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field span#premade div.js-form-perm-msg{float: left;margin: 0 8px 8px 0;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field span#premade div.js-form-perm-msg a{display: inline-block;width: 100%;text-decoration: none;padding: 10px 12px;border: 1px solid #ebecec;background: #fff;color: #575455;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field span#premade div.js-form-perm-msg a:hover{border-color: #1572e8;color: #1572e8;}

';
/*Code For Colors*/
$jssupportticket_css .= '
/* Add Form */
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field-title {color: '.$color2.';}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field input.js-ticket-form-field-input{background-color:#fff;border:1px solid '.$color5.';color: '.$color4.';}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field select#categoryid{background-color:'.$color3.';border:1px solid '.$color5.';}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field select.js-ticket-select-field{background-color:#fff !important;border:1px solid '.$color5.';color: '.$color4.';}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field select.js-ticket-form-field-select{background-color:'.$color3.' !important;border:1px solid '.$color5.';}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field select#status{background-color:'.$color3.' !important;border:1px solid '.$color5.';}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field select#parentid{background-color:'.$color3.' !important;border:1px solid '.$color5.';}
	span.js-ticket-sub-fields{background-color:'.$color3.' !important;border:1px solid '.$color5.';}
	.js-userpopup-link{color:'.$color2.';}
	div.js-ticket-form-btn-wrp{border-top:2px solid '.$color2.';}
	div.js-ticket-reply-attachments div.js-attachment-field-title{border:'.$color2.';}
	div.js-ticket-form-btn-wrp input.js-ticket-save-button{background-color:'.$color1.';color:'.$color7.';border: 1px solid '.$color5.';}
	div.js-ticket-form-btn-wrp input.js-ticket-save-button:hover{border-color: '.$color2.';}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field select {border: 1px solid '.$color5.';color: '.$color4.';background: '.$color7.';}
	div.js-ticket-form-btn-wrp a.js-ticket-cancel-button{background: '.$color2.';color:'.$color7.';border: 1px solid '.$color5.';}
	div.js-ticket-form-btn-wrp a.js-ticket-cancel-button:hover{border-color:'.$color1.';}
	a.js-ticket-delete-attachment{background-color:#ed3237;color:'.$color7.';}
	div.js-ticket-radio-btn-wrp{background-color:'.$color3.';border:1px solid '.$color5.';}
	span.tk_attachments_addform{background-color:'.$color1.';color:'.$color7.';border: 1px solid '.$color5.';}
	span.tk_attachments_addform:hover{border-color: '.$color2.';}
	span.js-ticket-apend-radio-btn{border:1px solid '.$color5.';background-color: #fff;color: '.$color4.';}
	div.tk_attachment_value_wrapperform{border: 1px solid '.$color5.';background: #fff;}
	span.tk_attachment_value_text{border: 1px solid '.$color5.';background-color:'.$color7.';}
	div.js-ticket-assigned-tome{border:1px solid '.$color5.';background: #fff;}
	span.help-block{color:red;}
	span.tk_attachments_configform {color: '.$color4.';}
	div#userpopup{background: '.$color7.';}
	div.jsst-popup-header{background: '.$color1.';color:'.$color7.';}
	div.jsst-popup-wrapper{background-color:'.$color7.';}
	div.js-ticket-search-top div.js-ticket-search-left div.js-ticket-search-fields-wrp input.js-ticket-search-input-fields{border:1px solid '.$color5.';background-color:#fff;color:'.$color4.';}
	div.js-ticket-search-top div.js-ticket-search-right div.js-ticket-search-btn-wrp input.js-ticket-search-btn{background: '.$color1.';color:'.$color7.';border:1px solid '.$color5.';}
	div.js-ticket-search-top div.js-ticket-search-right div.js-ticket-search-btn-wrp input.js-ticket-search-btn:hover{border-color:'.$color2.';}
	div.js-ticket-search-top div.js-ticket-search-right div.js-ticket-search-btn-wrp input.js-ticket-reset-btn{background: '.$color2.';color:'.$color7.';border:1px solid '.$color5.';}
	div.js-ticket-search-top div.js-ticket-search-right div.js-ticket-search-btn-wrp input.js-ticket-reset-btn:hover{border-color:'.$color1.';}

	div.js-ticket-table-header{background-color:#ecf0f5;border: 0;border-bottom:1px solid '.$color5.';}
	div.js-ticket-table-header div.js-ticket-table-header-col{}
	div.js-ticket-table-header div.js-ticket-table-header-col:last-child{}
	div.js-ticket-table-body div.js-ticket-data-row{border: 0;border-bottom:1px solid '.$color5.';}
	div.js-ticket-table-body div.js-ticket-data-row div.js-ticket-table-body-col{}
	div.js-ticket-table-body div.js-ticket-data-row div.js-ticket-table-body-col:last-child{}
	th.js-ticket-table-th{border-right:1px solid '.$color5.';}
	tbody.js-ticket-table-tbody{border:1px solid '.$color5.';}
	td.js-ticket-table-td{border-right:1px solid '.$color5.';}
	div.js-ticket-select-user-btn a#userpopup{background-color:'.$color1.';color:'.$color7.';border: 1px solid '.$color5.';}
	div.js-ticket-select-user-btn a#userpopup:hover{border-color: '.$color2.';}
	span#premade select#premadeid {background-color: #fff !important;}



/* Add Form */

';


wp_add_inline_style('jssupportticket-main-css',$jssupportticket_css);


?>
