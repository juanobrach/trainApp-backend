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
	jQuery(document).ready(function(){
		jQuery('.custom_date').datepicker({
            dateFormat: '<?php echo $js_scriptdateformat; ?>'
        });
		google.setOnLoadCallback(drawChart);
	});

	function resetFrom(){
		document.getElementById('date_start').value = '';
		document.getElementById('date_end').value = '';
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
	// function resizeCharts () {
	//     // redraw charts, dashboards, etc here
	//     chart.draw(data, options);
	// }
	// jQuery(window).resize(resizeCharts);
</script>
<?php JSSTmessage::getMessage(); ?>
<?php
$t_name = 'getdepartmentexport';
$link_export = admin_url('admin.php?page=export&task='.$t_name.'&action=jstask&date_start='.jssupportticket::$_data['filter']['date_start'].'&date_end='.jssupportticket::$_data['filter']['date_end']);
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
		                <li><?php echo __('Department Reports','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __("Department Reports", 'js-support-ticket') ?></h1>
            <?php if(in_array('export', jssupportticket::$_active_addons)){ ?>
	  			<a title="<?php echo __('Export Data','js-support-ticket'); ?>" id="jsexport-link" class="jsstadmin-add-link button" href="<?php echo $link_export; ?>"><img alt="<?php echo __('Export','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/export-icon.png" /><?php echo __('Export Data', 'js-support-ticket'); ?></a>
	  		<?php } ?>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
        	<form class="js-filter-form js-report-form" name="jssupportticketform" id="jssupportticketform" method="post" action="<?php echo admin_url("admin.php?page=reports&jstlay=departmentreport"); ?>">
			    <?php
			        $curdate = date_i18n('Y-m-d');
			        $enddate = date_i18n('Y-m-d', strtotime("now -1 month"));
			        $date_start = !empty(jssupportticket::$_data['filter']['date_start']) ? jssupportticket::$_data['filter']['date_start'] : $curdate;
			        $date_end = !empty(jssupportticket::$_data['filter']['date_end']) ? jssupportticket::$_data['filter']['date_end'] : $enddate;
			        $uid = !empty(jssupportticket::$_data['filter']['uid']) ? jssupportticket::$_data['filter']['uid'] : '';
			    	echo JSSTformfield::text('date_start', date_i18n(jssupportticket::$_config['date_format'], strtotime($date_start)), array('class' => 'custom_date js-form-date-field','placeholder' => __('Start Date','js-support-ticket')));
			    	echo JSSTformfield::text('date_end', date_i18n(jssupportticket::$_config['date_format'], strtotime($date_end)), array('class' => 'custom_date js-form-date-field','placeholder' => __('End Date','js-support-ticket')));
			    	echo JSSTformfield::hidden('JSST_form_search', 'JSST_SEARCH');
				?>
			    <?php echo JSSTformfield::submitbutton('go', __('Search', 'js-support-ticket'), array('class' => 'button js-form-search')); ?>
				<?php echo JSSTformfield::button('reset', __('Reset', 'js-support-ticket'), array('class' => 'button js-form-reset', 'onclick' => 'resetFrom();')); ?>
			</form>
			<div class="js-admin-report">
				<div class="js-admin-subtitle"><?php echo __('Overall Report','js-support-ticket'); ?></div>
				<div class="js-admin-rep-graph" id="curve_chart" style="height:400px;width:98%; "></div>
			</div>
			<div class="js-admin-report">
				<div class="js-admin-subtitle"><?php echo __('Departments','js-support-ticket'); ?></div>
				<div class="js-admin-staff-list">
			<?php
			if(!empty(jssupportticket::$_data['depatments_report'])){
				foreach(jssupportticket::$_data['depatments_report'] AS $dept){ ?>
					<div class="js-admin-staff-wrapper dept-reprt">
						<a href="<?php echo admin_url('admin.php?page=reports&jstlay=departmentdetailreport&id='.$dept->id.'&date_start='.jssupportticket::$_data['filter']['date_start'].'&date_end='.jssupportticket::$_data['filter']['date_end']); ?>" class="js-admin-staff-anchor-wrapper" title="<?php echo __('Department','js-support-ticket'); ?>">
							<div class="js-admin-staff-cnt">
								<div class="js-report-staff-cnt">
									<div class="js-report-staff-info js-report-staff-name">
										<?php
											echo __($dept->departmentname,'js-support-ticket');
										?>
									</div>
									<div class="js-report-staff-info js-report-staff-email">
										<?php
											echo $dept->email;
										?>
									</div>
								</div>
							</div>
							<div class="js-admin-staff-boxes">
								<?php
									$open_percentage = 0;
									$close_percentage = 0;
									$answered_percentage = 0;
									$pending_percentage = 0;
									$overdue_percentage = 0;
									if(isset($dept) && isset($dept->allticket) && $dept->allticket != 0){
									    $open_percentage = round(($dept->openticket / $dept->allticket) * 100);
									    $close_percentage = round(($dept->closeticket / $dept->allticket) * 100);
									    $overdue_percentage = round(($dept->overdueticket / $dept->allticket) * 100);
									    $answered_percentage = round(($dept->answeredticket / $dept->allticket) * 100);
									    $pending_percentage = round(($dept->pendingticket / $dept->allticket) * 100);
									}
									if(isset($dept) && isset($dept->allticket) && $dept->allticket != 0){
									    $allticket_percentage = 100;
									}
								?>
								<div class="js-ticket-count">
								    <div class="js-ticket-link">
								        <a class="js-ticket-link js-ticket-green" href="#" data-tab-number="1" title="<?php echo __('Open Ticket','js-support-ticket'); ?>">
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
								                    echo ' ( '.$dept->openticket.' )';
								                ?>
								            </div>
								        </a>
								    </div>
								    <div class="js-ticket-link">
								        <a class="js-ticket-link js-ticket-brown" href="#" data-tab-number="2" title="<?php echo __('answered ticket','js-support-ticket'); ?>">
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
								                    echo ' ( '. $dept->answeredticket.' )';
								                ?>
								            </div>
								        </a>
								    </div>
								    <div class="js-ticket-link">
					                    <a class="js-ticket-link js-ticket-blue" href="#" data-tab-number="3" title="<?php echo __('pending ticket','js-support-ticket'); ?>">
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
					                                echo ' ( '. $dept->pendingticket.' )';
					                            ?>
					                        </div>
					                    </a>
					                </div>
								    <div class="js-ticket-link">
								        <a class="js-ticket-link js-ticket-orange" href="#" data-tab-number="4" title="<?php echo __('overdue ticket','js-support-ticket'); ?>">
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
								                    echo ' ( '. $dept->overdueticket.' )';
								                ?>
								            </div>
								        </a>
								    </div>
								    <div class="js-ticket-link">
								        <a class="js-ticket-link js-ticket-red" href="#" data-tab-number="5" title="<?php echo __('Close Ticket','js-support-ticket'); ?>">
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
								                    echo ' ( '. $dept->closeticket.' )';
								                ?>
								            </div>
								        </a>
								    </div>
								</div>
							</div>
						</a>
					</div>
				<?php
				}
				?>
				</div>
			</div>
				<?php
			    if (jssupportticket::$_data[1]) {
			        echo '<div class="tablenav"><div class="tablenav-pages">' . jssupportticket::$_data[1] . '</div></div>';
			    }
			}
			?>
		</div>
	</div>
</div>
