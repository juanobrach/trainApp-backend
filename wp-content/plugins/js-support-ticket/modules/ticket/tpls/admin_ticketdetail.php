<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
JSSTmessage::getMessage();
wp_enqueue_script('file_validate.js', jssupportticket::$_pluginpath . 'includes/js/file_validate.js');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_style('jquery-ui-css', $protocol.'ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
wp_enqueue_script('timer.js', jssupportticket::$_pluginpath . 'includes/js/timer.jquery.js');
?>
<script type="text/javascript">
    var timer_flag = 0;
            var seconds = 0;
    function checktinymcebyid(id) {
        var content = tinymce.get(id).getContent({format: 'text'});
        if (jQuery.trim(content) == '')
        {
            alert('<?php echo __('Some values are not acceptable please retry', 'js-support-ticket'); ?>');
            return false;
        }
        return true;
    }
    function getpremade(val) {
        jQuery.post(ajaxurl, {action: 'jsticket_ajax', val: val, jstmod: 'cannedresponses', task: 'getpremadeajax'}, function (data) {
            if (data) {
                var append = jQuery('input#append_premade1:checked').length;
                if (append == 1) {
                    var content = tinyMCE.get('jsticket_message').getContent();
                    content = content + data;
                    tinyMCE.get('jsticket_message').execCommand('mceSetContent', false, content);

                } else {
                    tinyMCE.get('jsticket_message').execCommand('mceSetContent', false, data);
                }

            }
        });
    }
    jQuery(document).ready(function ($) {
        jQuery( "form" ).submit(function(e) {
            if(timer_flag != 0){
                jQuery('input#timer_time_in_seconds').val(jQuery('div.timer').data('seconds'));
            }
        });
        jQuery("#tabs").tabs();
        jQuery("#tk_attachment_add").click(function () {
            var obj = this;
            var current_files = jQuery('input[type="file"]').length;
            var total_allow =<?php echo jssupportticket::$_config['no_of_attachement']; ?>;
            var append_text = "<span class='tk_attachment_value_text'><input name='filename[]' type='file' onchange=\"uploadfile(this,'<?php echo jssupportticket::$_config['file_maximum_size']; ?>','<?php echo jssupportticket::$_config['file_extension']; ?>');\" size='20' maxlenght='30'  /><span  class='tk_attachment_remove'></span></span>";
            if (current_files < total_allow) {
                jQuery(".tk_attachment_value_wrapperform").append(append_text);
            } else if ((current_files === total_allow) || (current_files > total_allow)) {
                alert('<?php echo __('File upload limit exceeds', 'js-support-ticket'); ?>');
                obj.hide();
            }
        });
        jQuery(document).delegate(".tk_attachment_remove", "click", function (e) {
            jQuery(this).parent().remove();
            var current_files = jQuery('input[type="file"]').length;
            var total_allow =<?php echo jssupportticket::$_config['no_of_attachement']; ?>;
            if (current_files < total_allow) {
                jQuery("#tk_attachment_add").show();
            }
        });
        jQuery("a#showhidedetail").click(function (e) {
            e.preventDefault();
            var divid = jQuery(this).attr('data-divid');
            jQuery("div#" + divid).slideToggle();
            jQuery(this).find('img').toggleClass('js-hidedetail');
        });

        var height = jQuery(window).height();
        jQuery("a#showhistory").click(function (e) {
            e.preventDefault();
            jQuery("div#userpopup").slideDown('slow');
            jQuery('div#userpopupblack').show();
        });
        jQuery("a#int-note").click(function (e) {
            e.preventDefault();
            jQuery("div#internalnotes-popup").slideDown('slow');
            jQuery('div#userpopupblack').show();
        });
        jQuery(".userpopup-close, div#userpopupblack").click(function (e) {
            jQuery("div#internalnotes-popup").slideUp('slow', function () {
                jQuery("div#userpopupblack").hide();
            });

        });
        jQuery("a#chng-prority").click(function (e) {
            e.preventDefault();
            jQuery("div#changepriority-popup").slideDown('slow');
            jQuery('div#userpopupblack').show();
        });
        jQuery(".userpopup-close, div#userpopupblack").click(function (e) {
            jQuery("div#changepriority-popup").slideUp('slow', function () {
                jQuery("div#userpopupblack").hide();
            });

        });
        jQuery("a#chng-dept").click(function (e) {
            e.preventDefault();
            jQuery("div#changedept-popup").slideDown('slow');
            jQuery('div#userpopupblack').show();
        });
        jQuery(".userpopup-close, div#userpopupblack").click(function (e) {
            jQuery("div#changedept-popup").slideUp('slow', function () {
                jQuery("div#userpopupblack").hide();
            });

        });
        jQuery("a#asgn-staff").click(function (e) {
            e.preventDefault();
            jQuery("div#assignstaff-popup").slideDown('slow');
            jQuery('div#userpopupblack').show();
        });
        jQuery(".userpopup-close, div#userpopupblack").click(function (e) {
            jQuery("div#assignstaff-popup").slideUp('slow', function () {
                jQuery("div#userpopupblack").hide();
            });

        });
        jQuery(document).delegate(".close-merge", "click", function (e) {
            jQuery("div#mergeticketselection").fadeOut();
            jQuery("div#popup-record-data").html("");
        });

        jQuery("div#userpopupblack,div.jsst-popup-background,.close-history,.close-credentails").click(function (e) {
            jQuery("div#userpopup").slideUp('slow');
            jQuery("#usercredentailspopup").slideUp('slow');
            setTimeout(function () {
                jQuery('div#userpopupblack').hide();
                jQuery('div.jsst-popup-background').hide();
            }, 700);
        });

        //print code
        <?php
        if(isset(jssupportticket::$_data[0])){ ?>
            jQuery('a#print-link').click(function (e) {
                e.preventDefault();
                var href = '<?php echo jssupportticket::makeUrl(array('jstmod'=>'ticket','jstlay'=>'printticket','jssupportticketid'=>jssupportticket::$_data[0]->id,'jsstpageid'=>jssupportticket::getPageid())); ?>';
                print = window.open(href, 'print_win', 'width=1024, height=800, scrollbars=yes');
            });
        <?php } ?>
        jQuery(document).delegate("#ticketpopupsearch",'submit', function (e) {
            var ticketid = jQuery("#ticketidformerge").val();
            e.preventDefault();
            var name = jQuery("input#name").val();
            var email = jQuery("input#email").val();
            jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'mergeticket', task: 'getTicketsForMerging', name: name, email: email,ticketid:ticketid}, function (data) {
                data=jQuery.parseJSON(data);
               if(data !== 'undefined' && data !== '') {
                    jQuery("div#popup-record-data").html("");
                    jQuery("div#popup-record-data").html(data['data']);
                }else{
                    jQuery("div#popup-record-data").html("");
                }
            });//jquery closed
        });

        jQuery(document).delegate("#ticketidcopybtn", "click", function(){
            var temp = jQuery("<input>");
            jQuery("body").append(temp);
            temp.val(jQuery("#ticketrandomid").val()).select();
            document.execCommand("copy");
            temp.remove();
            jQuery("#ticketidcopybtn").text(jQuery("#ticketidcopybtn").attr('success'));
        });

        //non premium support function
        jQuery("#nonpreminumsupport").change(function(){
            if(jQuery(this).is(':checked')){
                if(1 || confirm("<?php echo __('Are you sure to mark this ticket non-premium?','js-support-ticket'); ?>")){
                    markUnmarkTicketNonPremium(1);
                }else{
                    jQuery(this).removeAttr('checked');
                }
            }else{
                markUnmarkTicketNonPremium(0);
            }
        });

        jQuery("#paidsupportlinkticketbtn").click(function(){
            var ticketid = jQuery("#ticketid").val();
            var paidsupportitemid = jQuery("#paidsupportitemid").val();
            if(paidsupportitemid > 0){
                jQuery.post(ajaxurl, {action: 'jsticket_ajax',jstmod: 'paidsupport', task: 'linkTicketPaidSupportAjax', ticketid: ticketid, paidsupportitemid:paidsupportitemid }, function (data) {
                    window.location.reload();
                });
            }
        });

    });

    function markUnmarkTicketNonPremium(mark){
        var ticketid = jQuery("#ticketid").val();
        var paidsupportitemid = jQuery("#paidsupportitemid").val();
        jQuery.post(ajaxurl, {action: 'jsticket_ajax',jstmod: 'paidsupport', task: 'markUnmarkTicketNonPremiumAjax', status: mark, ticketid: ticketid, paidsupportitemid:paidsupportitemid }, function (data) {
            window.location.reload();
        });
    }

    function actionticket(action) {
        /*  Action meaning
         * 1 -> Change Priority
         * 2 -> Close Ticket
         */
        if(action == 1){
            jQuery("#priority").val(jQuery("#prioritytemp").val());
        }
        jQuery("input#actionid").val(action);
        jQuery("form#adminTicketform").submit();
    }
    function getmergeticketid(mergeticketid, mergewithticketid){
                if(mergewithticketid == 0){
                    mergewithticketid =  jQuery("#mergeticketid").val();
                }else{
                    jQuery("#mergeticketid").val(mergewithticketid);
                }
                if(mergeticketid == mergewithticketid){
                    alert("Primary id must be differ from merge ticket id");
                    return false;
                }
                jQuery("#mergeticketselection").hide();
                getTicketdataForMerging(mergeticketid,mergewithticketid);
            }

    function getTicketdataForMerging(mergeticketid,mergewithticketid){
        jQuery.post(ajaxurl, {action: 'jsticket_ajax',jstmod: 'mergeticket', task: 'getLatestReplyForMerging', mergeid:mergeticketid,mergewith:mergewithticketid,isadmin:1}, function (data) {
            if(data){
                data=jQuery.parseJSON(data);
                jQuery("div#popup-record-data").html("");
                jQuery("div#popup-record-data").html(data['data']);
            }
        });
    }

    function closePopup(){
        setTimeout(function () {
            jQuery('div.jsst-popup-background').hide();
            jQuery('div#userpopupblack').hide();
            }, 700);

        jQuery('div.jsst-popup-wrapper').slideUp('slow');
        jQuery('div#userpopupforchangepriority').slideUp('slow');
        jQuery('div#userpopup').slideUp('slow');


    }
    function updateticketlist(pagenum,ticketid){
        jQuery.post(ajaxurl, {action: 'jsticket_ajax',jstmod: 'mergeticket', task: 'getTicketsForMerging', ticketid:ticketid,ticketlimit:pagenum}, function (data) {
        if(data){
            console.log(data);
            data=jQuery.parseJSON(data);
                jQuery("div#popup-record-data").html("");
                jQuery("div#popup-record-data").html(data['data']);
            }
        });
    }

    function showPopupAndFillValues(id,pfor) {
        if(pfor == 1){
            jQuery.post(ajaxurl, {action: 'jsticket_ajax', val: id, jstmod: 'reply', task: 'getReplyDataByID'}, function (data) {
                if (data) {
                    d = jQuery.parseJSON(data);
                    tinyMCE.get('jsticket_replytext').execCommand('mceSetContent', false, d.message);
                    jQuery('div.jsst-merge-popup-wrapper div.userpopup-heading').html("<?php echo __("Edit Reply","js-support-ticket");?>");
                    jQuery('form#jsst-time-edit-form').hide();
                    jQuery('form#jsst-note-edit-form').hide();
                    jQuery('div.edit-time-popup').hide();
                    jQuery('form#jsst-reply-form').show();
                    jQuery('input#reply-replyid').val(id);
                    jQuery('div.jsst-popup-background').show();
                    jQuery('div.jsst-merge-popup-wrapper').slideDown('slow');
                }
            });
        }else if(pfor == 2){
            jQuery.post(ajaxurl, {action: 'jsticket_ajax', val: id, jstmod: 'timetracking', task: 'getTimeByReplyID'}, function (data) {
                if (data) {
                    d = jQuery.parseJSON(data);
                    jQuery('div.jsst-merge-popup-wrapper div.userpopup-heading').html("<?php echo __("Edit Time","js-support-ticket");?>");
                    jQuery('form#jsst-reply-form').hide();
                    jQuery('form#jsst-note-edit-form').hide();
                    jQuery('div.system-time-div').hide();
                    jQuery('div.edit-time-popup').hide();
                    jQuery('form#jsst-time-edit-form').show();
                    jQuery('input#reply-replyid').val(id);
                    jQuery('div.jsst-popup-background').show();
                    jQuery('div.jsst-merge-popup-wrapper').slideDown('slow');
                    jQuery('input#edited_time').val(d.time);
                    jQuery('textarea#edit_reason').text(d.desc);
                    if(d.conflict == 1){
                        jQuery('div.system-time-div').show();
                        jQuery('input#time-confilct').val(d.conflict);
                        jQuery('input#systemtime').val(d.systemtime);
                        jQuery('select#time-confilct-combo').val(0);
                    }
                }
            });
        }else if(pfor == 3){
            jQuery.post(ajaxurl, {action: 'jsticket_ajax', val: id, jstmod: 'note', task: 'getTimeByNoteID'}, function (data) {
                if (data) {
                    d = jQuery.parseJSON(data);
                    jQuery('div.jsst-merge-popup-wrapper div.userpopup-heading').html("<?php echo __("Edit Time","js-support-ticket");?>");
                    jQuery('form#jsst-reply-form').hide();
                    jQuery('form#jsst-note-edit-form').show();
                    jQuery('form#jsst-time-edit-form').hide();
                    jQuery('div.system-time-div').hide();
                    jQuery('div.edit-time-popup').hide();
                    jQuery('input#note-noteid').val(id);
                    jQuery('div.jsst-popup-background').show();
                    jQuery('div.jsst-merge-popup-wrapper').slideDown('slow');
                    jQuery('input#edited_time').val(d.time);
                    jQuery('textarea#edit_reason').text(d.desc);
                    if(d.conflict == 1){
                        jQuery('div.system-time-div').show();
                        jQuery('input#time-confilct').val(d.conflict);
                        jQuery('input#systemtime').val(d.systemtime);
                        jQuery('select#time-confilct-combo').val(0);
                    }
                }
            });
        }else if(pfor == 4){
            jQuery.post(ajaxurl, {action: 'jsticket_ajax', ticketid: id, jstmod: 'mergeticket', task: 'getTicketsForMerging'}, function (data) {
                if (data) {
                    data=jQuery.parseJSON(data);
                    jQuery('div.jsst-merge-popup-wrapper div.userpopup-heading').html("<?php echo __("Merge Ticket","js-support-ticket");?>");
                    jQuery("div#popup-record-data").html("");
                    jQuery("div#popup-record-data").html(data['data']);

                }
            });
        }

         return false;
    }

    function changeTimerStatus(val) {
        if(timer_flag == 2){// to handle stopped timer
                return;
        }
        if(!jQuery('span.timer-button.cls_'+val).hasClass('selected')){
            jQuery('span.timer-button').removeClass('selected');
            jQuery('span.timer-button.cls_'+val).addClass('selected');
            if(val == 1){
                if(timer_flag == 0){
                    jQuery('div.timer').timer({format: '%H:%M:%S'});
                }
                timer_flag = 1;
                jQuery('div.timer').timer('resume');
            }else if(val == 2) {
                 jQuery('div.timer').timer('pause');
            }else{
                 jQuery('div.timer').timer('remove');
                timer_flag = 2;
            }
        }
    }

    function showEditTimerPopup(){
        jQuery('form#jsst-time-edit-form').hide();
        jQuery('form#jsst-reply-form').hide();
        jQuery('form#jsst-note-edit-form').hide();
        jQuery('div.edit-time-popup').show();
        jQuery('span.timer-button').removeClass('selected');
        if(timer_flag != 0){
            jQuery('div.timer').timer('pause');
        }
        ex_val = jQuery('div.timer').html();
        jQuery('input#edited_time').val('');
        jQuery('input#edited_time').val(ex_val.trim());
        jQuery('div.jsst-popup-background').show();
        jQuery('div.jsst-merge-popup-wrapper').slideDown('slow');
        jQuery('div.jsst-merge-popup-wrapper div.userpopup-heading').html("<?php echo __("Edit Time","js-support-ticket");?>");
    }

    function updateTimerFromPopup(){
        val = jQuery('input#edited_time').val();
        arr = val.split(':', 3);
        jQuery('div.timer').html(val);
        jQuery('div.jsst-popup-background').hide();
        jQuery('div.jsst-popup-wrapper').slideUp('slow');
        seconds = parseInt(arr[0])*3600 + parseInt(arr[1])*60 + parseInt(arr[2]);
        if(seconds < 0){
            seconds = 0;
        }
        jQuery('div.timer').timer('remove');
        jQuery('div.timer').timer({
            format: '%H:%M:%S',
            seconds: seconds,
        });
        jQuery('div.timer').timer('pause');
        timer_flag = 1;
        desc = jQuery('textarea#t_desc').val();
        jQuery('input#timer_edit_desc').val(desc);
    }

    jQuery("div.popup-header-close-img,div.jsst-popup-background,input#cancel").click(function (e) {
        jQuery("div.jsst-popup-wrapper").slideUp('slow');
        jQuery("div.jsst-merge-popup-wrapper").slideUp('slow');
        setTimeout(function () {
            jQuery('div.jsst-popup-background').hide();
        }, 700);
    });

    // smooth scroll
    jQuery(document).ready(function(){
        jQuery('a.smooth-scroll').on("click", function(e) {
            e.preventDefault();
            var anchor = jQuery(this);
            jQuery('html, body').stop().animate({
                scrollTop: jQuery(anchor.attr('href')).offset().top - 10
            }, 1000);
        });
    })

</script>
<?php
$yesno = array(
    (object) array('id' => '1', 'text' => __('Yes', 'js-support-ticket')),
    (object) array('id' => '0', 'text' => __('No', 'js-support-ticket'))
);
?>
<span style="display:none" id="filesize"><?php echo __('Error file size too large', 'js-support-ticket'); ?></span>
<span style="display:none" id="fileext"><?php echo __('The uploaded file extension not valid', 'js-support-ticket'); ?></span>
<div class="jsst-popup-background" style="display:none" ></div>
<div id="popup-record-data" style="display:inline-block;width:100%;"></div>
<div id="userpopup" class="jsst-popup-wrapper jsst-merge-popup-wrapper" style="display:none" >
    <div class="userpopup-top" >
        <div class="userpopup-heading" >
            <?php echo __('Edit Reply','js-support-ticket')?>
        </div>
        <img alt="<?php echo __('Close','js-support-ticket'); ?>" class="close-history userpopup-close" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/close-icon-white.png" />
    </div>
    <div class="js-admin-popup-cnt">
    <div class="edit-time-popup" style="display:none;" >
        <div class="js-ticket-edit-form-wrp">
            <div class="js-ticket-edit-form-row">
                <div class="js-ticket-edit-field-title">
                    <?php echo __('Time', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span>
                </div>
                <div class="js-ticket-edit-field-wrp">
                    <?php echo JSSTformfield::text('edited_time', '', array('class' => 'inputbox js-ticket-edit-field-input')) ?>
                </div>
            </div>
            <div class="js-ticket-edit-form-row">
                <div class="js-ticket-edit-field-title">
                    <?php echo __('Reason For Editing the timer', 'js-support-ticket'); ?>
                </div>
                <div class="js-ticket-edit-field-wrp">
                    <?php echo JSSTformfield::textarea('t_desc', '', array('class' => 'inputbox')); ?>
                </div>
            </div>
            <div class="js-ticket-priorty-btn-wrp">
                <?php echo JSSTformfield::submitbutton('ok', __('Save', 'js-support-ticket'), array('class' => 'js-ticket-priorty-save','onclick' => 'updateTimerFromPopup();')); ?>
                <?php echo JSSTformfield::button('cancel', __('Cancel', 'js-support-ticket'), array('class' => 'js-ticket-priorty-cancel','onclick'=>'closePopup();')); ?>
            </div>
        </div>
    </div>
    <form id="jsst-reply-form" style="display:none" method="post" action="<?php echo admin_url("admin.php?page=reply&task=saveeditedreply&action=jstask"); ?>" >
        <div class="js-form-wrapper-popup">
            <div class="js-form-title-popup"><?php echo __('Reply', 'js-support-ticket'); ?></div>
            <div class="js-form-field-popup"><?php echo wp_editor('', 'jsticket_replytext', array('media_buttons' => false,'editor_height' => 200, 'textarea_rows' => 20,)); ?></div>
        </div>
        <div class="js-col-md-12 js-form-button-wrapper">
            <?php echo JSSTformfield::submitbutton('ok', __('Save', 'js-support-ticket'), array('class' => 'button')); ?>
            <?php echo JSSTformfield::button('cancel', __('Cancel', 'js-support-ticket'), array('class' => 'button', 'onclick'=>'closePopup();')); ?>
        </div>
        <?php echo JSSTformfield::hidden('reply-replyid', ''); ?>

        <?php
        if(isset(jssupportticket::$_data[0])){
            echo JSSTformfield::hidden('reply-tikcetid',jssupportticket::$_data[0]->id);
        } ?>
    </form>
    <?php
    if(in_array('timetracking', jssupportticket::$_active_addons)){ ?>
        <form id="jsst-time-edit-form" style="display:none" method="post" action="<?php echo admin_url("admin.php?page=reply&task=saveeditedtime&action=jstask"); ?>" >
            <div class="js-form-wrapper-popup">
                <div class="js-form-title-popup"><?php echo __('Time', 'js-support-ticket'); ?></div>
                <div class="js-form-field-popup"><?php echo JSSTformfield::text('edited_time', '', array('class' => 'inputbox')) ?></div>
            </div>
            <div class="js-form-wrapper-popup system-time-div" style="display:none;" >
                <div class="js-form-title-popup"><?php echo __('System Time', 'js-support-ticket'); ?></div>
                <div class="js-form-field-popup"><?php echo JSSTformfield::text('systemtime', '', array('class' => 'inputbox','disabled'=>'disabled')) ?></div>
            </div>
            <div class="js-form-wrapper-popup">
                <div class="js-form-title-popup"><?php echo __('Reason For Editing', 'js-support-ticket'); ?></div>
                <div class="js-form-field-popup"><?php echo JSSTformfield::textarea('edit_reason', '', array('class' => 'inputbox')) ?></div>
            </div>
            <div class="js-form-wrapper-popup system-time-div" style="display:none;" >
                <div class="js-form-title-popup"><?php echo __('Resolve conflict', 'js-support-ticket'); ?></div>
                <div class="js-form-field-popup"><?php echo JSSTformfield::select('time-confilct-combo', $yesno, ''); ?></div>
            </div>
            <div class="js-col-md-12 js-form-button-wrapper">
                <?php echo JSSTformfield::submitbutton('ok', __('Save', 'js-support-ticket'), array('class' => 'button')); ?>
                <?php echo JSSTformfield::button('cancel', __('Cancel', 'js-support-ticket'), array('class' => 'button', 'onclick'=>'closePopup();')); ?>
            </div>
            <?php echo JSSTformfield::hidden('reply-replyid', ''); ?>
            <?php echo JSSTformfield::hidden('reply-tikcetid',jssupportticket::$_data[0]->id); ?>
            <?php echo JSSTformfield::hidden('time-confilct',''); ?>
        </form>
        <?php if(in_array('note', jssupportticket::$_active_addons) && in_array('timetracking', jssupportticket::$_active_addons)){ ?>
        <form id="jsst-note-edit-form" style="display:none" method="post" action="<?php echo admin_url("admin.php?page=note&task=saveeditedtime&action=jstask"); ?>" >
            <div class="js-form-wrapper-popup">
                <div class="js-form-title-popup"><?php echo __('Time', 'js-support-ticket'); ?></div>
                <div class="js-form-field-popup"><?php echo JSSTformfield::text('edited_time', '', array('class' => 'inputbox')) ?></div>
            </div>
            <div class="js-form-wrapper-popup system-time-div" style="display:none;" >
                <div class="js-form-title-popup"><?php echo __('System Time', 'js-support-ticket'); ?></div>
                <div class="js-form-field-popup"><?php echo JSSTformfield::text('systemtime', '', array('class' => 'inputbox','disabled'=>'disabled')) ?></div>
            </div>
            <div class="js-form-wrapper-popup">
                <div class="js-form-title-popup"><?php echo __('Reason For Editing', 'js-support-ticket'); ?></div>
                <div class="js-form-field-popup"><?php echo JSSTformfield::textarea('edit_reason', '', array('class' => 'inputbox')) ?></div>
            </div>
            <div class="js-form-wrapper-popup system-time-div" style="display:none;" >
                <div class="js-form-title-popup"><?php echo __('Resolve conflict', 'js-support-ticket'); ?></div>
                <div class="js-form-field-popup"><?php echo JSSTformfield::select('time-confilct-combo', $yesno, ''); ?></div>
            </div>
            <div class="js-col-md-12 js-form-button-wrapper">
                <?php echo JSSTformfield::submitbutton('ok', __('Save', 'js-support-ticket'), array('class' => 'button')); ?>
                <?php echo JSSTformfield::button('cancel', __('Cancel', 'js-support-ticket'), array('class' => 'button', 'onclick'=>'closePopup();')); ?>
            </div>
            <?php echo JSSTformfield::hidden('note-noteid', ''); ?>
            <?php echo JSSTformfield::hidden('note-tikcetid',jssupportticket::$_data[0]->id); ?>
            <?php echo JSSTformfield::hidden('time-confilct',''); ?>
        </form>
    <?php } ?>
<?php }?>
    </div>
</div>
<div id="jsstadmin-wrapper">
    <div id="jsstadmin-leftmenu">
        <?php
        if(current_user_can('jsst_support_ticket')){
            JSSTincluder::getClassesInclude('jsstadminsidemenu');
        }
        ?>
    </div>
    <div id="jsstadmin-data">
        <div id="jsstadmin-wrapper-top">
            <div id="jsstadmin-wrapper-top-left">
                <div id="jsstadmin-breadcrunbs">
                    <ul>
                        <li><a href="?page=jssupportticket" title="<?php echo __('Dashboard','js-support-ticket'); ?>"><?php echo __('Dashboard','js-support-ticket'); ?></a></li>
                        <li><?php echo __('Ticket Detail','js-support-ticket'); ?></li>
                    </ul>
                </div>
            </div>
            <div id="jsstadmin-wrapper-top-right">
                <div id="jsstadmin-config-btn">
                    <a title="<?php echo __('Configuration','js-support-ticket'); ?>" href="<?php echo admin_url("admin.php?page=configuration"); ?>">
                        <img alt="<?php echo __('Configuration','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/config.png" />
                    </a>
                </div>
                <div id="jsstadmin-vers-txt">
                    <?php echo __("Version",'js-support-ticket'); ?>:
                    <span class="jsstadmin-ver"><?php echo JSSTincluder::getJSModel('configuration')->getConfigValue('versioncode'); ?></span>
                </div>
            </div>
        </div>
        <div id="jsstadmin-head">
            <h1 class="jsstadmin-head-text">
                <?php echo isset(jssupportticket::$_data[0]->subject) ? jssupportticket::$_data[0]->subject : __('Ticket Details', 'js-support-ticket'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
            <?php
            if (!empty(jssupportticket::$_data[0])) {
                if (jssupportticket::$_data[0]->lock == 1) {
                    $style = "darkred;";
                    $status = __('Lock', 'js-support-ticket');
                } elseif (jssupportticket::$_data[0]->status == 0) {
                    $style = "red;";
                    $status = __('New', 'js-support-ticket');
                } elseif (jssupportticket::$_data[0]->status == 1) {
                    $style = "orange;";
                    $status = __('Waiting Reply', 'js-support-ticket');
                } elseif (jssupportticket::$_data[0]->status == 2) {
                    $style = "#FF7F50;";
                    $status = __('In Progress', 'js-support-ticket');
                } elseif (jssupportticket::$_data[0]->status == 3) {
                    $style = "green;";
                    $status = __('Replied', 'js-support-ticket');
                } elseif (jssupportticket::$_data[0]->status == 4 OR jssupportticket::$_data[0]->status == 5) {
                    $style = "blue;";
                    $status = __('Closed', 'js-support-ticket');
                }
                $cur_uid = get_current_user_id();
                ?>

                <div id="userpopupblack" style="display:none;"> </div>
                <script>
                    jQuery(document).ready(function(){
                        jQuery(document).on('submit','#js-ticket-usercredentails-form',function(e){
                            e.preventDefault(); // avoid to execute the actual submit of the form.
                            var fdata = jQuery(this).serialize(); // serializes the form's elements.
                            jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'privatecredentials', task: 'storePrivateCredentials',formdata_string:fdata}, function (data) {
                                if(data){ // ajax executed
                                    var return_data = jQuery.parseJSON(data);
                                    if(return_data.status == 1){
                                        jQuery('.js-ticket-usercredentails-wrp').show();
                                        jQuery('.js-ticket-usercredentails-form-wrap').hide();
                                        jQuery('.js-ticket-usercredentails-credentails-wrp').append(return_data.content);
                                    }else{
                                        alert(return_data.error_message);
                                    }
                                }
                            });
                        })
                    });

                    function addEditCredentail(ticketid, uid, cred_id = 0, cred_data = ''){
                        jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'privatecredentials', task: 'getFormForPrivteCredentials', ticketid: ticketid, cred_id: cred_id, cred_data: cred_data, uid: uid}, function (data) {
                            if(data){ // ajax executed
                                var return_data = jQuery.parseJSON(data);
                                jQuery('.js-ticket-usercredentails-wrp').hide();
                                jQuery('.js-ticket-usercredentails-form-wrap').show();
                                jQuery('.js-ticket-usercredentails-form-wrap').html(return_data);
                                if(cred_id != 0){
                                    jQuery('#js-ticket-usercredentails-single-id-'+cred_id).remove();
                                }
                            }
                        });
                    }

                    function getCredentails(ticketid){
                        jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'privatecredentials', task: 'getPrivateCredentials',ticketid:ticketid}, function (data) {
                            if(data){ // ajax executed
                                var return_data = jQuery.parseJSON(data);
                                if(return_data.status == 1){
                                    jQuery('#userpopupblack').show();
                                    jQuery('#usercredentailspopup').slideDown('slow');
                                    jQuery('.js-ticket-usercredentails-wrp').slideDown('slow');
                                    jQuery('.js-ticket-usercredentails-form-wrap').hide();
                                    if(return_data.content != ''){
                                        jQuery('.js-ticket-usercredentails-credentails-wrp').html('');
                                        jQuery('.js-ticket-usercredentails-credentails-wrp').append(return_data.content);
                                    }
                                }
                            }
                        });
                        return false;
                    }

                    function removeCredentail(cred_id){
                        jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'privatecredentials', task: 'removePrivateCredential',cred_id:cred_id}, function (data) {
                            if(data){ // ajax executed
                                if(cred_id != 0){
                                    jQuery('#js-ticket-usercredentails-single-id-'+cred_id).remove();
                                }
                            }
                        });
                        return false;
                    }

                    function closeCredentailsForm(ticketid){
                        getCredentails(ticketid);
                    }
                </script>
                <div id="usercredentailspopup" class="jsst-popup-wrapper" style="display: none;">
                    <div class="userpopup-top">
                        <div class="userpopup-heading">
                            <?php echo __('Private Credentials', 'js-support-ticket'); ?>
                        </div>
                        <img alt="<?php echo __('Close','js-support-ticket'); ?>" class="close-credentails userpopup-close" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/close-icon-white.png" />
                    </div>
                    <div class="js-ticket-usercredentails-wrp" style="display: none;">
                        <div class="js-ticket-usercredentails-credentails-wrp">
                        </div>
                        <?php if(jssupportticket::$_data[0]->status != 4 && jssupportticket::$_data[0]->status != 5){ ?>
                            <div class="js-ticket-usercredentail-data-add-new-button-wrap" >
                                <button type="button" class="js-ticket-usercredentail-data-add-new-button" onclick="addEditCredentail(<?php echo jssupportticket::$_data[0]->id;?>,<?php echo get_current_user_id();?>);" >
                                    <?php echo __("Add New Credential","js-support-ticket"); ?>
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="js-ticket-usercredentails-form-wrap" >
                    </div>
                </div>
                <div id="userpopupblack" style="display:none;"></div>
                <div id="userpopup" class="srch-hist-popup" style="display:none;">
                    <div class="userpopup-top">
                        <div class="userpopup-heading">
                            <?php echo __('Ticket History', 'js-support-ticket'); ?>
                        </div>
                        <img alt="<?php echo __('Close','js-support-ticket'); ?>" class="close-history userpopup-close" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/close-icon-white.png" />
                    </div>
                    <div id="userpopup-records-wrp">
                        <div id="userpopup-records">
                            <div class="userpopup-search-history">
                                <?php // data[5] holds the tickect history
                                    $field_array = JSSTincluder::getJSModel('fieldordering')->getFieldTitleByFieldfor(1);
                                if ((!empty(jssupportticket::$_data[5]))) {
                                    ?>
                                    <?php foreach (jssupportticket::$_data[5] AS $history) { ?>
                                        <div class="userpopup-search-history-row">
                                            <div class="userpopup-search-history-col date">
                                                <?php echo date_i18n('Y-m-d', strtotime($history->datetime)); ?>
                                            </div>
                                            <div class="userpopup-search-history-col time">
                                            <?php echo date_i18n('H:i:s', strtotime($history->datetime)); ?>
                                            </div>
                                            <?php
                                            if (is_super_admin($history->uid)) {
                                                $message = 'admin';
                                            } elseif ( in_array('agent',jssupportticket::$_active_addons) && JSSTincluder::getJSModel('agent')->isUserStaff($history->uid)) {
                                                $message = 'agent';
                                            } else {
                                                $message = 'member';
                                            }
                                            ?>
                                            <div class="userpopup-search-history-col msg <?php echo $message; ?>">
                                                <?php echo wp_kses_post($history->message); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- inrternal notes popup -->
                <div id="internalnotes-popup" class="jsst-popup-wrapper" style="display: none;">
                    <?php if(in_array('note', jssupportticket::$_active_addons)){ ?>
                        <div class="userpopup-top">
                            <div class="userpopup-heading">
                                <?php echo __('Post New Internal Note','js-support-ticket'); ?>
                            </div>
                            <img alt="<?php echo __('Close','js-support-ticket'); ?>" class="userpopup-close" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/close-icon-white.png" />
                        </div>
                        <div class="js-admin-popup-cnt">  <!--  postinternalnote Area   -->
                            <form class="js-det-tkt-form" method="post" action="<?php echo admin_url("admin.php?page=note&task=savenote"); ?>"  enctype="multipart/form-data">
                                <?php if(in_array('timetracking', jssupportticket::$_active_addons)){ ?>
                                    <div class="jsst-ticket-detail-timer-wrapper"> <!-- Top Timer Section -->
                                        <div class="timer-left" >
                                            <div class="timer-total-time" >
                                                <?php
                                                    $hours = floor(jssupportticket::$_data['time_taken'] / 3600);
                                                    $mins = floor(jssupportticket::$_data['time_taken'] / 60 % 60);
                                                    $secs = floor(jssupportticket::$_data['time_taken'] % 60);
                                                    echo __('Time Taken','js-support-ticket').':&nbsp;'.sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="timer-right" >
                                            <div class="timer" >
                                                00:00:00
                                            </div>
                                            <div class="timer-buttons" >
                                                <?php if(JSSTincluder::getJSModel('userpermissions')->checkPermissionGrantedForTask('Edit Time')){ ?>
                                                    <span class="timer-button" onclick="showEditTimerPopup()" >
                                                        <img alt="<?php echo __('Edit','js-support-ticket'); ?>" class="default-show" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/edit-time-1.png"/>
                                                        <img alt="<?php echo __('Edit','js-support-ticket'); ?>" class="default-hide" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/edit-time.png"/>
                                                    </span>
                                                <?php } ?>
                                                <span class="timer-button cls_1" onclick="changeTimerStatus(1)" >
                                                    <img alt="<?php echo __('play','js-support-ticket'); ?>" class="default-show" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/play-time-1.png"/>
                                                    <img alt="<?php echo __('play','js-support-ticket'); ?>" class="default-hide" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/play-time.png"/>
                                                </span>
                                                <span class="timer-button cls_2" onclick="changeTimerStatus(2)" >
                                                    <img alt="<?php echo __('pause','js-support-ticket'); ?>" class="default-show" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/pause-time-1.png"/>
                                                    <img alt="<?php echo __('pause','js-support-ticket'); ?>" class="default-hide" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/pause-time.png"/>
                                                </span>
                                                <span class="timer-button cls_3" onclick="changeTimerStatus(3)" >
                                                    <img alt="<?php echo __('stop','js-support-ticket'); ?>" class="default-show" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/stop-time-1.png"/>
                                                    <img alt="<?php echo __('stop','js-support-ticket'); ?>" class="default-hide" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/stop-time.png"/>
                                                </span>
                                            </div>
                                        </div>
                                        <?php echo JSSTformfield::hidden('timer_time_in_seconds',''); ?>

                                        <?php echo JSSTformfield::hidden('timer_edit_desc',''); ?>
                                    </div>
                                <?php } ?>
                                <div class="js-form-wrapper">
                                    <div class="js-form-title"><?php echo __('Note Title', 'js-support-ticket'); ?></div>
                                    <div class="js-form-value"><?php echo JSSTformfield::text('internalnotetitle', '', array('class' => 'inputbox js-admin-popup-input-field')) ?></div>
                                </div>
                                <div class="js-form-wrapper">
                                    <div class="js-form-title"><label id="responcemsg" for="responce"><?php echo __('Internal Note', 'js-support-ticket'); ?></label></div>
                                    <div class="js-form-value"><?php echo wp_editor('', 'internalnote', array('media_buttons' => false)); ?></div>
                                </div>
                                <div class="js-form-wrapper">
                                    <div class="js-form-title"><?php echo __('Ticket', 'js-support-ticket'); echo ' '; echo __($field_array['status'],'js-support-ticket'); ?></div>
                                    <div class="js-form-value">
                                        <div class="jsst-formfield-radio-button-wrap">
                                            <?php echo JSSTformfield::checkbox('closeonreply', array('1' => __('Close on reply', 'js-support-ticket')), '', array('class' => 'radiobutton')); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-form-wrapper">
                                    <div class="js-form-title"><?php echo __($field_array['attachments'], 'js-support-ticket'); ?></div>
                                    <div class="js-form-value">
                                        <div class="tk_attachment_value_wrapperform">
                                            <span class="tk_attachment_value_text">
                                                <input type="file" class="inputbox" name="note_attachment" onchange="uploadfile(this, '<?php echo jssupportticket::$_config['file_maximum_size']; ?>', '<?php echo jssupportticket::$_config['file_extension']; ?>');" size="20" maxlenght='30'/>
                                                <span class='tk_attachment_remove'></span>
                                            </span>
                                        </div>
                                        <span class="tk_attachments_configform">
                                            <small><?php __('Maximum File Size','js-support-ticket');
                                            echo ' (' . jssupportticket::$_config['file_maximum_size']; ?>KB)<br><?php __('File Extension Type','js-support-ticket');
                                            echo ' (' . jssupportticket::$_config['file_extension'] . ')'; ?></small>
                                        </span>
                                    </div>
                                </div>
                                <div class="js-form-button">
                                    <?php echo JSSTformfield::submitbutton('postinternalnote', __('Post Internal Note','js-support-ticket'), array('class' => 'button js-admin-pop-btn-block', 'onclick' => "return checktinymcebyid('internalnote');")); ?>
                                </div>
                                <?php echo JSSTformfield::hidden('ticketid', jssupportticket::$_data[0]->id); ?>
                                <?php echo JSSTformfield::hidden('uid', get_current_user_id()); ?>
                                <?php echo JSSTformfield::hidden('action', 'note_savenote'); ?>
                                <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                            </form>
                        </div>
                    <?php } ?>
                </div>
                <!-- change priority popup -->
                <div id="changepriority-popup" class="jsst-popup-wrapper" style="display: none;">
                    <div class="userpopup-top">
                        <div class="userpopup-heading">
                            <?php echo __('Change Priority','js-support-ticket'); ?>
                        </div>
                        <img alt="<?php echo __('Close','js-support-ticket'); ?>" class="userpopup-close" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/close-icon-white.png" />
                    </div>
                    <div class="js-admin-popup-cnt">
                        <form class="js-det-tkt-form" method="post" action="#">
                            <div class="js-form-wrapper">
                                <div class="js-form-title">
                                    <?php echo __($field_array['priority'], 'js-support-ticket'); ?>
                                </div>
                                <div class="js-form-value">
                                    <?php echo JSSTformfield::select('prioritytemp', JSSTincluder::getJSModel('priority')->getPriorityForCombobox(), jssupportticket::$_data[0]->priorityid, __('Change Priority', 'js-support-ticket'), array('class' => 'inputbox js-admin-popup-select-field')); ?>
                                </div>
                            </div>
                            <div class="js-form-button">
                                <?php echo JSSTformfield::button('changepriority', __('Change Priority', 'js-support-ticket'), array('class' => 'button js-admin-pop-btn-block changeprioritybutton', 'onclick' => 'actionticket(1);')); ?>
                            </div>
                            <?php //echo JSSTformfield::hidden('ticketid', jssupportticket::$_data[0]->id); ?>
                            <?php //echo JSSTformfield::hidden('uid', get_current_user_id()); ?>
                            <?php //echo JSSTformfield::hidden('action', 'note_savenote'); ?>
                            <?php //echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                        </form>
                    </div>
                </div>
                <!-- change department popup -->
                <div id="changedept-popup" class="jsst-popup-wrapper" style="display: none;">
                    <?php if ( in_array('actions',jssupportticket::$_active_addons)) { ?>
                        <div class="userpopup-top">
                            <div class="userpopup-heading">
                                <?php echo __('Department Transfer','js-support-ticket'); ?>
                            </div>
                            <img alt="<?php echo __('Close','js-support-ticket'); ?>" class="userpopup-close" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/close-icon-white.png" />
                        </div>
                        <form class="js-det-tkt-form" method="post" action="<?php echo admin_url("admin.php?page=ticket&task=transferdepartment"); ?>"  enctype="multipart/form-data">
                            <div class="js-admin-popup-cnt">
                                <div class="js-form-wrapper">
                                    <div class="js-form-title"><?php echo __($field_array['department'], 'js-support-ticket'); ?></div>
                                    <div class="js-form-value">
                                        <?php echo JSSTformfield::select('departmentid', JSSTincluder::getJSModel('department')->getDepartmentForCombobox(), jssupportticket::$_data[0]->departmentid, __('Select Department', 'js-support-ticket'), array('class' => 'inputbox js-admin-popup-select-field')); ?>
                                    </div>
                                </div>
                                <?php if(in_array('note', jssupportticket::$_active_addons)){ ?>
                                    <div class="js-form-wrapper">
                                        <div class="js-form-title"><label id="responcemsg" for="responce"><?php echo __('Reason For Department Transfer', 'js-support-ticket'); ?></label></div>
                                        <div class="js-form-value"><?php echo wp_editor('', 'departmenttranfernote', array('media_buttons' => false)); ?></div>
                                    </div>
                                <?php } ?>
                                <div class="js-form-button">
                                    <?php echo JSSTformfield::submitbutton('departmenttransfer', __('Transfer','js-support-ticket'), array('class' => 'button js-admin-pop-btn-block', 'onclick' => "return checktinymcebyid('departmenttranfernote');")); ?>
                                </div>
                                <?php echo JSSTformfield::hidden('ticketid', jssupportticket::$_data[0]->id); ?>
                                <?php echo JSSTformfield::hidden('uid', get_current_user_id()); ?>
                                <?php echo JSSTformfield::hidden('action', 'ticket_transferdepartment'); ?>
                                <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                            </div>
                        </form>
                    <?php } ?>
                </div>
                <!-- assign to staff popup -->
                <div id="assignstaff-popup" class="jsst-popup-wrapper" style="display: none;">
                    <?php if ( in_array('agent',jssupportticket::$_active_addons)) { ?>
                        <div class="userpopup-top">
                            <div class="userpopup-heading">
                                <?php echo __('Assign To Agent','js-support-ticket'); ?>
                            </div>
                            <img alt="<?php echo __('Close','js-support-ticket'); ?>" class="userpopup-close" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/close-icon-white.png" />
                        </div>
                        <div class="js-admin-popup-cnt">
                            <form class="js-det-tkt-form" method="post" action="<?php echo admin_url("admin.php?page=ticket&task=assigntickettostaff"); ?>"  enctype="multipart/form-data">
                                <div class="js-form-wrapper">
                                    <div class="js-form-title"><?php echo __('Agent', 'js-support-ticket'); ?></div>
                                    <div class="js-form-value">
                                         <?php echo JSSTformfield::select('staffid', JSSTincluder::getJSModel('agent')->getstaffForCombobox(), jssupportticket::$_data[0]->staffid, __('Select Agent', 'js-support-ticket'), array('class' => 'inputbox js-admin-popup-select-field','required' => true)); ?>
                                    </div>
                                </div>
                                <?php if(in_array('note', jssupportticket::$_active_addons)){ ?>
                                    <div class="js-form-wrapper">
                                        <div class="js-form-title"><label id="responcemsg" for="responce"><?php echo __('Internal Note', 'js-support-ticket'); ?></label></div>
                                        <div class="js-form-value"><?php echo wp_editor('', 'assignnote', array('media_buttons' => false)); ?></div>
                                    </div>
                                <?php } ?>
                                <div class="js-form-button">
                                    <?php echo JSSTformfield::submitbutton('assigntostaff', __('Assign','js-support-ticket'), array('class' => 'button js-admin-pop-btn-block', 'onclick' => "return checktinymcebyid('assignnote');")); ?>
                                </div>
                                <?php echo JSSTformfield::hidden('ticketid', jssupportticket::$_data[0]->id); ?>
                                <?php echo JSSTformfield::hidden('uid', get_current_user_id()); ?>
                                <?php echo JSSTformfield::hidden('action', 'ticket_assigntickettostaff'); ?>
                                <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                            </form>
                        </div>
                    <?php } ?>
                </div>
                <!-- ticket detail -->
                <?php //echo '<pre>';print_r(jssupportticket::$_data[0]);die();?>
                <div class="js-ticket-detail-wrapper">
                    <div class="js-tkt-det-left">
                        <!-- ticket top info -->
                        <div class="js-tkt-det-cnt js-tkt-det-info-wrp">
                            <div class="js-tkt-det-user">
                                <div class="js-tkt-det-user-image">
                                    <?php echo jsst_get_avatar(jssupportticket::$_data[0]->uid); ?>
                                </div>
                                <div class="js-tkt-det-user-cnt">
                                    <div class="js-tkt-det-user-data name"><?php echo jssupportticket::$_data[0]->name; ?></div>
                                    <div class="js-tkt-det-user-data email"><?php echo jssupportticket::$_data[0]->email; ?></div>
                                    <div class="js-tkt-det-user-data number"><?php echo jssupportticket::$_data[0]->phone; ?></div>
                                </div>
                            </div>
                            <?php if(isset(jssupportticket::$_data['nticket'])){ ?>
                            <div class="js-tkt-det-other-tkt">
                                <a href="<?php echo admin_url('admin.php?page=ticket&jstlay=tickets&uid='.jssupportticket::$_data[0]->uid); ?>" class="js-tkt-det-other-tkt-btn">
                                    <?php echo sprintf(__('View all %d tickets by %s','js-support-ticket'),jssupportticket::$_data['nticket'],jssupportticket::$_data[0]->name); ?>
                                </a>
                                <a href="<?php echo admin_url('admin.php?page=ticket&jstlay=tickets&uid='.jssupportticket::$_data[0]->uid); ?>" class="js-tkt-det-other-tkt-img">
                                    <img alt="<?php echo __('Edit Ticket','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/new-window.png" />
                                </a>
                            </div>
                            <?php } ?>
                            <div class="js-tkt-det-tkt-msg">
                                <?php echo jssupportticket::$_data[0]->message; ?>
                            </div>
                            <?php
                            jssupportticket::$_data['custom']['ticketid'] = jssupportticket::$_data[0]->id;
                            $customfields = JSSTincluder::getObjectClass('customfields')->userFieldsData(1);
                            if (!empty($customfields)){
                                ?>
                                <div class="js-tkt-det-tkt-custm-flds">
                                    <?php
                                    foreach ($customfields as $field) {
                                        $ret = JSSTincluder::getObjectClass('customfields')->showCustomFields($field,2, jssupportticket::$_data[0]->params);
                                        ?>
                                        <div class="js-tkt-det-tkt-custm-flds-rec">
                                            <span class="js-tkt-det-tkt-custm-flds-tit">
                                                <?php echo $ret['title'].' : '; ?>
                                            </span>
                                            <span class="js-tkt-det-tkt-custm-flds-val">
                                                <?php echo $ret['value']; ?>
                                            </span>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="js-tkt-det-actn-btn-wrp">
                                <a title="<?php echo __('Edit Ticket','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="?page=ticket&jstlay=addticket&jssupportticketid=<?php echo jssupportticket::$_data[0]->id; ?>">
                                    <img alt="<?php echo __('Edit Ticket','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/edit.png" />
                                    <span><?php echo __('Edit Ticket','js-support-ticket'); ?></span>
                                </a>
                                <?php if(in_array('tickethistory', jssupportticket::$_active_addons)){ ?>
                                    <a title="<?php echo __('Show History','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="#" id="showhistory">
                                        <img alt="<?php echo __('Show History','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/history.png" />
                                        <span><?php echo __('Show History','js-support-ticket'); ?></span>
                                    </a>
                                <?php } ?>
                                <form method="post" action="<?php echo admin_url("admin.php?page=ticket&task=actionticket"); ?>" id="adminTicketform" enctype="multipart/form-data">
                                    <?php
                                        if (jssupportticket::$_data[0]->status != 5) { // merged closed ticket can not be reopend.
                                            if (jssupportticket::$_data[0]->status != 4) { ?>
                                                <a title="<?php echo __('Close Ticket','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="#" onclick="actionticket(2);">
                                                    <img alt="<?php echo __('Close Ticket','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/close.png" />
                                                    <span><?php echo __('Close Ticket','js-support-ticket'); ?></span>
                                                </a>
                                            <?php } else { ?>
                                                <a title="<?php echo __('Reopen Ticket','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="#" onclick="actionticket(3);">
                                                    <img alt="<?php echo __('Reopen Ticket','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/reopen.png" />
                                                    <span><?php echo __('Reopen Ticket','js-support-ticket'); ?></span>
                                                </a>
                                            <?php }
                                        }
                                        jssupportticket::$_data['custom']['ticketid'] = jssupportticket::$_data[0]->id;
                                    ?>
                                    <?php if (  in_array('actions',jssupportticket::$_active_addons) && jssupportticket::$_data[0]->status != 4 && jssupportticket::$_data[0]->status != 5 ) { ?>
                                        <a title="<?php echo __('Print Ticket','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="#" id="print-link" data-ticketid="<?php echo jssupportticket::$_data[0]->id; ?>">
                                            <img alt="<?php echo __('Print Ticket','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/print.png" />
                                            <span><?php echo __('Print Ticket','js-support-ticket'); ?></span>
                                        </a>
                                    <?php } ?>
                                    <?php if (  in_array('mergeticket',jssupportticket::$_active_addons) && jssupportticket::$_data[0]->status != 4 && jssupportticket::$_data[0]->status != 5 ) { ?>
                                    <a title="<?php echo __('Merge Ticket','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="#" id="mergeticket" data-ticketid="<?php echo jssupportticket::$_data[0]->id; ?>" onclick="return showPopupAndFillValues(<?php echo jssupportticket::$_data[0]->id ?>,4)" >
                                        <img alt="<?php echo __('Merge Ticket','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/merge-ticket.png" />
                                        <span><?php echo __('Merge Ticket','js-support-ticket'); ?></span>
                                    </a>
                                    <?php } ?>
                                    <?php if (in_array('privatecredentials',jssupportticket::$_active_addons)) { ?>
                                    <a title="<?php echo __('Private Credentials','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="javascript:return false;" id="privatecredentials" onclick="getCredentails(<?php echo jssupportticket::$_data[0]->id; ?>)" >
                                        <img alt="<?php echo __('Private Credentials','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/private-credentials.png"  />
                                        <span><?php echo __('Private Credentials','js-support-ticket'); ?></span>
                                    </a>
                                    <?php } ?>
                                    <?php
                                        if(in_array('actions', jssupportticket::$_active_addons)){
                                            if (jssupportticket::$_data[0]->lock == 1) { ?>
                                                <a title="<?php echo __('Unlock Ticket','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="#" onclick="actionticket(5);">
                                                    <img alt="<?php echo __('Unlock Ticket','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/unlock.png" />
                                                    <span><?php echo __('Unlock Ticket','js-support-ticket'); ?></span>
                                                </a>
                                            <?php } else { ?>
                                                <a title="<?php echo __('Lock Ticket','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="#" onclick="actionticket(4);">
                                                    <img alt="<?php echo __('Lock Ticket','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/lock.png" />
                                                    <span><?php echo __('Lock Ticket','js-support-ticket'); ?></span>
                                                </a>
                                            <?php }
                                        }
                                        if(in_array('banemail', jssupportticket::$_active_addons)){
                                            if (JSSTincluder::getJSModel('banemail')->isEmailBan(jssupportticket::$_data[0]->email)) { ?>
                                                <a titile="<?php echo __('Unban Email','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="#" onclick="actionticket(7);">
                                                    <img alt="<?php echo __('Unban Email','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/un-ban.png" />
                                                    <span><?php echo __('Unban Email','js-support-ticket'); ?></span>
                                                </a>
                                            <?php } else { ?>
                                                <a title="<?php echo __('Ban Email','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="#" onclick="actionticket(6);">
                                                    <img alt="<?php echo __('Ban Email','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/ban.png" />
                                                    <span><?php echo __('Ban Email','js-support-ticket'); ?></span>
                                                </a>
                                            <?php
                                            }
                                        }
                                        if(in_array('overdue', jssupportticket::$_active_addons)){
                                            if (jssupportticket::$_data[0]->isoverdue == 1) { ?>
                                                <a title="<?php echo __('Unmark Overdue','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="#" onclick="actionticket(11);">
                                                    <img alt="<?php echo __('Unmark Overdue','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/un-over-due.png" />
                                                    <span><?php echo __('Unmark Overdue','js-support-ticket'); ?></span>
                                                </a>
                                            <?php } else { ?>
                                                <a titlle="<?php echo __('Mark overdue','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="#" onclick="actionticket(8);">
                                                    <img alt="<?php echo __('Mark overdue','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/over-due.png" />
                                                    <span><?php echo __('Mark Overdue','js-support-ticket'); ?></span>
                                                </a>
                                            <?php }
                                        }
                                    ?>
                                    <?php if(in_array('actions', jssupportticket::$_active_addons)){ ?>
                                        <a title="<?php echo __('Mark in Progress','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="#" onclick="actionticket(9);">
                                            <img alt="<?php echo __('Mark in Progress','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/in-progress.png" />
                                            <span><?php echo __('Mark in Progress','js-support-ticket'); ?></span>
                                        </a>
                                    <?php } ?>
                                    <?php
                                        if(in_array('banemail', jssupportticket::$_active_addons)){ ?>
                                            <a title="<?php echo __('Ban Email and Close Ticket','js-support-ticket'); ?>" class="js-tkt-det-actn-btn" href="#" onclick="actionticket(10);">
                                                <img alt="<?php echo __('Ban Email and Close Ticket','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/ban-email-close-ticket.png" />
                                                <span><?php echo __('Ban Email and Close Ticket','js-support-ticket'); ?></span>
                                            </a>
                                    <?php } ?>
                                    <?php
                                        echo JSSTformfield::hidden('actionid', '');
                                        echo JSSTformfield::hidden('priority', '');
                                        echo JSSTformfield::hidden('ticketid', jssupportticket::$_data[0]->id);
                                        echo JSSTformfield::hidden('uid', get_current_user_id()); echo JSSTformfield::hidden('action', 'reply_savereply');
                                        echo JSSTformfield::hidden('form_request', 'jssupportticket');
                                    ?>
                                </form>
                            </div>
                        </div>
                        <!-- Tickect internal Note Area -->
                        <?php
                            $colored = "colored";
                            if(in_array('note', jssupportticket::$_active_addons)){ ?>
                                <div class="js-tkt-det-title"><?php echo __('Internal Note', 'js-support-ticket'); ?></div>
                                <?php if (!empty(jssupportticket::$_data[6])) {
                                    foreach (jssupportticket::$_data[6] AS $note) {
                                        if ($cur_uid == isset($note->uid))
                                            $colored = '';?>
                                        <div class="js-ticket-thread">
                                            <div class="js-ticket-thread-image">
                                                <?php if (in_array('agent',jssupportticket::$_active_addons) && $note->staffphoto) { ?>
                                                    <img alt="<?php echo __('agent image','js-support-ticket'); ?>" src="<?php echo jssupportticket::makeUrl(array('jstmod'=>'agent','task'=>'getStaffPhoto','action'=>'jstask','jssupportticketid'=>$note->staff_id, 'jsstpageid'=>jssupportticket::getPageid())); ?>">
                                                <?php } else {
                                                    echo jsst_get_avatar($note->userid);
                                                } ?>
                                            </div>
                                            <div class="js-ticket-thread-cnt">
                                                <div class="js-ticket-thread-data">
                                                    <span class="js-ticket-thread-person">
                                                        <?php
                                                        if(isset($note->staffname)){
                                                            echo $note->staffname;
                                                        }elseif(isset($note->display_name)){
                                                            echo $note->display_name;
                                                        }else{
                                                            echo '--------';
                                                        }
                                                        ?>
                                                    </span>
                                                    <?php
                                                        if(in_array('timetracking', jssupportticket::$_active_addons)){
                                                            $hours = floor($note->usertime / 3600);
                                                            $mins = floor($note->usertime / 60 % 60);
                                                            $secs = floor($note->usertime % 60);
                                                            $time = __('Time Taken','js-support-ticket').':&nbsp;'.sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                                                        ?>
                                                        <span class="js-ticket-thread-time"><?php echo $time; ?></span>
                                                    <?php } ?>
                                                </div>
                                                <?php if (isset($note->title) && $note->title != '') { ?>
                                                    <div class="js-ticket-thread-data">
                                                        <span class="js-ticket-thread-note"><?php echo $note->title; ?></span>
                                                    </div>
                                                <?php } ?>
                                                <div class="js-ticket-thread-data note-msg">
                                                <?php
                                                    echo wp_kses_post($note->note);
                                                    if($note->filesize > 0 && !empty($note->filename)){
                                                        echo '<div class="js_ticketattachment">
                                                                <span class="js_ticketattachment_fname">'
                                                                    . $note->filename . /*' (' . ($note->filesize / 1024 ) . ')&nbsp;&nbsp*/'
                                                                </span>
                                                                <a title="'.__('Download','js-support-ticket').'" class="button" target="_blank" href="'.admin_url('?page=note&action=jstask&task=downloadbyid&id='.$note->id).'">'.__('Download','js-support-ticket').'</a>
                                                            </div>';
                                                    }
                                                ?>
                                                </div>
                                                <div class="js-ticket-thread-cnt-btm">
                                                    <div class="js-ticket-thread-date"><?php echo date_i18n("l F d, Y, h:i:s", strtotime($note->created)); ?></div>
                                                    <div class="js-ticket-thread-actions">
                                                        <?php
                                                            if(in_array('timetracking', jssupportticket::$_active_addons)){
                                                                $hours = floor($note->usertime / 3600);
                                                                $mins = floor($note->usertime / 60 % 60);
                                                                $secs = floor($note->usertime % 60);
                                                                $time = __('Time Taken','js-support-ticket').':&nbsp;'.sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                                                            ?>
                                                            <a title="<?php echo __('Edit','js-support-ticket'); ?>" class="js-ticket-thread-actn-btn ticket-edit-time-button" href="#" onclick="return showPopupAndFillValues(<?php echo $note->id;?>,3)" >
                                                                <img alt="<?php echo __('Edit','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/edit-reply.png" />
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <div class="js-ticket-thread-add-btn">
                                    <a title="<?php echo __('Post New Internal Note','js-support-ticket'); ?>" href="#" class="js-ticket-thread-add-btn-link" id="int-note">
                                        <img alt="<?php echo __('Post New Internal Note','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/edit-time.png" />
                                        <span><?php echo __('Post New Internal Note','js-support-ticket'); ?></span>
                                    </a>
                                </div>
                            <?php } ?>
                        <!-- Tickect  Reply  Area -->
                        <div class="js-tkt-det-title"><?php echo __('Ticket Thread', 'js-support-ticket'); ?></div>
                        <div class="js-ticket-thread">
                            <div class="js-ticket-thread-image">
                                <?php if ( in_array('agent',jssupportticket::$_active_addons) &&  jssupportticket::$_data[0]->staffphotophoto) { ?>
                                    <img alt="<?php echo __('agent image','js-support-ticket'); ?>" src="<?php echo admin_url('?page=agent&action=jstask&task=getStaffPhoto&jssupportticketid='.jssupportticket::$_data[0]->staffphotoid ); ?>">
                                <?php } else {
                                    echo jsst_get_avatar(jssupportticket::$_data[0]->uid);
                                } ?>
                            </div>
                            <div class="js-ticket-thread-cnt">
                                <div class="js-ticket-thread-data">
                                    <span class="js-ticket-thread-person">
                                        <?php echo jssupportticket::$_data[0]->name; ?>
                                    </span>
                                </div>
                                <div class="js-ticket-thread-data">
                                    <span class="js-ticket-thread-email">
                                        <?php echo jssupportticket::$_data[0]->email; ?>
                                    </span>
                                </div>
                                <div class="js-ticket-thread-data note-msg">
                                    <?php echo wp_kses_post(jssupportticket::$_data[0]->message);
                                    ?>
                                </div>
                                <?php
                                    if (!empty(jssupportticket::$_data['ticket_attachment'])) {
                                        $datadirectory = jssupportticket::$_config['data_directory'];
                                        $maindir = wp_upload_dir();
                                        $path = $maindir['baseurl'];

                                        $path = $path .'/' . $datadirectory;
                                        $path = $path . '/attachmentdata';
                                        $path = $path . '/ticket/ticket_' . jssupportticket::$_data[0]->id . '/';
                                        foreach (jssupportticket::$_data['ticket_attachment'] AS $attachment) {
                                            $path = admin_url("?page=ticket&action=jstask&task=downloadbyid&id=".$attachment->id);
                                            echo '
                                            <div class="js_ticketattachment">
                                                <span class="js_ticketattachment_fname">
                                                  ' . $attachment->filename . /*' ( ' . $attachment->filesize . ' ) ' . */'
                                                </span>
                                                <a title="'.__('Download','js-support-ticket').'" class="button" target="_blank" href="' . $path . '">' . __('Download', 'js-support-ticket') . '</a>
                                            </div>';
                                        }
                                    }
                                ?>
                                <div class="js-ticket-thread-cnt-btm">
                                    <div class="js-ticket-thread-date">
                                        <?php echo date_i18n("l F d, Y, h:i:s", strtotime(jssupportticket::$_data[0]->created)); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tickect  Reply  Area -->
                        <?php
                            $colored = "colored";
                            if (!empty(jssupportticket::$_data[4]))
                                foreach (jssupportticket::$_data[4] AS $reply) {
                                if ($cur_uid == $reply->uid)
                                    $colored = '';
                                ?>
                                <div class="js-ticket-thread">
                                    <div class="js-ticket-thread-image">
                                        <?php if (in_array('agent',jssupportticket::$_active_addons) && $reply->staffphoto) { ?>
                                            <img alt="<?php echo __('agent image','js-support-ticket'); ?>"  src="<?php echo jssupportticket::makeUrl(array('jstmod'=>'agent','task'=>'getStaffPhoto','action'=>'jstask','jssupportticketid'=>$reply->staffid,'jsstpageid'=>jssupportticket::getPageid())); ?>">
                                        <?php } else {
                                            echo jsst_get_avatar($reply->uid);
                                        } ?>
                                    </div>
                                    <div class="js-ticket-thread-cnt">
                                        <div class="js-ticket-thread-data">
                                            <span class="js-ticket-thread-person"><?php echo $reply->name; ?></span>
                                            <?php
                                           if(in_array('timetracking', jssupportticket::$_active_addons)){
                                               if($reply->time > 0 ){
                                                   $hours = floor($reply->time / 3600);
                                                   $mins = floor($reply->time / 60 % 60);
                                                   $secs = floor($reply->time % 60);
                                                   $time = __('Time Taken','js-support-ticket').':&nbsp;'.sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                                                    ?>
                                                    <span class="js-ticket-thread-time"><?php echo $time; ?></span>
                                                    <?php
                                               }
                                           }
                                           ?>
                                        </div>
                                        <div class="js-ticket-thread-data">
                                            <span class="js-ticket-via-email">
                                                <?php echo ($reply->ticketviaemail == 1) ? __('Created via Email', 'js-support-ticket') : ''; ?>
                                            </span>
                                        </div>
                                        <div class="js-ticket-thread-data note-msg">
                                            <?php echo wp_kses_post(html_entity_decode($reply->message)); ?>
                                        </div>
                                        <?php
                                            if (!empty($reply->attachments)) {
                                                foreach ($reply->attachments AS $attachment) {
                                                    $path = admin_url("?page=ticket&action=jstask&task=downloadbyid&id=".$attachment->id);
                                                    echo '
                                                  <div class="js_ticketattachment">
                                                    <span class="js_ticketattachment_fname">
                                                    ' . $attachment->filename . /*' ( ' . $attachment->filesize . ' ) ' .*/ '
                                                    </span>
                                                    <a title="'.__('Download','js-support-ticket').'" class="button" target="_blank" href="' . $path . '">' . __('Download', 'js-support-ticket') . '</a>
                                                  </div>';
                                                }
                                            }
                                        ?>
                                        <div class="js-ticket-thread-cnt-btm">
                                            <div class="js-ticket-thread-date"><?php echo date_i18n("l F d, Y, h:i:s", strtotime($reply->created)); ?></div>
                                            <div class="js-ticket-thread-actions">
                                               <?php
                                               if(in_array('timetracking', jssupportticket::$_active_addons)){
                                                   if($reply->time > 0 ){
                                                       ?>
                                                       <a title="<?php echo __('Edit Time','js-support-ticket'); ?>" class="js-ticket-thread-actn-btn ticket-edit-time-button" href="#" onclick="return showPopupAndFillValues(<?php echo $reply->replyid;?>,2)" >
                                                           <img alt="<?php echo __('Edit Time','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/edit-reply.png" />
                                                           <span><?php echo __('Edit Time','js-support-ticket'); ?></span>
                                                       </a>
                                                   <?php
                                                   }
                                               }
                                               ?>
                                               <?php
                                                   if($reply->staffid != 0){ ?>
                                                       <a ttile="<?php echo __('Edit Reply','js-support-ticket'); ?>" class="js-ticket-thread-actn-btn ticket-edit-reply-button" href="#" onclick="return showPopupAndFillValues(<?php echo $reply->replyid;?>,1)" >
                                                           <img alt="<?php echo __('Edit Reply','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticket-detail/edit-reply.png" />
                                                           <span><?php echo __('Edit Reply','js-support-ticket'); ?></span>
                                                       </a>
                                               <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php } ?>
                        <!-- Post Reply Area -->
                        <div id="postreply" class="js-det-tkt-rply-frm">
                            <form class="js-det-tkt-form" method="post" action="<?php echo admin_url("admin.php?page=reply&task=savereply"); ?>"  enctype="multipart/form-data">
                                <div class="js-tkt-det-title"><?php echo __('Post Reply', 'js-support-ticket'); ?></div>
                                <?php if(in_array('timetracking', jssupportticket::$_active_addons)){ ?>
                                    <div class="jsst-ticket-detail-timer-wrapper"> <!-- Timer Wrapper -->
                                        <div class="timer-left" >
                                            <div class="timer-total-time" >
                                                <?php
                                                    $hours = floor(jssupportticket::$_data['time_taken'] / 3600);
                                                    $mins = floor(jssupportticket::$_data['time_taken'] / 60 % 60);
                                                    $secs = floor(jssupportticket::$_data['time_taken'] % 60);
                                                    echo __('Time Taken','js-support-ticket').':&nbsp;'.sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="timer-right" >
                                            <div class="timer" >
                                                00:00:00
                                            </div>
                                            <div class="timer-buttons" >
                                                <?php if(in_array('agent', jssupportticket::$_active_addons) && JSSTincluder::getJSModel('userpermissions')->checkPermissionGrantedForTask('Edit Own Time')){ ?>
                                                    <span class="timer-button" onclick="showEditTimerPopup()" >
                                                        <img alt="<?php echo __('Edit','js-support-ticket'); ?>" class="default-show" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/edit-time-1.png"/>
                                                        <img alt="<?php echo __('Edit','js-support-ticket'); ?>" class="default-hide" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/edit-time.png"/>
                                                    </span>
                                                <?php } ?>
                                                <span class="timer-button cls_1" onclick="changeTimerStatus(1)" >
                                                    <img alt="<?php echo __('play','js-support-ticket'); ?>" class="default-show" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/play-time-1.png"/>
                                                    <img alt="<?php echo __('play','js-support-ticket'); ?>" class="default-hide" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/play-time.png"/>
                                                </span>
                                                <span class="timer-button cls_2" onclick="changeTimerStatus(2)" >
                                                    <img <?php echo __('pause','js-support-ticket'); ?> class="default-show" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/pause-time-1.png"/>
                                                    <img <?php echo __('pause','js-support-ticket'); ?> class="default-hide" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/pause-time.png"/>
                                                </span>
                                                <span class="timer-button cls_3" onclick="changeTimerStatus(3)" >
                                                    <img <?php echo __('stop','js-support-ticket'); ?> class="default-show" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/stop-time-1.png"/>
                                                    <img <?php echo __('stop','js-support-ticket'); ?> class="default-hide" alt="image" src="<?php echo jssupportticket::$_pluginpath;?>includes/images/ticket-detail/stop-time.png"/>
                                                </span>
                                            </div>
                                        </div>
                                        <?php echo JSSTformfield::hidden('timer_time_in_seconds',''); ?>
                                        <?php echo JSSTformfield::hidden('timer_edit_desc',''); ?>
                                    </div>
                                <?php } ?>
                                <div class="js-form-wrapper">
                                    <div class="js-form-title"><label id="responcemsg" for="responce"><?php echo __('Response', 'js-support-ticket'); ?><span style="color: red;" >*</span></label></div>
                                    <div class="js-form-value"><?php echo wp_editor('', 'jsticket_message', array('media_buttons' => false)); ?></div>
                                </div>
                                <?php
                                if(in_array('cannedresponses', jssupportticket::$_active_addons)){
                                    $cannedresponses = JSSTincluder::getJSModel('cannedresponses')->getPreMadeMessageForCombobox();
                                    ?>
                                    <div class="js-form-wrapper">
                                        <div class="js-form-value">
                                            <?php
                                            foreach($cannedresponses as $premade){
                                                ?>
                                                <div class="js-tkt-det-perm-msg" onclick="getpremade(<?php echo $premade->id; ?>);">
                                                    <a href="javascript:void(0);" title="<?php echo __('premade','js-support-ticket'); ?>"><?php echo $premade->text; ?></a>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <div class="js-ticket-detail-append-signature-xs">
                                                <?php echo JSSTformfield::checkbox('append_premade', array('1' => __('Append', 'js-support-ticket')), '', array('class' => 'radiobutton')); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="js-form-wrapper">
                                    <div class="js-form-title"><?php echo __($field_array['attachments'], 'js-support-ticket'); ?></div>
                                    <div class="js-form-field">
                                        <div class="tk_attachment_value_wrapperform">
                                            <span class="tk_attachment_value_text">
                                                <input type="file" class="inputbox" name="filename[]" onchange="uploadfile(this, '<?php echo jssupportticket::$_config['file_maximum_size']; ?>', '<?php echo jssupportticket::$_config['file_extension']; ?>');" size="20" maxlenght='30'/>
                                                <span class='tk_attachment_remove'></span>
                                            </span>
                                        </div>
                                        <span class="tk_attachments_configform">
                                            <small><?php __('Maximum File Size','js-support-ticket');
                                            echo ' (' . jssupportticket::$_config['file_maximum_size']; ?>KB)<br><?php __('File Extension Type','js-support-ticket');
                                            echo ' (' . jssupportticket::$_config['file_extension'] . ')'; ?></small>
                                        </span>
                                        <span id="tk_attachment_add" class="tk_attachments_addform jsst-button-bg-link"><?php echo __('Add More File','js-support-ticket'); ?></span>
                                    </div>
                                </div>
                                <div class="js-form-wrapper">
                                    <div class="js-form-title"><?php echo __('Append Signature', 'js-support-ticket'); ?></div>
                                    <div class="js-form-value">
                                        <div class="jsst-formfield-radio-button-wrap">
                                            <?php echo JSSTformfield::checkbox('ownsignature', array('1' => __('Own Signature', 'js-support-ticket')), '', array('class' => 'radiobutton')); ?>
                                        </div>
                                        <div class="jsst-formfield-radio-button-wrap">
                                            <?php echo JSSTformfield::checkbox('departmentsignature', array('1' => __('Department Signature', 'js-support-ticket')), '', array('class' => 'radiobutton')); ?>
                                        </div>
                                        <div class="jsst-formfield-radio-button-wrap">
                                            <?php echo JSSTformfield::checkbox('nonesignature', array('1' => __('JNone', 'js-support-ticket')), '', array('class' => 'radiobutton')); ?>
                                        </div>
                                    </div>
                                    <?php
                                    $signature = get_user_meta(get_current_user_id(), 'jsst_signature', true);
                                    if(!$signature){
                                        ?>
                                        <a class="js-add-signature" target= "_blank" href="<?php echo admin_url('profile.php#jsstsignature'); ?>"><?php echo __("Add Signature",'js-support-ticket'); ?></a>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php
                                if ( in_array('agent',jssupportticket::$_active_addons) ) {
                                    $staffid = JSSTincluder::getJSModel('agent')->getStaffId(get_current_user_id());
                                    if (jssupportticket::$_data[0]->staffid != $staffid) {?>
                                    <div class="js-form-wrapper">
                                        <div class="js-form-title"><?php echo __('Assign to me', 'js-support-ticket'); ?></div>
                                        <div class="jsst-formfield-radio-button-wrap">
                                            <?php echo JSSTformfield::checkbox('assigntome', array('1' => __('Assign to me', 'js-support-ticket')), '', array('class' => 'radiobutton')); ?>
                                        </div>
                                    </div>
                                    <?php }
                                } ?>
                                <div class="js-form-wrapper">
                                    <div class="js-form-title"><?php echo __('Ticket', 'js-support-ticket'); echo ' '; echo __($field_array['status'],'js-support-ticket'); ?></div>
                                    <div class="jsst-formfield-radio-button-wrap">
                                        <?php echo JSSTformfield::checkbox('closeonreply', array('1' => __('Close on reply', 'js-support-ticket')), '', array('class' => 'radiobutton')); ?>
                                    </div>
                                </div>
                                <div class="js-form-button">
                                    <?php echo JSSTformfield::submitbutton('postreply', __('Post Reply','js-support-ticket'), array('class' => 'button js-form-save', 'onclick' => "return checktinymcebyid('message');")); ?>
                                </div>
                                <?php echo JSSTformfield::hidden('departmentid', jssupportticket::$_data[0]->departmentid); ?>
                                <?php echo JSSTformfield::hidden('ticketid', jssupportticket::$_data[0]->id); ?>
                                <?php echo JSSTformfield::hidden('ticketrandomid', jssupportticket::$_data[0]->ticketid); ?>
                                <?php echo JSSTformfield::hidden('hash', jssupportticket::$_data[0]->hash); ?>
                                <?php echo JSSTformfield::hidden('uid', get_current_user_id()); ?>
                                <?php echo JSSTformfield::hidden('action', 'reply_savereply'); ?>
                                <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                            </form>
                        </div> <!-- end of postreply div -->
                    </div>
                    <!-- ticket detail right side -->
                    <div class="js-tkt-det-right">
                        <!-- ticket detail info -->
                        <div class="js-tkt-det-cnt js-tkt-det-tkt-info">
                            <?php
                                if (jssupportticket::$_data[0]->status == 0) {
                                    $color = "#5bb12f;";
                                    $ticketmessage = __('Open', 'js-support-ticket');
                                } elseif (jssupportticket::$_data[0]->status == 1) {
                                    $color = "#28abe3;";
                                    $ticketmessage = __('On Waiting', 'js-support-ticket');
                                } elseif (jssupportticket::$_data[0]->status == 2) {
                                    $color = "#69d2e7;";
                                    $ticketmessage = __('In Progress', 'js-support-ticket');
                                } elseif (jssupportticket::$_data[0]->status == 3) {
                                    $color = "#FFB613;";
                                    $ticketmessage = __('Replied', 'js-support-ticket');
                                } elseif (jssupportticket::$_data[0]->status == 4) {
                                    $color = "#ed1c24;";
                                    $ticketmessage = __('Closed', 'js-support-ticket');
                                } elseif (jssupportticket::$_data[0]->status == 5) {
                                    $color = "#dc2742;";
                                    $ticketmessage = __('Close and merge', 'js-support-ticket');
                                }
                            ?>
                            <div class="js-tkt-det-status" style="background-color:<?php echo $color;?>;">
                                <?php
                                    jssupportticket::$_data['custom']['ticketid'] = jssupportticket::$_data[0]->id;
                                    if (jssupportticket::$_data[0]->status == 4)
                                        $ticketmessage = __('Closed', 'js-support-ticket');
                                    elseif (jssupportticket::$_data[0]->status == 2)
                                        $ticketmessage = __('In Progress', 'js-support-ticket');
                                    elseif (jssupportticket::$_data[0]->status == 5)
                                        $ticketmessage = __('Closed & merged', 'js-support-ticket');
                                    else
                                    $ticketmessage = __('Open', 'js-support-ticket');
                                    echo $ticketmessage;
                                ?>
                            </div>
                            <div class="js-tkt-det-info-cnt">
                                <div class="js-tkt-det-info-data">
                                    <span class="js-tkt-det-info-tit">
                                        <?php echo __('Created', 'js-support-ticket'). ' : '; ?>
                                    </span>
                                    <span class="js-tkt-det-info-val" title="<?php echo esc_html(date_i18n("d F, Y, h:i:s A", strtotime(jssupportticket::$_data[0]->created))); ?>">
                                        <?php echo human_time_diff(strtotime(jssupportticket::$_data[0]->created),strtotime(date_i18n("Y-m-d H:i:s"))).' '.__('ago', 'js-support-ticket'); ?>
                                    </span>
                                </div>
                                <div class="js-tkt-det-info-data">
                                    <span class="js-tkt-det-info-tit">
                                        <?php echo __('Last Reply', 'js-support-ticket'). ' : '; ?>
                                    </span>
                                    <span class="js-tkt-det-info-val">
                                        <?php
                                            if (empty(jssupportticket::$_data[0]->lastreply) || jssupportticket::$_data[0]->lastreply == '0000-00-00 00:00:00') echo __('No Last Reply', 'js-support-ticket');
                                            else echo date_i18n(jssupportticket::$_config['date_format'], strtotime(jssupportticket::$_data[0]->lastreply));
                                        ?>
                                    </span>
                                </div>
                                <div class="js-tkt-det-info-data">
                                    <span class="js-tkt-det-info-tit">
                                        <?php echo __($field_array['duedate'], 'js-support-ticket'). ' : ' ; ?>
                                    </span>
                                    <span class="js-tkt-det-info-val">
                                        <?php
                                            if (empty(jssupportticket::$_data[0]->duedate) || jssupportticket::$_data[0]->duedate == '0000-00-00 00:00:00') echo __('Not Given', 'js-support-ticket');
                                            else echo date_i18n(jssupportticket::$_config['date_format'], strtotime(jssupportticket::$_data[0]->duedate));
                                        ?>
                                    </span>
                                </div>
                                <div class="js-tkt-det-info-data">
                                    <span class="js-tkt-det-info-tit">
                                        <?php echo __($field_array['helptopic'], 'js-support-ticket'). ' : '; ?>
                                    </span>
                                    <span class="js-tkt-det-info-val">
                                        <?php if(in_array('helptopic',jssupportticket::$_active_addons)){ ?>
                                            <?php echo jssupportticket::$_data[0]->helptopic; ?>
                                        <?php } ?>
                                    </span>
                                </div>
                                <div class="js-tkt-det-info-data">
                                    <span class="js-tkt-det-info-tit">
                                        <?php echo __($field_array['department'], 'js-support-ticket'). ' : '; ?>
                                    </span>
                                    <span class="js-tkt-det-info-val">
                                        <?php echo __(jssupportticket::$_data[0]->departmentname, 'js-support-ticket'); ?>
                                    </span>
                                </div>
                                <div class="js-tkt-det-info-data">
                                    <span class="js-tkt-det-info-tit">
                                        <?php echo __('Ticket ID', 'js-support-ticket'). ' : '; ?>
                                    </span>
                                    <span class="js-tkt-det-info-val">
                                        <?php echo jssupportticket::$_data[0]->ticketid; ?>
                                        <a href="javascript:void(0)" title="<?php echo __('Copy','js-support-ticket'); ?>" class="js-tkt-det-copy-id" id="ticketidcopybtn" success="<?php echo __('Copied','js-support-ticket'); ?>"><?php echo __('Copy','js-support-ticket'); ?></a>
                                    </span>
                                </div>
                                <div class="js-tkt-det-info-data">
                                    <span class="js-tkt-det-info-tit">
                                        <?php echo __($field_array['status'], 'js-support-ticket'). ' : '; ?>
                                    </span>
                                    <span class="js-tkt-det-info-val">
                                        <?php
                                            $printstatus = 1;
                                            if (jssupportticket::$_data[0]->lock == 1) {
                                                echo '<span>' . __('Lock', 'js-support-ticket') . '</span>';
                                                $printstatus = 0;
                                            }
                                            if (jssupportticket::$_data[0]->isoverdue == 1) {
                                                echo '<span>' . __('Overdue', 'js-support-ticket') . '</span>';
                                                $printstatus = 0;
                                            }
                                            if ($printstatus == 1) {
                                                echo $ticketmessage;
                                            }
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- ticket detail priority -->
                        <div class="js-tkt-det-cnt js-tkt-det-tkt-prty">
                            <div class="js-tkt-det-hdg">
                                <div class="js-tkt-det-hdg-txt">
                                    <?php echo __('Priority','js-support-ticket'); ?>
                                </div>
                                <a title="<?php echo __('Change','js-support-ticket'); ?>" href="#" class="js-tkt-det-hdg-btn" id="chng-prority">
                                    <?php echo __('Change','js-support-ticket'); ?>
                                </a>
                            </div>
                            <div class="js-tkt-det-tkt-prty-txt" style="background:<?php echo jssupportticket::$_data[0]->prioritycolour; ?>;">
                                <?php echo __(jssupportticket::$_data[0]->priority, 'js-support-ticket'); ?>
                            </div>
                        </div>
                        <!-- ticket detail assign to staff -->
                        <?php
                        $agentflag = in_array('agent', jssupportticket::$_active_addons);
                        $departmentflag = in_array('actions', jssupportticket::$_active_addons);
                        if($agentflag || $departmentflag){
                            ?>
                            <div class="js-tkt-det-cnt js-tkt-det-tkt-assign">
                                <?php if($agentflag){ ?>
                                <div class="js-tkt-det-hdg">
                                    <div class="js-tkt-det-hdg-txt">
                                        <?php echo __('Ticket Assign & Transfer','js-support-ticket'); ?>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="js-tkt-det-tkt-asgn-cnt">
                                    <?php if($agentflag){ ?>
                                    <div class="js-tkt-det-hdg">
                                        <div class="js-tkt-det-hdg-txt">
                                            <?php
                                            if(jssupportticket::$_data[0]->staffid > 0){
                                                echo __('Ticket assigned to','js-support-ticket');
                                            }else{
                                                echo __('Not assigned to agent','js-support-ticket');
                                            }
                                            ?>
                                        </div>
                                        <a title="<?php echo __('Change','js-support-ticket'); ?>" href="#" class="js-tkt-det-hdg-btn" id="asgn-staff">
                                            <?php echo __('Change','js-support-ticket'); ?>
                                        </a>
                                    </div>
                                    <?php } ?>
                                    <div class="js-tkt-det-info-wrp">
                                        <?php if(jssupportticket::$_data[0]->staffid > 0){ ?>
                                        <div class="js-tkt-det-user">
                                            <div class="js-tkt-det-user-image">
                                                <?php
                                                    if(jssupportticket::$_data[0]->staffphoto){
                                                        ?>
                                                        <img alt="<?php echo __('staff photo','js-support-ticket'); ?>" src="<?php echo jssupportticket::makeUrl(array('jstmod'=>'agent','task'=>'getStaffPhoto','action'=>'jstask','jssupportticketid'=>jssupportticket::$_data[0]->staffid, 'jsstpageid'=>jssupportticket::getPageid())); ?>">
                                                        <?php
                                                    } else { ?>
                                                        <img alt="<?php echo __('staff photo','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath . '/includes/images/user.png'; ?>" />
                                                <?php } ?>
                                            </div>
                                            <div class="js-tkt-det-user-cnt">
                                                <div class="js-tkt-det-user-data"><?php echo jssupportticket::$_data[0]->staffname; ?></div>
                                                <div class="js-tkt-det-user-data"><?php echo jssupportticket::$_data[0]->staffemail; ?></div>
                                                <div class="js-tkt-det-user-data"><?php echo jssupportticket::$_data[0]->staffphone; ?></div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if($departmentflag){ ?>
                                        <div class="js-tkt-det-trsfer-dep">
                                            <div class="js-tkt-det-trsfer-dep-txt">
                                                <?php echo __('Department','js-support-ticket'); ?>: <?php echo __(jssupportticket::$_data[0]->departmentname,'js-support-ticket'); ?>
                                            </div>
                                            <a title="<?php echo __('Change','js-support-ticket'); ?>" href="#" class="js-tkt-det-hdg-btn" id="chng-dept">
                                                <?php echo __('Change','js-support-ticket'); ?>
                                            </a>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <!-- ticket detail time tracking -->
                        <?php if(in_array('timetracking', jssupportticket::$_active_addons)){ ?>
                        <div class="js-tkt-det-cnt js-tkt-det-time-tracker">
                            <div class="js-tkt-det-hdg">
                                <div class="js-tkt-det-hdg-txt">
                                    <?php echo __('Total Time Taken','js-support-ticket'); ?>
                                </div>
                            </div>
                            <div class="js-tkt-det-timer-wrp"> <!-- Timer Wrapper -->
                                <div class="timer-total-time" >
                                    <?php
                                    $hours = floor(jssupportticket::$_data['time_taken'] / 3600);
                                    $mins = floor(jssupportticket::$_data['time_taken'] / 60 % 60);
                                    $secs = floor(jssupportticket::$_data['time_taken'] % 60);
                                    $time =  sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                                    ?>
                                    <div class="timer-total-time-value">
                                        <span class="timer-box">
                                            <?php echo sprintf('%02d', $hours); ?>
                                        </span>
                                        <span class="timer-box">
                                            <?php echo sprintf('%02d', $mins); ?>
                                        </span>
                                        <span class="timer-box">
                                            <?php echo sprintf('%02d', $secs); ?>
                                        </span>
                                    </div>
                                </div>
                                <?php echo JSSTformfield::hidden('timer_time_in_seconds',''); ?>
                                <?php echo JSSTformfield::hidden('timer_edit_desc',''); ?>
                            </div>
                        </div>
                        <?php } ?>
                        <!-- ticket detail user tickets -->
                        <?php if(isset(jssupportticket::$_data['usertickets']) && !empty(jssupportticket::$_data['usertickets'])){ ?>
                        <div class="js-tkt-det-cnt js-tkt-det-user-tkts" id="usr-tkt">
                            <div class="js-tkt-det-hdg">
                                <div class="js-tkt-det-hdg-txt">
                                    <?php echo jssupportticket::$_data[0]->name.' '.__('Tickets','js-support-ticket'); ?>
                                </div>
                            </div>
                            <div class="js-tkt-det-usr-tkt-list">
                                <?php foreach (jssupportticket::$_data['usertickets'] AS $usertickets) { ?>
                                        <div class="js-tkt-det-user">
                                            <div class="js-tkt-det-user-image">
                                                <?php echo jsst_get_avatar(jssupportticket::$_data[0]->uid); ?>
                                            </div>
                                            <div class="js-tkt-det-user-cnt">
                                                <div class="js-tkt-det-user-data name">
                                                    <span id="usr-tkts">
                                                        <a title="<?php echo __('view ticket','js-support-ticket'); ?>" href="<?php echo admin_url('admin.php?page=ticket&jstlay=ticketdetail&jssupportticketid='.$usertickets->id); ?>">
                                                            <span class="js-tkt-det-user-val"><?php echo $usertickets->subject; ?></span>
                                                        </a>
                                                    </span>
                                                </div>
                                                <div class="js-tkt-det-user-data">
                                                    <span class="js-tkt-det-user-tit"><?php echo __('Department','js-support-ticket'). ' : '; ?></span>
                                                    <span class="js-tkt-det-user-val"><?php echo __($usertickets->departmentname,'js-support-ticket'); ?></span>
                                                </div>
                                                <div class="js-tkt-det-user-data">
                                                    <span class="js-tkt-det-prty" style="background: <?php echo $usertickets->prioritycolour; ?>;"><?php echo __($usertickets->priority,'js-support-ticket'); ?></span>
                                                    <span class="js-tkt-det-status">
                                                        <?php
                                                            if ($usertickets->status == 4)
                                                                $ticketmessage = __('Closed', 'js-support-ticket');
                                                            elseif ($usertickets->status == 2)
                                                                $ticketmessage = __('In Progress', 'js-support-ticket');
                                                            elseif ($usertickets->status == 5)
                                                                $ticketmessage = __('Closed & merged', 'js-support-ticket');
                                                            else
                                                            $ticketmessage = __('Open', 'js-support-ticket');
                                                            echo $ticketmessage;
                                                        ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <!-- ticket detail woocomerece -->
                        <?php
                            if( class_exists('WooCommerce') && in_array('woocommerce', jssupportticket::$_active_addons)){
                                $order = wc_get_order(jssupportticket::$_data[0]->wcorderid);
                                $order_productid = jssupportticket::$_data[0]->wcproductid;
                                if($order){
                                ?>
                                <div class="js-tkt-det-cnt js-tkt-det-woocom">
                                    <div class="js-tkt-det-hdg">
                                        <div class="js-tkt-det-hdg-txt">
                                            <?php echo __("Woocommerce Order",'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <div class="js-tkt-wc-order-box">
                                        <div class="js-tkt-wc-order-item">
                                            <div class="js-tkt-wc-order-item-title"><?php echo __($field_array['wcorderid'],'js-support-ticket'). ' : '; ?></div>
                                            <div class="js-tkt-wc-order-item-value">
                                                <a title="<?php echo __('Order','js-support-ticket'). ' : '; ?>" href="<?php echo $order->get_edit_order_url(); ?>">
                                                    #<?php echo $order->get_id(); ?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="js-tkt-wc-order-item">
                                            <div class="js-tkt-wc-order-item-title"><?php echo __("Status",'js-support-ticket'). ' : '; ?></div>
                                            <div class="js-tkt-wc-order-item-value"><?php echo wc_get_order_status_name($order->get_status()); ?></div>
                                        </div>
                                        <?php
                                        if($order_productid){
                                            $item = new WC_Order_Item_Product($order_productid);
                                            if($item){
                                                ?>
                                                <div class="js-tkt-wc-order-item">
                                                    <div class="js-tkt-wc-order-item-title"><?php echo __($field_array['wcproductid'],'js-support-ticket'). ' : '; ?></div>
                                                    <div class="js-tkt-wc-order-item-value"><?php echo $item->get_name(); ?></div>
                                                </div>
                                                <?php
                                            }
                                        }?>
                                        <div class="js-tkt-wc-order-item">
                                            <div class="js-tkt-wc-order-item-title"><?php echo __("Created",'js-support-ticket'). ' : '; ?></div>
                                            <div class="js-tkt-wc-order-item-value"><?php echo $order->get_date_created()->date_i18n(wc_date_format()); ?></div>
                                        </div>
                                        <?php do_action('jsst_woocommerce_order_detail_admin', $order, $order_productid); ?>
                                    </div>
                                </div>
								<?php 
								}else{ ?>
									<div class="js-tkt-det-cnt js-tkt-det-woocom">
										<div class="js-tkt-wc-order-box">
										<?php
										do_action('jsst_woocommerce_order_detail_admin', $order, $order_productid,jssupportticket::$_data[0]->uid); 
										?>
										</div>
									</div>
								<?php
								}
                            }
                        ?>
                        <!-- ticket detail easy digital downloads -->
                        <?php
                            if( class_exists('Easy_Digital_Downloads') && in_array('easydigitaldownloads', jssupportticket::$_active_addons)){
                                $orderid = jssupportticket::$_data[0]->eddorderid;
                                $order_product = jssupportticket::$_data[0]->eddproductid;
                                $order_license = jssupportticket::$_data[0]->eddlicensekey;
                                if($orderid != ''){ ?>
                                    <div class="js-tkt-det-cnt js-tkt-det-edd">
                                        <div class="js-tkt-det-hdg">
                                            <div class="js-tkt-det-hdg-txt">
                                                <?php echo __("Easy Digital Downloads",'js-support-ticket'); ?>
                                            </div>
                                        </div>
                                        <div class="js-tkt-wc-order-box">
                                            <div class="js-tkt-wc-order-item">
                                                <div class="js-tkt-wc-order-item-title"><?php echo __($field_array['eddorderid'],'js-support-ticket'); ?>:</div>
                                                <div class="js-tkt-wc-order-item-value">#<?php echo $orderid; ?></div>
                                            </div>

                                            <div class="js-tkt-wc-order-item">
                                                <div class="js-tkt-wc-order-item-title"><?php echo __($field_array['eddproductid'],'js-support-ticket'); ?>:</div>
                                                <div class="js-tkt-wc-order-item-value"><?php
                                                    if(is_numeric($order_product)){
                                                        $download = new EDD_Download($order_product);
                                                        echo $download->post_title;
                                                    }else{
                                                        echo '-----------';
                                                    }?>
                                                </div>
                                            </div>
                                            <?php if(class_exists('EDD_Software_Licensing')){ ?>
                                                <div class="js-tkt-wc-order-item">
                                                    <div class="js-tkt-wc-order-item-title"><?php echo __($field_array['eddlicensekey'],'js-support-ticket'); ?>:</div>
                                                    <div class="js-tkt-wc-order-item-value"><?php
                                                        if($order_license != ''){
                                                            $license = EDD_Software_Licensing::instance();
                                                            $licenseid = $license->get_license_by_key($order_license);
                                                            $result = $license->get_license_status($licenseid);
                                                            if($result == 'expired'){
                                                                $result_color = 'red';
                                                            }elseif($result == 'inactive'){
                                                                $result_color = 'orange';
                                                            }else{
                                                                $result_color = 'green';
                                                            }
                                                            echo $order_license.'&nbsp;&nbsp;(<span style="color:'.$result_color.';font-weight:bold;text-transform:uppercase;padding:0 3px;">'.$result.'</span>)';
                                                        }
                                                         ?>
                                                    </div>
                                                </div>
                                            <?php }?>
                                        </div>
                                    </div><?php
                                }
                            }
                        ?>
                        <!-- ticket detail envato validation -->
                        <?php
                        if(in_array('envatovalidation', jssupportticket::$_active_addons) && !empty(jssupportticket::$_data[0]->envatodata)){
                            $envlicense = jssupportticket::$_data[0]->envatodata;
                            if(!empty($envlicense)){ ?>
                                <div class="js-tkt-det-cnt js-tkt-det-env">
                                    <div class="js-tkt-det-hdg">
                                        <div class="js-tkt-det-hdg-txt">
                                            <?php echo __("Envato License",'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <div class="js-tkt-wc-order-box">
                                        <div class="js-tkt-wc-order-item">
                                            <div class="js-tkt-wc-order-item-title"><?php echo __("Item",'js-support-ticket'); ?>:</div>
                                            <div class="js-tkt-wc-order-item-value">
                                                <?php echo $envlicense['itemname'].' (#'.$envlicense['itemid'].')'; ?>
                                            </div>
                                        </div>
                                        <?php if(!empty($envlicense['buyer'])){ ?>
                                        <div class="js-tkt-wc-order-item">
                                            <div class="js-tkt-wc-order-item-title"><?php echo __("Buyer",'js-support-ticket'); ?>:</div>
                                            <div class="js-tkt-wc-order-item-value">
                                                <?php echo $envlicense['buyer']; ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="js-tkt-wc-order-item">
                                            <div class="js-tkt-wc-order-item-title"><?php echo __("License Type",'js-support-ticket'); ?>:</div>
                                            <div class="js-tkt-wc-order-item-value">
                                                <?php echo $envlicense['licensetype']; ?>
                                            </div>
                                        </div>
                                        <div class="js-tkt-wc-order-item">
                                            <div class="js-tkt-wc-order-item-title"><?php echo __("License",'js-support-ticket'); ?>:</div>
                                            <div class="js-tkt-wc-order-item-value">
                                                <?php echo $envlicense['license']; ?>
                                            </div>
                                        </div>
                                        <div class="js-tkt-wc-order-item">
                                            <div class="js-tkt-wc-order-item-title"><?php echo __("Purchase Date",'js-support-ticket'); ?>:</div>
                                            <div class="js-tkt-wc-order-item-value">
                                                <?php echo date_i18n("F d, Y, h:i:s", strtotime($envlicense['purchasedate'])); ?>
                                            </div>
                                        </div>
                                        <?php if(!empty($envlicense['supporteduntil'])){ ?>
                                        <div class="js-tkt-wc-order-item">
                                            <div class="js-tkt-wc-order-item-title"><?php echo __("Supported Until",'js-support-ticket'); ?>:</div>
                                            <div class="js-tkt-wc-order-item-value">
                                                <?php echo date_i18n("F d, Y", strtotime($envlicense['supporteduntil'])); ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div><?php
                            }
                        }
                        ?>
                        <!-- ticket detail paid support -->
                        <?php
                        if(in_array('paidsupport', jssupportticket::$_active_addons) && class_exists('WooCommerce')){
                            $linktickettoorder = true;
                            if(jssupportticket::$_data[0]->paidsupportitemid > 0){
                                $paidsupport = JSSTincluder::getJSModel('paidsupport')->getPaidSupportDetails(jssupportticket::$_data[0]->paidsupportitemid);
                                if($paidsupport){
                                    $linktickettoorder = false;
                                    $nonpreminumsupport = in_array(jssupportticket::$_data[0]->id,$paidsupport['ignoreticketids']) ? 1 : 0;
                                    ?>
                                    <div class="js-tkt-det-cnt js-tkt-det-pdsprt">
                                        <div class="js-tkt-det-hdg">
                                            <div class="js-tkt-det-hdg-txt">
                                                <?php echo __("Paid Support Details",'js-support-ticket'); ?>
                                            </div>
                                        </div>
                                        <?php if(!$nonpreminumsupport){ ?>
                                        <div class="js-tkt-wc-order-box">
                                            <div class="js-tkt-wc-order-item">
                                                <div class="js-tkt-wc-order-item-title"><?php echo __("Order",'js-support-ticket'); ?>:</div>
                                                <div class="js-tkt-wc-order-item-value">#<?php echo $paidsupport['orderid']; ?></div>
                                            </div>
                                            <div class="js-tkt-wc-order-item">
                                                <div class="js-tkt-wc-order-item-title"><?php echo __("Product Name",'js-support-ticket'); ?>:</div>
                                                <div class="js-tkt-wc-order-item-value"><?php echo $paidsupport['itemname']; ?></div>
                                            </div>
                                            <div class="js-tkt-wc-order-item">
                                                <div class="js-tkt-wc-order-item-title"><?php echo __("Total Tickets",'js-support-ticket'); ?>:</div>
                                                <div class="js-tkt-wc-order-item-value"><?php echo $paidsupport['totalticket']==-1 ? __("Unlimited",'js-support-ticket') : $paidsupport['totalticket']; ?></div>
                                            </div>
                                            <div class="js-tkt-wc-order-item">
                                                <div class="js-tkt-wc-order-item-title"><?php echo __("Remaining Tickets",'js-support-ticket'); ?>:</div>
                                                <div class="js-tkt-wc-order-item-value"><?php echo $paidsupport['totalticket']==-1 ? __("Unlimited",'js-support-ticket') : $paidsupport['remainingticket']; ?></div>
                                            </div>
                                            <?php if(isset($paidsupport['subscriptionid'])){ ?>
                                            <div class="js-tkt-wc-order-item">
                                                <div class="js-tkt-wc-order-item-title"><?php echo __("Subscription",'js-support-ticket'); ?>:</div>
                                                <div class="js-tkt-wc-order-item-value">#<?php echo $paidsupport['subscriptionid']; ?></div>
                                            </div>
                                            <?php } ?>
                                            <?php if(isset($paidsupport['subscriptionstartdate'])){ ?>
                                            <div class="js-tkt-wc-order-item">
                                                <div class="js-tkt-wc-order-item-title"><?php echo __("Subscribed On",'js-support-ticket'); ?>:</div>
                                                <div class="js-tkt-wc-order-item-value"><?php echo date_i18n("F d, Y, h:i:s", strtotime($paidsupport['subscriptionstartdate'])); ?></div>
                                            </div>
                                            <?php } ?>
                                            <?php if(isset($paidsupport['expiry'])){ ?>
                                            <div class="js-tkt-wc-order-item">
                                                <div class="js-tkt-wc-order-item-title"><?php echo __("Support Expiry",'js-support-ticket'); ?>:</div>
                                                <div class="js-tkt-wc-order-item-value"><?php echo $paidsupport['expiry'] ? date_i18n("F d, Y", strtotime($paidsupport['expiry'])) : __("No expiration",'js-support-ticket'); ?></div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php } ?>

                                        <div class="js-tkt-wc-order-box">
                                            <div class="js-tkt-wc-order-item">
                                                <label>
                                                    <input type="checkbox" id="nonpreminumsupport" <?php if($nonpreminumsupport) echo 'checked'; ?>>
                                                    <b><?php echo __("Non-premium support",'js-support-ticket'); ?></b>
                                                </label>
                                                <?php echo JSSTformfield::hidden('paidsupportitemid',jssupportticket::$_data[0]->paidsupportitemid) ?>
                                                <div>
                                                    <small><i><?php echo __("Check this box if this ticket should NOT apply against the paid support",'js-support-ticket'); ?></i></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            if($linktickettoorder){
                                $paidsupportitems = JSSTincluder::getJSModel('paidsupport')->getPaidSupportList(jssupportticket::$_data[0]->uid);
                                $paidsupportlist = array();
                                foreach($paidsupportitems as $row){
                                    $paidsupportlist[] = (object) array(
                                        'id' => $row->itemid,
                                        'text' => __("Order",'js-support-ticket').' #'.$row->orderid.', '.$row->itemname.', '.__("Remaining",'js-support-ticket').':'.$row->remaining.' '.__("Out of",'js-support-ticket').':'.$row->total,
                                    );
                                }
                                ?>
                                <div class="js-tkt-det-cnt">
                                    <div class="js-tkt-det-hdg">
                                        <div class="js-tkt-det-hdg-txt">
                                            <?php echo __("Link ticket to paid support",'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <div class="js-tkt-wc-order-box">
                                        <div class="js-tkt-wc-order-item">
                                            <?php echo JSSTformfield::select('paidsupportitemid',$paidsupportlist,null,__("Select",'js-support-ticket')); ?>
                                            <button type="button" class="button" id="paidsupportlinkticketbtn"><?php echo __("Link",'js-support-ticket'); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>

                <?php
            } else {
                JSSTlayout::getNoRecordFound();
            }
            ?>
        </div>
    </div>
</div>
