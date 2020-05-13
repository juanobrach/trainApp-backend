<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', $protocol.'ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
$id = JSSTrequest::getVar('id');
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.custom_date').datepicker({
            dateFormat: 'yy-mm-dd'
        });
	});
	google.setOnLoadCallback(drawChart);

      function drawChart() {
      	var data = new google.visualization.DataTable();
		data.addColumn('date', '<?php echo __('Dates','js-support-ticket'); ?>');
        data.addColumn('number', '<?php echo __('Minutes','js-support-ticket'); ?>');
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
?>
<div id="jsstadmin-wrapper">
    <div id="jsstadmin-leftmenu">
        <?php  JSSTincluder::getClassesInclude('jsstadminsidemenu'); ?>
    </div>
    <div id="jsstadmin-data">
    <span class="js-adminhead-title"> <a class="jsanchor-backlink" href="<?php echo admin_url('admin.php?page=reports&jstlay=staffdetailreport&id='.$id);?>"><img alt="image" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/back-icon.png" /></a> <span class="jsheadtext"><?php echo __("Report By Staff Member", 'js-support-ticket') ?></span>
    </span>
    <a href="<?php echo admin_url('admin.php?page=reports&jstlay=staffreport&date_start='.jssupportticket::$_data['filter']['date_start'].'&date_end='.jssupportticket::$_data['filter']['date_end']); ?>"></a>
    <form class="js-filter-form js-report-form" name="jssupportticketform" id="jssupportticketform" method="post" action="<?php echo admin_url("admin.php?page=reports&jstlay=stafftimereport&id=".$id); ?>">
        <?php
            $curdate = date_i18n('Y-m-d');
            $enddate = date_i18n('Y-m-d', strtotime("now -1 month"));
            $date_start = !empty(jssupportticket::$_data['filter']['date_start']) ? jssupportticket::$_data['filter']['date_start'] : $curdate;
            $date_end = !empty(jssupportticket::$_data['filter']['date_end']) ? jssupportticket::$_data['filter']['date_end'] : $enddate;
        	echo JSSTformfield::text('date_start', $date_start, array('class' => 'custom_date','placeholder' => __('Start Date','js-support-ticket')));
        	echo JSSTformfield::text('date_end', $date_end, array('class' => 'custom_date','placeholder' => __('End Date','js-support-ticket')));
        	echo JSSTformfield::hidden('JSST_form_search', 'JSST_SEARCH');
    	?>
        <?php echo JSSTformfield::submitbutton('go', __('Search', 'js-support-ticket'), array('class' => 'button')); ?>
    	<?php echo JSSTformfield::button('reset', __('Reset', 'js-support-ticket'), array('class' => 'button', 'onclick' => 'resetFrom();')); ?>
    </form>
    <span class="js-admin-subtitle"><?php echo jssupportticket::$_data[0]['staffname']; ?></span>
    <div id="curve_chart" style="height:400px;width:98%; "></div>
  </div>
</div>
