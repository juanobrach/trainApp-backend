<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', $protocol.'ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
wp_enqueue_style('status-graph', jssupportticket::$_pluginpath . 'includes/css/status_graph.css');
wp_enqueue_script('google-api','https://www.google.com/jsapi?autoload={\'modules\':[{\'name\':\'visualization\',\'version\':\'1\',\'packages\':[\'corechart\']}]}');
?>
<!-- <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script> -->
<script type="text/javascript">
    jQuery(document).ready(function ($) {

	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawBarChart);
	function drawBarChart() {
		var data = google.visualization.arrayToDataTable([
         ['<?php echo __('Status','js-support-ticket'); ?>', '<?php echo __('Tickets By Status','js-support-ticket'); ?>', { role: 'style' }],
         <?php echo jssupportticket::$_data['bar_chart']; ?>
      ]);
     var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        //title: "Density of Precious Metals, in g/cm^3",
        width: '95%',
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("bar_chart"));
      chart.draw(view, options);
  	}

	google.setOnLoadCallback(drawStackChart);
    function drawStackChart() {
      var data = google.visualization.arrayToDataTable([
        ['<?php echo __('Tickets','js-support-ticket'); ?>', '<?php echo __('Direct','js-support-ticket'); ?>', '<?php echo __('Email','js-support-ticket'); ?>', { role: 'annotation' } ],
        <?php echo jssupportticket::$_data['stack_data']; ?>
      ]);

      var view = new google.visualization.DataView(data);
      var options = {
        width: '95%',
        //height: 400,
        legend: { position: 'top', maxLines: 3 },
        bar: { groupWidth: '75%' },
        isStacked: true,
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("stack_chart"));
      chart.draw(view, options);
  	}

	google.setOnLoadCallback(drawPie3d1Chart);
	function drawPie3d1Chart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo __('Departments','js-support-ticket'); ?>', '<?php echo __('Tickets By Department','js-support-ticket'); ?>'],
          <?php echo jssupportticket::$_data['pie3d_chart1']; ?>
        ]);

        var options = {
          title: '<?php echo __('Ticket by departments','js-support-ticket'); ?>',
          chartArea :{width:450,height:350,top:80,left:80},
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie3d_chart1'));
        chart.draw(data, options);
  	}

	google.setOnLoadCallback(drawPie3d2Chart);
	function drawPie3d2Chart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo __('Priorities','js-support-ticket'); ?>', '<?php echo __('Tickets By Priority','js-support-ticket'); ?>'],
          <?php echo jssupportticket::$_data['pie3d_chart2']; ?>
        ]);

        var options = {
          title: '<?php echo __('Tickets By Priorities','js-support-ticket'); ?>',
          chartArea :{width:450,height:350,top:80,left:80},
          is3D: true,
          colors:<?php echo jssupportticket::$_data['priorityColorList']; ?>
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie3d_chart2'));
        chart.draw(data, options);
  	}

	google.setOnLoadCallback(drawStackChartHorizontal);
    function drawStackChartHorizontal() {
      var data = google.visualization.arrayToDataTable([
      	<?php
      		echo jssupportticket::$_data['stack_chart_horizontal']['title'].',';
      		echo jssupportticket::$_data['stack_chart_horizontal']['data'];
      	?>
      ]);

      var view = new google.visualization.DataView(data);

      var options = {
        chartArea: {width:'90%'},
        legend: { position: 'top', maxLines: 3 },
        bar: { groupWidth: '75%' },
        isStacked: true,
        colors:['#ff652f','#5ab9ea','#d89922','#14a76c'],
      };
      var chart = new google.visualization.AreaChart(document.getElementById("stack_chart_horizontal"));
      chart.draw(view, options);
  	}
    <?php if(isset(jssupportticket::$_data['slice_chart'])) { ?>
    google.setOnLoadCallback(drawSliceChart);
    function drawSliceChart() {
      var data = google.visualization.arrayToDataTable([
        ['<?php echo __('Tickets','js-support-ticket'); ?>', '<?php echo __('Staff Member Tickets','js-support-ticket'); ?>'],
        <?php echo jssupportticket::$_data['slice_chart']; ?>
      ]);

      var options = {
        //title: 'Indian Language Use',
        //pieSliceText: 'label',
        legend : {position: 'none'},
        chartArea : {width: '80%',height:300},
        // slices: {
        //           2: {offset: 0.2},
        //           4: {offset: 0.3},
        //           5: {offset: 0.4},
        //           7: {offset: 0.5},
        //           9: {offset: 0.5},
        // },
      };

      var chart = new google.visualization.BarChart(document.getElementById('slice_chart'));
      chart.draw(data, options);
    }
    <?php } ?>
  });
</script>
<?php JSSTmessage::getMessage(); ?>
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
                        <li><?php echo __('Overall Statistics','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __("Overall Statistics", 'js-support-ticket') ?></h1>
            <?php if(in_array('export', jssupportticket::$_active_addons)){ ?>
                <a title="<?php echo __('Export Data', 'js-support-ticket'); ?>" id="jsexport-link" class="jsstadmin-add-link button" href="?page=export&task=getoverallexport&action=jstask"><img alt="<?php echo __('Export', 'js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/export-icon.png" /><?php echo __('Export Data', 'js-support-ticket'); ?></a>
            <?php } ?>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
            <?php
            $open_percentage = 0;
            $close_percentage = 0;
            $overdue_percentage = 0;
            $answered_percentage = 0;
            if(isset(jssupportticket::$_data['ticket_total']) && isset(jssupportticket::$_data['ticket_total']['allticket']) && jssupportticket::$_data['ticket_total']['allticket'] != 0){
                $open_percentage = round((jssupportticket::$_data['ticket_total']['openticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
                $close_percentage = round((jssupportticket::$_data['ticket_total']['closeticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
                $overdue_percentage = round((jssupportticket::$_data['ticket_total']['overdueticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
                $answered_percentage = round((jssupportticket::$_data['ticket_total']['answeredticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
            }
            if(isset(jssupportticket::$_data['ticket_total']) && isset(jssupportticket::$_data['ticket_total']['allticket']) && jssupportticket::$_data['ticket_total']['allticket'] != 0){
                $allticket_percentage = 100;
            }
            ?>
            <div class="js-ticket-count">
                <div class="js-ticket-link">
                    <a class="js-ticket-link js-ticket-green" href="#" data-tab-number="1" title="<?php echo __('Open Ticket','js-support-ticket'); ?>">
                        <div class="js-ticket-cricle-wrp" data-per="<?php echo $open_percentage; ?>" >
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
                                echo ' ( '.jssupportticket::$_data['ticket_total']['answeredticket'].' )';
                            ?>
                        </div>
                    </a>
                </div>
                <div class="js-ticket-link">
                    <a class="js-ticket-link js-ticket-orange" href="#" data-tab-number="3" title="<?php echo __('overdue ticket','js-support-ticket'); ?>">
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
                                echo ' ( '.jssupportticket::$_data['ticket_total']['overdueticket'].' )';
                            ?>
                        </div>
                    </a>
                </div>
                <div class="js-ticket-link">
                    <a class="js-ticket-link js-ticket-red" href="#" data-tab-number="4" title="<?php echo __('Close Ticket','js-support-ticket'); ?>">
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
                                echo ' ( '.jssupportticket::$_data['ticket_total']['closeticket'].' )';
                            ?>
                        </div>
                    </a>
                </div>
                <div class="js-ticket-link">
                    <a class="js-ticket-link js-ticket-blue" href="#" data-tab-number="5" title="<?php echo __('all ticket','js-support-ticket'); ?>">
                        <div class="js-ticket-cricle-wrp" data-per="<?php echo $allticket_percentage; ?>">
                            <div class="js-mr-rp" data-progress="<?php echo $allticket_percentage; ?>">
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
                                echo __('All Tickets', 'js-support-ticket');
                                echo ' ( '.jssupportticket::$_data['ticket_total']['allticket'].' )';
                            ?>
                        </div>
                    </a>
                </div>
            </div>
            <div class="js-admin-report">
                <div class="js-admin-subtitle"><?php echo __('Tickets By Status And Priorities','js-support-ticket'); ?></div>
                <div class="js-admin-rep-graph" id="stack_chart_horizontal" style="float:left; height:400px;width:100%; "></div>
            </div>
            <div class="js-admin-report halfwidth">
            	<div class="js-admin-subtitle box1"><?php echo __('Tickets By Departments','js-support-ticket'); ?></div>
            	<div class="js-admin-rep-graph" id="pie3d_chart1" style="height:400px;width:100%;"></div>
            </div>
            <div class="js-admin-report halfwidth">
            	<div class="js-admin-subtitle box2"><?php echo __('Tickets By Priorities','js-support-ticket'); ?></div>
            	<div class="js-admin-rep-graph" id="pie3d_chart2" style="height:400px;width:100%;"></div>
            </div>
            <div class="js-admin-report halfwidth">
            	<div class="js-admin-subtitle box3"><?php echo __('Tickets By Status','js-support-ticket'); ?></div>
            	<div class="js-admin-rep-graph" id="bar_chart" style="height:400px;width:100%;"></div>
            </div>
            <div class="js-admin-report halfwidth">
              <div class="js-admin-subtitle box4"><?php echo __('Tickets By Channel','js-support-ticket'); ?></div>
              <div class="js-admin-rep-graph" id="stack_chart" style="height:400px;width:100%;"></div>
            </div>
            <div class="js-admin-report">
            	<div class="js-admin-subtitle box4"><?php echo __('Tickets By Agents','js-support-ticket'); ?></div>
            	<div class="js-admin-rep-graph" id="slice_chart" style="height:400px;width:100%;"></div>
            </div>
        </div>
  </div>
</div>
