<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', $protocol.'ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
wp_enqueue_style('status-graph', jssupportticket::$_pluginpath . 'includes/css/status_graph.css');
wp_enqueue_script('google-api','https://www.google.com/jsapi?autoload={\'modules\':[{\'name\':\'visualization\',\'version\':\'1\',\'packages\':[\'corechart\']}]}');
?>
<?php JSSTmessage::getMessage(); ?>
<?php $formdata = JSSTformfield::getFormData(); ?>
<?php
$js_scriptdateformat = JSSTincluder::getJSModel('jssupportticket')->getJSSTDateFormat();
?>
<!-- <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script> -->
<script type="text/javascript">
    function updateuserlist(pagenum){
        jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'agent', task: 'getusersearchstaffreportajax',userlimit:pagenum}, function (data) {
            if(data){
                jQuery("div#records").html("");
                jQuery("div#records").html(data);
                setUserLink();
            }
        });
    }
    function setUserLink() {
        jQuery("a.js-userpopup-link").each(function () {
            var anchor = jQuery(this);
            jQuery(anchor).click(function (e) {
                var id = jQuery(this).attr('data-id');
                var name = jQuery(this).html();
                jQuery("input#username-text").val(name);
                jQuery("input#uid").val(id);
                jQuery("div#userpopup").slideUp('slow', function () {
                    jQuery("div#userpopupblack").hide();
                });
            });
        });
    }
    setUserLink();
    jQuery(document).ready(function ($) {
        $('.custom_date').datepicker({
            dateFormat: '<?php echo $js_scriptdateformat; ?>'
        });
        jQuery("a#userpopup").click(function (e) {
            e.preventDefault();
            jQuery("div#userpopupblack").show();
            jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'agent', task: 'getusersearchstaffreportajax'}, function (data) {
                if(data){
                    jQuery("div#userpopup-records").html("");
                    jQuery("div#userpopup-records").html(data);
                    setUserLink();
                }
            });
            jQuery("div#userpopup").slideDown('slow');
        });
        jQuery("form#userpopupsearch").submit(function (e) {
            e.preventDefault();
            var name = jQuery("input#name").val();
            var emailaddress = jQuery("input#emailaddress").val();
            jQuery.post(ajaxurl, {action: 'jsticket_ajax', name: name, emailaddress: emailaddress, jstmod: 'agent', task: 'getusersearchstaffreportajax'}, function (data) {
                if (data) {
                    jQuery("div#userpopup-records").html(data);
                    setUserLink();
                }
            });//jquery closed
        });
        jQuery(".userpopup-close, div#userpopupblack").click(function (e) {
            jQuery("div#userpopup").slideUp('slow', function () {
                jQuery("div#userpopupblack").hide();
            });

        });
        google.setOnLoadCallback(drawChart);
	});

	function resetFrom(){
		document.getElementById('date_start').value = '';
		document.getElementById('date_end').value = '';
		document.getElementById('uid').value = '';
		document.getElementById('username-text').value = '';
		document.getElementById('jssupportticketform').submit();
	}



      function drawChart() {
      	var data = new google.visualization.DataTable();
		data.addColumn('date', '<?php echo __('Dates','js-support-ticket'); ?>');
        data.addColumn('number', '<?php echo __('New','js-support-ticket'); ?>');
        data.addColumn('number', '<?php echo __('Answered','js-support-ticket'); ?>');
        data.addColumn('number', '<?php echo __('Pending','js-support-ticket'); ?>');
        data.addColumn('number', '<?php echo __('Overdue','js-support-ticket'); ?>');
        data.addColumn('number', '<?php echo __('Closed','js-support-ticket'); ?>');
		data.addRows([
			<?php echo jssupportticket::$_data['line_chart_json_array']; ?>
        ]);

        var options = {
          colors:['#1EADD8','#179650','#D98E11','#DB624C','#5F3BBB'],
          curveType: 'function',
          legend: { position: 'bottom' },
          pointSize: 6,
		  // This line will make you select an entire row of data at a time
		  focusTarget: 'category',
		  chartArea: {width:'90%',top:50}
		};

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
    }
	function resizeCharts () {
	    // redraw charts, dashboards, etc here
	    chart.draw(data, options);
	}
	jQuery(window).resize(resizeCharts);
</script>
<div id="userpopupblack" style="display:none;"></div>
<div id="userpopup" style="display:none;">
	<div class="userpopup-top">
	    <div class="userpopup-heading">
	    	<?php echo __('Select user','js-support-ticket'); ?>
	    </div>
	    <img alt="<?php echo __('Close','js-support-ticket'); ?>" class="userpopup-close" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/close-icon-white.png" />
    </div>
    <div class="userpopup-search">
        <form id="userpopupsearch">
            <div class="userpopup-fields-wrp">
                <div class="userpopup-fields">
                    <input type="text" name="username" id="username" placeholder="<?php echo __('Username','js-support-ticket'); ?>" />
                </div>
                <div class="userpopup-fields">
                    <input type="text" name="name" id="name" placeholder="<?php echo __('Name','js-support-ticket'); ?>" />
                </div>
                <div class="userpopup-fields">
                    <input type="text" name="emailaddress" id="emailaddress" placeholder="<?php echo __('Email Address','js-support-ticket'); ?>"/>
                </div>
                <div class="userpopup-btn-wrp">
                    <input class="userpopup-search-btn" type="submit" value="<?php echo __('Search','js-support-ticket'); ?>" />
                    <input class="userpopup-reset-btn" type="submit" onclick="document.getElementById('name').value = '';document.getElementById('username').value = ''; document.getElementById('emailaddress').value = '';" value="<?php echo __('Reset','js-support-ticket'); ?>" />
                </div>
            </div>
        </form>
    </div>
    <div id="userpopup-records-wrp">
	    <div id="userpopup-records">
            <div class="userpopup-records-desc">
                <?php echo __('Use search feature to select the user','js-support-ticket'); ?>
            </div>
	    </div>
    </div>
</div>
<?php JSSTmessage::getMessage(); ?>

<?php
$t_name = 'getstaffmemberexport';
$link_export = admin_url('admin.php?page=export&task='.$t_name.'&action=jstask&uid='.jssupportticket::$_data['filter']['uid'].'&date_start='.jssupportticket::$_data['filter']['date_start'].'&date_end='.jssupportticket::$_data['filter']['date_end']);
?>
<div id="jsstadmin-wrapper">
    <div id="jsstadmin-leftmenu">
        <?php  JSSTincluder::getClassesInclude('jsstadminsidemenu'); ?>
    </div>
    <div id="jsstadmin-data">
    	<div id="jsstadmin-wrapper-top">
    	    <div id="jsstadmin-wrapper-top-left">
    	        <div id="jsstadmin-breadcrunbs">
    	            <ul>
    	                <li><a href="?page=jssupportticket" title="<?php echo __('Dashboard','js-support-ticket'); ?>"><?php echo __('Dashboard','js-support-ticket'); ?></a></li>
    	                <li><?php echo __('Agent Reports','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __("Agent Reports", 'js-support-ticket') ?></h1>
    		<?php if(in_array('export', jssupportticket::$_active_addons)){ ?>
				<a title="<?php echo __('Export Data', 'js-support-ticket'); ?>" id="jsexport-link" class="jsstadmin-add-link button" href="<?php echo $link_export; ?>"><img alt="<?php echo __('Export', 'js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/export-icon.png" /><?php echo __('Export Data', 'js-support-ticket'); ?></a>
			<?php } ?>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
        	<div class="js-admin-staff-boxes">
				<?php
					$open_percentage = 0;
					$close_percentage = 0;
					$overdue_percentage = 0;
					$answered_percentage = 0;
					$pending_percentage = 0;
					if(isset(jssupportticket::$_data['ticket_total']) && isset(jssupportticket::$_data['ticket_total']['allticket']) && jssupportticket::$_data['ticket_total']['allticket'] != 0){
					    $open_percentage = round((jssupportticket::$_data['ticket_total']['openticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
					    $close_percentage = round((jssupportticket::$_data['ticket_total']['closeticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
					    $overdue_percentage = round((jssupportticket::$_data['ticket_total']['overdueticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
					    $answered_percentage = round((jssupportticket::$_data['ticket_total']['answeredticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
					    $pending_percentage = round((jssupportticket::$_data['ticket_total']['pendingticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
					}
					if(isset(jssupportticket::$_data['ticket_total']) && isset(jssupportticket::$_data['ticket_total']['allticket']) && jssupportticket::$_data['ticket_total']['allticket'] != 0){
					    $allticket_percentage = 100;
					}
				?>
				<div class="js-ticket-count">
				    <div class="js-ticket-link">
				        <a class="js-ticket-link js-ticket-green" href="#" data-tab-number="1" title="<?php echo __('Open Ticket', 'js-support-ticket'); ?>">
				            <div class="js-ticket-cricle-wrp" data-per="<?php echo $open_percentage; ?>" data-tab-number="1">
				                <div class="js-mr-rp" data-progress="<?php echo $open_percentage; ?>">
				                    <div class="circle">
				                        <div class="mask full">
				                             <div class="fill js-ticket-open"></div>
				                        </div>
				                        <div class="mask half">
				                            <div class="fill js-ticket-open"></div>
				                            <div class="fill fix"></div>
				                        </div>
				                        <div class="shadow"></div>
				                    </div>
				                    <div class="inset">
				                    </div>
				                </div>
				            </div>
				            <div class="js-ticket-link-text js-ticket-green">
				                <?php
				                    echo __('Open', 'js-support-ticket');
				                    echo ' ( '.jssupportticket::$_data['ticket_total']['openticket'].' )';
				                ?>
				            </div>
				        </a>
				    </div>
				    <div class="js-ticket-link">
				        <a class="js-ticket-link js-ticket-brown" href="#" data-tab-number="2" title="<?php echo __('answered ticket', 'js-support-ticket'); ?>">
				            <div class="js-ticket-cricle-wrp" data-per="<?php echo $answered_percentage; ?>" >
				                <div class="js-mr-rp" data-progress="<?php echo $answered_percentage; ?>">
				                    <div class="circle">
				                        <div class="mask full">
				                             <div class="fill js-ticket-answer"></div>
				                        </div>
				                        <div class="mask half">
				                            <div class="fill js-ticket-answer"></div>
				                            <div class="fill fix"></div>
				                        </div>
				                        <div class="shadow"></div>
				                    </div>
				                    <div class="inset">
				                    </div>
				                </div>
				            </div>
				            <div class="js-ticket-link-text js-ticket-brown">
				                <?php
				                    echo __('Answered', 'js-support-ticket');
				                    echo ' ( '. jssupportticket::$_data['ticket_total']['answeredticket'].' )';
				                ?>
				            </div>
				        </a>
				    </div>
				    <div class="js-ticket-link">
	                    <a class="js-ticket-link js-ticket-blue" href="#" data-tab-number="3" title="<?php echo __('pending ticket', 'js-support-ticket'); ?>">
	                        <div class="js-ticket-cricle-wrp" data-per="<?php echo $pending_percentage; ?>">
	                            <div class="js-mr-rp" data-progress="<?php echo $pending_percentage; ?>">
	                                <div class="circle">
	                                    <div class="mask full">
	                                         <div class="fill js-ticket-allticket"></div>
	                                    </div>
	                                    <div class="mask half">
	                                        <div class="fill js-ticket-allticket"></div>
	                                        <div class="fill fix"></div>
	                                    </div>
	                                    <div class="shadow"></div>
	                                </div>
	                                <div class="inset">
	                                </div>
	                            </div>
	                        </div>
	                        <div class="js-ticket-link-text js-ticket-blue">
	                            <?php
	                                echo __('Pending', 'js-support-ticket');
	                                echo ' ( '. jssupportticket::$_data['ticket_total']['pendingticket'].' )';
	                            ?>
	                        </div>
	                    </a>
	                </div>
				    <div class="js-ticket-link">
				        <a class="js-ticket-link js-ticket-orange" href="#" data-tab-number="4" title="<?php echo __('overdue ticket', 'js-support-ticket'); ?>">
				            <div class="js-ticket-cricle-wrp" data-per="<?php echo $overdue_percentage; ?>" >
				                <div class="js-mr-rp" data-progress="<?php echo $overdue_percentage; ?>">
				                    <div class="circle">
				                        <div class="mask full">
				                             <div class="fill js-ticket-overdue"></div>
				                        </div>
				                        <div class="mask half">
				                            <div class="fill js-ticket-overdue"></div>
				                            <div class="fill fix"></div>
				                        </div>
				                        <div class="shadow"></div>
				                    </div>
				                    <div class="inset">
				                    </div>
				                </div>
				            </div>
				            <div class="js-ticket-link-text js-ticket-orange">
				                <?php
				                    echo __('Overdue', 'js-support-ticket');
				                    echo ' ( '. jssupportticket::$_data['ticket_total']['overdueticket'].' )';
				                ?>
				            </div>
				        </a>
				    </div>
				    <div class="js-ticket-link">
				        <a class="js-ticket-link js-ticket-red" href="#" data-tab-number="5" title="<?php echo __('Close Ticket', 'js-support-ticket'); ?>">
				            <div class="js-ticket-cricle-wrp" data-per="<?php echo $close_percentage; ?>" >
				                <div class="js-mr-rp" data-progress="<?php echo $close_percentage; ?>">
				                    <div class="circle">
				                        <div class="mask full">
				                             <div class="fill js-ticket-close"></div>
				                        </div>
				                        <div class="mask half">
				                            <div class="fill js-ticket-close"></div>
				                            <div class="fill fix"></div>
				                        </div>
				                        <div class="shadow"></div>
				                    </div>
				                    <div class="inset">
				                    </div>
				                </div>
				            </div>
				            <div class="js-ticket-link-text js-ticket-red">
				                <?php
				                    echo __('Closed', 'js-support-ticket');
				                    echo ' ( '. jssupportticket::$_data['ticket_total']['closeticket'].' )';
				                ?>
				            </div>
				        </a>
				    </div>
				</div>
			</div>
	    	<form class="js-filter-form js-report-form" name="jssupportticketform" id="jssupportticketform" method="post" action="<?php echo admin_url("admin.php?page=reports&jstlay=staffreport"); ?>">
			    <?php
			        $curdate = date_i18n('Y-m-d');
			        $enddate = date_i18n('Y-m-d', strtotime("now -1 month"));
			        $date_start = !empty(jssupportticket::$_data['filter']['date_start']) ? jssupportticket::$_data['filter']['date_start'] : $curdate;
			        $date_end = !empty(jssupportticket::$_data['filter']['date_end']) ? jssupportticket::$_data['filter']['date_end'] : $enddate;
			        $uid = !empty(jssupportticket::$_data['filter']['uid']) ? jssupportticket::$_data['filter']['uid'] : '';
			    	echo JSSTformfield::text('date_start', date_i18n(jssupportticket::$_config['date_format'], strtotime($date_start)), array('class' => 'custom_date js-form-date-field','placeholder' => __('Start Date','js-support-ticket')));
			    	echo JSSTformfield::text('date_end', date_i18n(jssupportticket::$_config['date_format'], strtotime($date_end)), array('class' => 'custom_date js-form-date-field','placeholder' => __('End Date','js-support-ticket')));
			    	echo JSSTformfield::hidden('uid', $uid);
			    	echo JSSTformfield::hidden('JSST_form_search', 'JSST_SEARCH');
				?>
			    <?php if (!empty(jssupportticket::$_data['filter']['staffname'])) { ?>
			        <div id="username-div"><input class="js-form-input-field" type="text" value="<?php echo jssupportticket::$_data['filter']['staffname']; ?>" id="username-text" readonly="readonly" data-validation="required"/></div><a href="#" id="userpopup" title="<?php echo __('Select User', 'js-support-ticket'); ?>"><?php echo __('Select User', 'js-support-ticket'); ?></a>
			    <?php } else { ?>
			        <div id="username-div"></div><input class="js-form-input-field" type="text" value="" id="username-text" readonly="readonly" data-validation="required"/><a href="#" id="userpopup" title="<?php echo __('Select User', 'js-support-ticket'); ?>"><?php echo __('Select User', 'js-support-ticket'); ?></a>
			    <?php } ?>
			    <?php echo JSSTformfield::submitbutton('go', __('Search', 'js-support-ticket'), array('class' => 'button js-form-search')); ?>
				<?php echo JSSTformfield::button('reset', __('Reset', 'js-support-ticket'), array('class' => 'button js-form-reset', 'onclick' => 'resetFrom();')); ?>
			</form>
			<div class="js-admin-report">
				<div class="js-admin-subtitle"><?php echo __('Overall Report','js-support-ticket'); ?></div>
				<div class="js-admin-rep-graph" id="curve_chart" style="height:400px;width:98%; "></div>
			</div>
			<div class="js-admin-report">
				<div class="js-admin-subtitle"><?php echo __('Agents','js-support-ticket'); ?></div>
				<div class="js-admin-staff-list">
				<?php
				if(!empty(jssupportticket::$_data['staffs_report'])){
					foreach(jssupportticket::$_data['staffs_report'] AS $agent){ ?>
						<div class="js-admin-staff-wrapper">
							<a href="<?php echo admin_url('admin.php?page=reports&jstlay=staffdetailreport&id='.$agent->id.'&date_start='.jssupportticket::$_data['filter']['date_start'].'&date_end='.jssupportticket::$_data['filter']['date_end']); ?>" class="js-admin-staff-anchor-wrapper" title="<?php echo __('Staff', 'js-support-ticket'); ?>">
								<div class="js-admin-staff-cnt">
									<div class="js-report-staff-image">
										<?php
											if($agent->photo){
												$maindir = wp_upload_dir();
												$path = $maindir['baseurl'];

												$imageurl = $path."/".jssupportticket::$_config['data_directory']."/staffdata/staff_".$agent->id."/".$agent->photo;
											}else{
												$imageurl = jssupportticket::$_pluginpath."includes/images/user.png";
											}
										?>
										<img alt="<?php echo __('staff image', 'js-support-ticket'); ?>" class="js-report-staff-pic" src="<?php echo esc_url($imageurl); ?>" />
									</div>
									<div class="js-report-staff-cnt">
										<div class="js-report-staff-info js-report-staff-name">
											<?php
												if($agent->firstname && $agent->lastname){
													$agentname = $agent->firstname . ' ' . $agent->lastname;
												}else{
													$agentname = $agent->display_name;
												}
												echo $agentname;
											?>
										</div>
										<div class="js-report-staff-info js-report-staff-email">
											<?php
												if($agent->display_name){
													$username = $agent->display_name;
												}else{
													$username = $agent->user_nicename;
												}
												echo $username;
											?>
										</div>
										<div class="js-report-staff-info js-report-staff-email">
											<?php
												if($agent->email){
													$email = $agent->email;
												}else{
													$email = $agent->user_email;
												}
												echo $email;
											?>
										</div>
									</div>
								</div>
								<?php
								$rating_class = 'box6';
									if(in_array('feedback', jssupportticket::$_active_addons)){
										if($agent->avragerating > 4){
											$rating_class = 'box65';
										}elseif($agent->avragerating > 3){
											$rating_class = 'box64';
										}elseif($agent->avragerating > 2){
											$rating_class = 'box63';
										}elseif($agent->avragerating > 1){
											$rating_class = 'box62';
										}elseif($agent->avragerating > 0){
											$rating_class = 'box61';
										}
									}
									if(in_array('timetracking', jssupportticket::$_active_addons)){
										$hours = floor($agent->time[0] / 3600);
							            $mins = floor(($agent->time[0] / 60) % 60);
							            $secs = floor($agent->time[0] % 60);
							            $avgtime = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
							        }
						        ?>
								<div class="js-admin-staff-boxes">
									<?php
										$open_percentage = 0;
										$close_percentage = 0;
										$overdue_percentage = 0;
										$answered_percentage = 0;
										$pending_percentage = 0;
										if(isset(jssupportticket::$_data['ticket_total']) && isset(jssupportticket::$_data['ticket_total']['allticket']) && jssupportticket::$_data['ticket_total']['allticket'] != 0){
										    $open_percentage = round((jssupportticket::$_data['ticket_total']['openticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
										    $close_percentage = round((jssupportticket::$_data['ticket_total']['closeticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
										    $overdue_percentage = round((jssupportticket::$_data['ticket_total']['overdueticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
										    $answered_percentage = round((jssupportticket::$_data['ticket_total']['answeredticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
										    $pending_percentage = round((jssupportticket::$_data['ticket_total']['pendingticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
										}
										if(isset(jssupportticket::$_data['ticket_total']) && isset(jssupportticket::$_data['ticket_total']['allticket']) && jssupportticket::$_data['ticket_total']['allticket'] != 0){
										    $allticket_percentage = 100;
										}
									?>
									<div class="js-ticket-count">
									    <div class="js-ticket-link">
									        <a class="js-ticket-link js-ticket-green" href="#" data-tab-number="1" title="<?php echo __('Open Ticket', 'js-support-ticket'); ?>">
									            <div class="js-ticket-cricle-wrp" data-per="<?php echo $open_percentage; ?>" data-tab-number="1">
									                <div class="js-mr-rp" data-progress="<?php echo $open_percentage; ?>">
									                    <div class="circle">
									                        <div class="mask full">
									                             <div class="fill js-ticket-open"></div>
									                        </div>
									                        <div class="mask half">
									                            <div class="fill js-ticket-open"></div>
									                            <div class="fill fix"></div>
									                        </div>
									                        <div class="shadow"></div>
									                    </div>
									                    <div class="inset">
									                    </div>
									                </div>
									            </div>
									            <div class="js-ticket-link-text js-ticket-green">
									                <?php
									                    echo __('Open', 'js-support-ticket');
									                    echo ' ( '.jssupportticket::$_data['ticket_total']['openticket'].' )';
									                ?>
									            </div>
									        </a>
									    </div>
									    <div class="js-ticket-link">
									        <a class="js-ticket-link js-ticket-brown" href="#" data-tab-number="2" title="<?php echo __('answered ticket', 'js-support-ticket'); ?>">
									            <div class="js-ticket-cricle-wrp" data-per="<?php echo $answered_percentage; ?>" >
									                <div class="js-mr-rp" data-progress="<?php echo $answered_percentage; ?>">
									                    <div class="circle">
									                        <div class="mask full">
									                             <div class="fill js-ticket-answer"></div>
									                        </div>
									                        <div class="mask half">
									                            <div class="fill js-ticket-answer"></div>
									                            <div class="fill fix"></div>
									                        </div>
									                        <div class="shadow"></div>
									                    </div>
									                    <div class="inset">
									                    </div>
									                </div>
									            </div>
									            <div class="js-ticket-link-text js-ticket-brown">
									                <?php
									                    echo __('Answered', 'js-support-ticket');
									                    echo ' ( '. jssupportticket::$_data['ticket_total']['answeredticket'].' )';
									                ?>
									            </div>
									        </a>
									    </div>
									    <div class="js-ticket-link">
						                    <a class="js-ticket-link js-ticket-blue" href="#" data-tab-number="3" title="<?php echo __('pending ticket', 'js-support-ticket'); ?>">
						                        <div class="js-ticket-cricle-wrp" data-per="<?php echo $pending_percentage; ?>">
						                            <div class="js-mr-rp" data-progress="<?php echo $pending_percentage; ?>">
						                                <div class="circle">
						                                    <div class="mask full">
						                                         <div class="fill js-ticket-allticket"></div>
						                                    </div>
						                                    <div class="mask half">
						                                        <div class="fill js-ticket-allticket"></div>
						                                        <div class="fill fix"></div>
						                                    </div>
						                                    <div class="shadow"></div>
						                                </div>
						                                <div class="inset">
						                                </div>
						                            </div>
						                        </div>
						                        <div class="js-ticket-link-text js-ticket-blue">
						                            <?php
						                                echo __('Pending', 'js-support-ticket');
						                                echo ' ( '. jssupportticket::$_data['ticket_total']['pendingticket'].' )';
						                            ?>
						                        </div>
						                    </a>
						                </div>
									    <div class="js-ticket-link">
									        <a class="js-ticket-link js-ticket-orange" href="#" data-tab-number="4" title="<?php echo __('overdue ticket', 'js-support-ticket'); ?>">
									            <div class="js-ticket-cricle-wrp" data-per="<?php echo $overdue_percentage; ?>" >
									                <div class="js-mr-rp" data-progress="<?php echo $overdue_percentage; ?>">
									                    <div class="circle">
									                        <div class="mask full">
									                             <div class="fill js-ticket-overdue"></div>
									                        </div>
									                        <div class="mask half">
									                            <div class="fill js-ticket-overdue"></div>
									                            <div class="fill fix"></div>
									                        </div>
									                        <div class="shadow"></div>
									                    </div>
									                    <div class="inset">
									                    </div>
									                </div>
									            </div>
									            <div class="js-ticket-link-text js-ticket-orange">
									                <?php
									                    echo __('Overdue', 'js-support-ticket');
									                    echo ' ( '. jssupportticket::$_data['ticket_total']['overdueticket'].' )';
									                ?>
									            </div>
									        </a>
									    </div>
									    <div class="js-ticket-link">
									        <a class="js-ticket-link js-ticket-red" href="#" data-tab-number="5" title="<?php echo __('Close Ticket', 'js-support-ticket'); ?>">
									            <div class="js-ticket-cricle-wrp" data-per="<?php echo $close_percentage; ?>" >
									                <div class="js-mr-rp" data-progress="<?php echo $close_percentage; ?>">
									                    <div class="circle">
									                        <div class="mask full">
									                             <div class="fill js-ticket-close"></div>
									                        </div>
									                        <div class="mask half">
									                            <div class="fill js-ticket-close"></div>
									                            <div class="fill fix"></div>
									                        </div>
									                        <div class="shadow"></div>
									                    </div>
									                    <div class="inset">
									                    </div>
									                </div>
									            </div>
									            <div class="js-ticket-link-text js-ticket-red">
									                <?php
									                    echo __('Closed', 'js-support-ticket');
									                    echo ' ( '. jssupportticket::$_data['ticket_total']['closeticket'].' )';
									                ?>
									            </div>
									        </a>
									    </div>
										<?php if(in_array('feedback', jssupportticket::$_active_addons)){ ?>
											<div class="js-ticket-link <?php echo $rating_class?>">
												<a href="#" class="js-ticket-link js-ticket-mariner" title="<?php echo __('Rating', 'js-support-ticket'); ?>">
													<span class="js-report-box-number">
														<?php if($agent->avragerating > 0){ ?>
															<span class="rating" ><?php echo round($agent->avragerating,1); ?></span>/5
														<?php }else{ ?>
															NA
														<?php } ?>
													</span>
													<span class="js-report-box-title"><?php echo __('Average rating','js-support-ticket'); ?></span>
													<div class="js-report-box-color"></div>
												</a>
											</div>
										<?php } ?>
										<?php if(in_array('timetracking', jssupportticket::$_active_addons)){ ?>
											<div class="js-ticket-link">
												<a href="#" class="js-ticket-link js-ticket-purple" title="<?php echo __('Average time', 'js-support-ticket'); ?>">
													<span class="js-report-box-number">
														<span class="time" >
															<?php echo $avgtime; ?>
														</span>
														<span class="exclamation" >
															<?php
															if($agent->time[1] != 0){
												            	echo '!';
												            }
															?>
														</span>
													</span>
													<span class="js-report-box-title"><?php echo __('Average time','js-support-ticket'); ?></span>
													<div class="js-report-box-color"></div>
												</a>
											</div>
										<?php } ?>
									</div>
								</div>
							</a>
						</div>
					<?php
					}
				    if (jssupportticket::$_data[1]) {
				        echo '<div class="tablenav"><div class="tablenav-pages"' . jssupportticket::$_data[1] . '</div></div>';
				    }
				}
				?>
				</div>
			</div>
		</div>
	</div>
</div>
