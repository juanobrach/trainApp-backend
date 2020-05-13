<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', $protocol.'ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
wp_enqueue_style('status-graph', jssupportticket::$_pluginpath . 'includes/css/status_graph.css');
wp_enqueue_script('google-api','https://www.google.com/jsapi?autoload={\'modules\':[{\'name\':\'visualization\',\'version\':\'1\',\'packages\':[\'corechart\']}]}');
?>
<?php
$js_scriptdateformat = JSSTincluder::getJSModel('jssupportticket')->getJSSTDateFormat();
?>
<!-- <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script> -->
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.custom_date').datepicker({
            dateFormat: '<?php echo $js_scriptdateformat; ?>'
        });
		google.setOnLoadCallback(drawChart);
	});

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

	function resetFrom(){
		document.getElementById('date_start').value = '';
		document.getElementById('date_end').value = '';
		document.getElementById('jssupportticketform').submit();
	}
</script>
<?php JSSTmessage::getMessage(); ?>

<?php
$t_name = 'getstaffmemberexportbystaffid';
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
    	                <li><?php echo __('Agent Detail Report','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __("Agent Detail Report", 'js-support-ticket') ?></h1>
    		<?php if(in_array('export', jssupportticket::$_active_addons)){ ?>
				<a title="<?php echo __('Export Data', 'js-support-ticket'); ?>" id="jsexport-link" class="jsstadmin-add-link button" href="<?php echo $link_export; ?>"><img alt="<?php echo __('Export', 'js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/export-icon.png" /><?php echo __('Export Data', 'js-support-ticket'); ?></a>
			<?php } ?>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
		    <?php
			$agent = jssupportticket::$_data['staff_report'];
			?>
			<a href="<?php echo admin_url('admin.php?page=reports&jstlay=staffdetailreport&id='.$agent->id.'&date_start='.jssupportticket::$_data['filter']['date_start'].'&date_end='.jssupportticket::$_data['filter']['date_end']); ?>"></a>
			<form class="js-filter-form js-report-form" name="jssupportticketform" id="jssupportticketform" method="post" action="<?php echo admin_url("admin.php?page=reports&jstlay=staffdetailreport&id=".jssupportticket::$_data['staff_report']->id); ?>">
			    <?php
			        $curdate = date_i18n('Y-m-d');
			        $enddate = date_i18n('Y-m-d', strtotime("now -1 month"));
			        $date_start = !empty(jssupportticket::$_data['filter']['date_start']) ? jssupportticket::$_data['filter']['date_start'] : $curdate;
			        $date_end = !empty(jssupportticket::$_data['filter']['date_end']) ? jssupportticket::$_data['filter']['date_end'] : $enddate;
			    	echo JSSTformfield::text('date_start', date_i18n(jssupportticket::$_config['date_format'], strtotime($date_start)), array('class' => 'custom_date js-form-date-field','placeholder' => __('Start Date','js-support-ticket')));
			    	echo JSSTformfield::text('date_end', date_i18n(jssupportticket::$_config['date_format'], strtotime($date_end)), array('class' => 'custom_date js-form-date-field','placeholder' => __('End Date','js-support-ticket')));
			    	echo JSSTformfield::hidden('JSST_form_search', 'JSST_SEARCH');
				?>
			    <?php echo JSSTformfield::submitbutton('go', __('Search', 'js-support-ticket'), array('class' => 'button js-form-search')); ?>
				<?php echo JSSTformfield::button('reset', __('Reset', 'js-support-ticket'), array('class' => 'button js-form-reset', 'onclick' => 'resetFrom();')); ?>
			</form>
			<div class="js-admin-report">
				<div class="js-admin-subtitle"><?php echo __('Agent Statistics','js-support-ticket'); ?></div>
				<div class="js-admin-rep-graph" id="curve_chart" style="height:400px;width:98%; "></div>
			</div>
			<div class="js-admin-report">
				<div class="js-admin-staff-list p0">
					<?php
						if(!empty($agent)){ ?>
						<div class="js-admin-staff-wrapper">
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
									<img alt="<?php echo __('staff image','js-support-ticket'); ?>" class="js-report-staff-pic" src="<?php echo esc_url($imageurl); ?>" />
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
							<div class="js-admin-staff-boxes">
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
								<?php
									$open_percentage = 0;
									$close_percentage = 0;
									$overdue_percentage = 0;
									$answered_percentage = 0;
									$pending_percentage = 0;
									if(isset($agent) && isset($agent->allticket) && $agent->allticket != 0){
									    $open_percentage = round(($agent->openticket / $agent->allticket) * 100);
									    $close_percentage = round(($agent->closeticket / $agent->allticket) * 100);
									    $overdue_percentage = round(($agent->overdueticket / $agent->allticket) * 100);
									    $answered_percentage = round(($agent->answeredticket / $agent->allticket) * 100);
									    $pending_percentage = round(($agent->pendingticket / $agent->allticket) * 100);
									}
									if(isset($agent) && isset($agent->allticket) && $agent->allticket != 0){
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
								                    echo ' ( '.$agent->openticket.' )';
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
								                    echo ' ( '. $agent->answeredticket.' )';
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
					                                echo ' ( '. $agent->pendingticket.' )';
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
								                    echo ' ( '. $agent->overdueticket.' )';
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
								                    echo ' ( '. $agent->closeticket.' )';
								                ?>
								            </div>
								        </a>
								    </div>
									<?php if(in_array('feedback', jssupportticket::$_active_addons)){ ?>
										<div class="js-ticket-link <?php echo $rating_class?>">
											<a href="#" class="js-ticket-link js-ticket-mariner" title="<?php echo __('Average rating', 'js-support-ticket'); ?>">
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
						</div>
					<?php
					} ?>
				</div>
			</div>
			<div class="js-admin-report">
				<div class="js-admin-subtitle"><?php echo __('Tickets','js-support-ticket'); ?></div>
				<?php
					if(!empty(jssupportticket::$_data['staff_tickets'])){ ?>
						<table id="js-support-ticket-table" class="js-admin-report-tickets">
							<tr class="js-support-ticket-table-heading">
								<th class="left"><?php echo __('Subject','js-support-ticket'); ?></th>
								<th><?php echo __('Status','js-support-ticket'); ?></th>
								<th><?php echo __('Priority','js-support-ticket'); ?></th>
								<th><?php echo __('Created','js-support-ticket'); ?></th>
								<?php if(in_array('feedback', jssupportticket::$_active_addons)){ ?>
									<th><?php echo __('Rating','js-support-ticket'); ?></th>
								<?php }?>
								<?php if(in_array('timetracking', jssupportticket::$_active_addons)){ ?>
									<th><?php echo __('Time Taken','js-support-ticket'); ?></th>
								<?php }?>
							</tr>
						<?php
						$show_flag = 0;
						foreach(jssupportticket::$_data['staff_tickets'] AS $ticket){
							if(in_array('timetracking', jssupportticket::$_active_addons)){
								$hours = floor($ticket->time / 3600);
					            $mins = floor(($ticket->time / 60) % 60);
					            $secs = floor($ticket->time % 60);
					            $avgtime = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
					        }
							if(in_array('feedback', jssupportticket::$_active_addons)){
					            $rating_color = 0;
					            if($ticket->rating > 4){
					            	$rating_color = '#ea1d22';
					            }elseif($ticket->rating > 3){
					            	$rating_color = '#f58634';
					            }elseif($ticket->rating > 2){
					            	$rating_color = '#a8518a';
					            }elseif($ticket->rating > 1){
					            	$rating_color = '#0098da';
					            }elseif($ticket->rating > 0){
					            	$rating_color = '#069a2e';
					            }
					        }
						 ?>
							<tr>
								<td class="overflow left">
									<span class="js-support-ticket-table-responsive-heading">
										<?php echo __('Subject','js-support-ticket'); ?> :
									</span>
									<a title="<?php echo __('Ticket', 'js-support-ticket'); ?>" target="_blank" href="<?php echo admin_url('admin.php?page=ticket&jstlay=ticketdetail&jssupportticketid='.$ticket->id); ?>"><?php echo $ticket->subject; ?></a>
								<?php
								if($agent->id != $ticket->staffid){
									$show_flag = 1;
									?>
									<font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font>
								<?php } ?>
								</td>
								<td >
									<span class="js-support-ticket-table-responsive-heading">
										<?php echo __('Status','js-support-ticket'); ?> :
									</span>
									<?php
							            // 0 -> New Ticket
							            // 1 -> Waiting admin/staff reply
							            // 2 -> in progress
							            // 3 -> waiting for customer reply
							            // 4 -> close ticket
										$status = '';
										switch($ticket->status){
											case 0:
												$status = '<font color="#1EADD8">'.__('New','js-support-ticket').'</font>';
												if($ticket->isoverdue == 1)
													$status = '<font color="#DB624C">'.__('Overdue','js-support-ticket').'</font>';
											break;
											case 1:
												$status = '<font color="#D98E11">'.__('Pending','js-support-ticket').'</font>';
												if($ticket->isoverdue == 1)
													$status = '<font color="#DB624C">'.__('Overdue','js-support-ticket').'</font>';
											break;
											case 2:
												$status = '<font color="#D98E11">'.__('In Progress','js-support-ticket').'</font>';
												if($ticket->isoverdue == 1)
													$status = '<font color="#DB624C">'.__('Overdue','js-support-ticket').'</font>';
											break;
											case 3:
												$status = '<font color="#179650">'.__('Answered','js-support-ticket').'</font>';
												if($ticket->isoverdue == 1)
													$status = '<font color="#DB624C">'.__('Overdue','js-support-ticket').'</font>';
											break;
											case 4:
												$status = '<font color="#5F3BBB">'.__('Closed','js-support-ticket').'</font>';
											break;
											case 5:
												$status = '<font color="#5F3BBB">'.__('Merged and closed','js-support-ticket').'</font>';
											break;
										}
										echo $status;
									?>
								</td>
								<td>
									<span class="js-support-ticket-table-responsive-heading"><?php echo __('Priority','js-support-ticket'); ?> :</span>
									<span style="background-color:<?php echo $ticket->prioritycolour; ?>;" class="js-tkt-rep-prty"><?php echo __($ticket->priority,'js-support-ticket'); ?></span>
								</td>
								<td >
									<span class="js-support-ticket-table-responsive-heading"><?php echo __("Created","js-support-ticket");?>: </span>
									<?php echo date_i18n(jssupportticket::$_config['date_format'],strtotime($ticket->created)); ?>
								</td>
								<?php if(in_array('feedback', jssupportticket::$_active_addons)){ ?>
									<td >
										<span class="js-support-ticket-table-responsive-heading"> <?php echo __('Rating','js-support-ticket'); ?> : </span>
										<?php if($ticket->rating > 0){ ?>
											<span style="color:<?php echo $rating_color; ?>;font-weight:bold;font-size:16px;" > <?php echo $ticket->rating;?></span>
											<?php echo __('Out of','js-support-ticket').'<span style="font-weight:bold;font-size:15px;" >&nbsp;5</span>';
										}else{
											echo 'NA';
										} ?>
									</td>
								<?php } ?>
								<?php if(in_array('timetracking', jssupportticket::$_active_addons)){ ?>
									<td >
										<span class="js-support-ticket-table-responsive-heading"><?php echo __('Time Taken','js-support-ticket'); ?> : </span>
										<?php echo $avgtime; ?>
									</td>
								<?php } ?>
							</tr>
						<?php
						} ?>
					</table>
				</div>
					<?php if($show_flag == 1){ ?>
						<div class="js-form-button">
				        <?php echo '<font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font>'.__('Tickets not assigned to the staff member','js-support-ticket'); ?>
				        </div>
			        <?php } ?>

					<?php
				    if (jssupportticket::$_data[1]) {
				        echo '<div class="tablenav"><div class="tablenav-pages">' . jssupportticket::$_data[1] . '</div></div>';
				    }
				}
				?>
			</div>
		</div>
	</div>
