<?php
function wpjobster_get_graph($uid,$disp_type='all',$select_year='',$select_month='',$graph_page='my_account'){
	$type =$disp_type=$disp_type==''?'all':$disp_type;
	$year = $select_year = $select_year==''?date("Y"):$select_year;
	$month = $select_month = $select_month==''?date("m"):$select_month;
	if($type=='all'){
		$from_number = 2011;
		$to_number = date("Y")+1;
	}elseif($type=='year'){
		$from_number = 01;
		if( $select_year==date('Y')){
			$to_number = date('m');
		}else{
			$to_number = 12;
		}
	}elseif($type=='month'){
			$from_number = 01;
			$months_days=array(1=>31,2=>28,3=>31,4=>30,5=>31,6=>30,7=>31,8=>31,9=>30,10=>31,11=>30,12=>31);

		if( $select_month==date('m') && $select_year ==date('Y')){
			$to_number = date('d');

		}else{
			$to_number = $months_days[$month];
			if($year%4==0 && $month==2){
				$to_number=29;
			}
		}
	}elseif($type=='lastweek'){
		$from_number = 00;
		$to_number = 07;
	}

	for($i=$from_number;$i<=$to_number;$i++){
		if($type == 'all'){
			$result_month[$i]=wpjobster_get_unix_timestamp("$i-01-01");
		}
		if($type=='year'){
			$result_month[$i]=wpjobster_get_unix_timestamp("$year-$i-01");
		}
		if($type=='month'){
			$result_month[$i]=wpjobster_get_unix_timestamp("$year-$month-$i");
		}
		if($type=='lastweek'){
			$todays_date=date_create(date("Y-m-d"));
			date_add($todays_date,date_interval_create_from_date_string("-{$i} days"));
			 $dt = date_format($todays_date,"Y-m-d");
			$week_arr[$i]=$dt;
			$result_month[$i]=wpjobster_get_unix_timestamp($dt);
		}
		if($i==$from_number){
			if($type=='lastweek'){
				$todays_date=date_create(date("Y-m-d"));
				date_add($todays_date,date_interval_create_from_date_string("-{$to_number} days"));
				$dt = date_format($todays_date,"Y-m-d");
				//$week_arr[$i]=$dt;
				$first_balance_timestamp=wpjobster_get_unix_timestamp($dt);
				$first_credit_balance = get_previous_credit_balance($uid,$first_balance_timestamp);
			}else{
				$first_credit_balance = get_previous_credit_balance($uid,$result_month[$i]);

			}
		}
		if($i>$from_number){
			if($type=='lastweek'){
				$result_earned[$i] = gettrasaction_amt($result_month[$i],$result_month[($i-1)],$uid);
				$result_active[$i] = gettrasaction_amt($result_month[$i],$result_month[($i-1)],$uid,'active');
				$result_completed[$i] = gettrasaction_amt($result_month[$i],$result_month[($i-1)],$uid,'completed');
				$result_withdraw[$i] = gettrasaction_amt($result_month[$i],$result_month[($i-1)],$uid,'withdraw');
				$result_pending[$i] = gettrasaction_amt($result_month[$i],$result_month[($i-1)],$uid,'pending_clearance');
				$result_credit_balance[$i] = gettrasaction_amt($result_month[$i],$result_month[($i-1)],$uid,'credit_balance');
			}else{
				$result_earned[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid);
				$result_active[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'active');
				$result_completed[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'completed');
				$result_withdraw[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'withdraw');
				$result_pending[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'pending_clearance');
				$result_credit_balance[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'credit_balance');
			}
		}
	}

	if($type=='year'){
		$result_month[$i]=wpjobster_get_unix_timestamp(($year+1)."-01-01");
		$result_earned[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid);
		$result_active[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'active');
		$result_completed[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'completed');
		$result_withdraw[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'withdraw');
		$result_pending[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'pending_clearance');
		$result_credit_balance[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'credit_balance');
	}

	if($type=='month'){
		$result_month[$i]=wpjobster_get_unix_timestamp(($year+1)."-01-01");
		$result_earned[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid);
		$result_active[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'active');
		$result_completed[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'completed');
		$result_withdraw[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'withdraw');
		$result_pending[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'pending_clearance');
		$result_credit_balance[$i-1] = gettrasaction_amt($result_month[($i-1)],$result_month[$i],$uid,'credit_balance');
	}

	$data_table='';
	$element_count = 0;
	$data_table_arr[]=array("$disp_type", __('Earned', 'wpjobster'), __('Active Orders', 'wpjobster'), __('Completed Orders', 'wpjobster'), __('Credit Balance', 'wpjobster'));
	if($type=='lastweek'){
		$todays_date=date_create(date("Y-m-d"));
		date_add($todays_date,date_interval_create_from_date_string("+1 days"));
		$dt = date("Y-m-d");
		$result_month[-1]=wpjobster_get_unix_timestamp($dt);
		$week_arr[-1]=$dt;
		$result_earned[-1] = gettrasaction_amt($result_month[0],$result_month[-1],$uid);
		$result_active[-1] = gettrasaction_amt($result_month[0],$result_month[-1],$uid,'active');
		$result_completed[-1] = gettrasaction_amt($result_month[0],$result_month[-1],$uid,'completed');
		$result_withdraw[-1] = gettrasaction_amt($result_month[(0)],$result_month[-1],$uid,'withdraw');
		$result_pending[-1] = gettrasaction_amt($result_month[0],$result_month[-1],$uid,'pending_clearance');
		$result_credit_balance[-1] = gettrasaction_amt($result_month[0],$result_month[-1],$uid,'credit_balance');

		$from_number=-1;
		$key_count=0;

		$result_earned2 =$result_earned;
		$result_active2 = $result_active;
		$result_completed2 = $result_completed;
		$result_credit_balance2 = $result_credit_balance;
		$result_earned =array();
		$result_active = array();
		$result_completed =array();
		$result_credit_balance =array();
		for($i=$to_number;$i>=$from_number;$i--){
			if(isset($result_earned2[$i-1]) ){
				$result_earned[] =$result_earned2[$i-1];
				$result_active[] = $result_active2[$i-1];
				$result_completed[] = $result_completed2[$i-1];
				$result_credit_balance[] = $result_credit_balance2[$i-1];
				$week_arr2[]=$week_arr[$i-1];
				$key_count++;
			}
		}
		foreach($result_credit_balance as $key_c => $res_credit_bal){
			if(isset($res_credit_bal->sum_amount)){
				$first_credit_balance = $res_credit_bal;
			}else{
				$result_credit_balance[$key_c] = $first_credit_balance;
			}
		}

		for($i=0;$i<$key_count;$i++){
			$key = $i;$element_count++;

			$data_table.= "['{$week_arr2[$key]}',";
			$data_table.= (isset($result_earned[$key]->sum_amount)&&$result_earned[$key]->sum_amount?$result_earned[$key]->sum_amount:'0') .", ";
			if($graph_page!='sales' && $graph_page!='my_account' ){
				$data_table.=         (isset($result_active[$key]->sum_amount)&&$result_active[$key]->sum_amount!=''?$result_active[$key]->sum_amount:'0') .",";
			}
			if($graph_page!='sales' ){
				$data_table.= (isset($result_completed[$key]->sum_amount)&&$result_completed[$key]->sum_amount!=''?$result_completed[$key]->sum_amount:'0') .",";
			}
			if($graph_page!='shopping' ){
				$data_table.= (isset($result_withdraw[$key]->sum_amount)&&$result_withdraw[$key]->sum_amount!=''?$result_withdraw[$key]->sum_amount:'0') .",";
			}
			if($graph_page!='shopping' ){
				$data_table.= (isset($result_pending[$key]->sum_amount)&&$result_pending[$key]->sum_amount!=''?$result_pending[$key]->sum_amount:'0') .",";
			}
			$data_table.= (isset($result_credit_balance[$key]->sum_amount)&&$result_credit_balance[$key]->sum_amount!=''?$result_credit_balance[$key]->sum_amount:'0') ."],";

			unset($tmp_arr);
				$tmp_arr[]=$key;
				$tmp_arr[]=    (isset($result_earned[$key]->sum_amount)&&$result_earned[$key]->sum_amount?$result_earned[$key]->sum_amount:'0');
			if($graph_page!='sales' && $graph_page!='my_account' ){
				$tmp_arr[]=    (isset($result_active[$key]->sum_amount)&&$result_active[$key]->sum_amount!=''?$result_active[$key]->sum_amount:'0');
			}
			if($graph_page!='sales' ){
				$tmp_arr[]=    (isset($result_completed[$key]->sum_amount)&&$result_completed[$key]->sum_amount!=''?$result_completed[$key]->sum_amount:'0');
			}
			if($graph_page!='shopping' ){
				$tmp_arr[]=    (isset($result_withdraw[$key]->sum_amount)&&$result_withdraw[$key]->sum_amount!=''?$result_withdraw[$key]->sum_amount:'0');
			}
			if($graph_page!='shopping' ){
				$tmp_arr[]=    (isset($result_pending[$key]->sum_amount)&&$result_pending[$key]->sum_amount!=''?$result_pending[$key]->sum_amount:'0') ;
			}
			$tmp_arr[]=    (isset($result_credit_balance[$key]->sum_amount)&&$result_credit_balance[$key]->sum_amount!=''?$result_credit_balance[$key]->sum_amount:'0');
			$data_table_arr[] = $tmp_arr;
		}
	}else{
		foreach($result_credit_balance as $key_c => $res_credit_bal){
			if(isset($res_credit_bal->sum_amount)){
				$first_credit_balance = $res_credit_bal;
			}else{
				$result_credit_balance[$key_c] = $first_credit_balance;
			}
		}
		for($i=$from_number;$i<=$to_number;$i++){
			$key = $i;$element_count++;
			if($type=='all' && $i==$to_number){
				// do nothing
			}else{
				$data_table.= "['$key',";
				$data_table.= (isset($result_earned[$key]->sum_amount)&&$result_earned[$key]->sum_amount?$result_earned[$key]->sum_amount:'0') .", ";
				if($graph_page!='sales' && $graph_page!='my_account' ){
					$data_table.=         (isset($result_active[$key]->sum_amount)&&$result_active[$key]->sum_amount!=''?$result_active[$key]->sum_amount:'0') .",";
				}
				if($graph_page!='sales' ){
					$data_table.=         (isset($result_completed[$key]->sum_amount)&&$result_completed[$key]->sum_amount!=''?$result_completed[$key]->sum_amount:'0') .",";
				}
				if($graph_page!='shopping' ){
					$data_table.=         (isset($result_withdraw[$key]->sum_amount)&&$result_withdraw[$key]->sum_amount!=''?$result_withdraw[$key]->sum_amount:'0') .",";
				}
				if($graph_page!='shopping' ){
					$data_table.=         (isset($result_pending[$key]->sum_amount)&&$result_pending[$key]->sum_amount!=''?$result_pending[$key]->sum_amount:'0') .",";
				}
				$data_table.=         (isset($result_credit_balance[$key]->sum_amount)&&$result_credit_balance[$key]->sum_amount!=''?$result_credit_balance[$key]->sum_amount:'0') ."],";
				$tmp_arr[]=$key;
				$tmp_arr[]=    (isset($result_earned[$key]->sum_amount)&&$result_earned[$key]->sum_amount?$result_earned[$key]->sum_amount:'0');
				if($graph_page!='sales' && $graph_page!='my_account' ){
					$tmp_arr[]=    (isset($result_active[$key]->sum_amount)&&$result_active[$key]->sum_amount!=''?$result_active[$key]->sum_amount:'0');
				}
				if($graph_page!='sales' ){
					$tmp_arr[]=    (isset($result_completed[$key]->sum_amount)&&$result_completed[$key]->sum_amount!=''?$result_completed[$key]->sum_amount:'0');
				}
				if($graph_page!='shopping' ){
					$tmp_arr[]=    (isset($result_withdraw[$key]->sum_amount)&&$result_withdraw[$key]->sum_amount!=''?$result_withdraw[$key]->sum_amount:'0');
				}
				if($graph_page!='shopping' ){
					$tmp_arr[]=    (isset($result_pending[$key]->sum_amount)&&$result_pending[$key]->sum_amount!=''?$result_pending[$key]->sum_amount:'0') ;
				}
				$tmp_arr[]=    (isset($result_credit_balance[$key]->sum_amount)&&$result_credit_balance[$key]->sum_amount!=''?$result_credit_balance[$key]->sum_amount:'0');
				$data_table_arr[] = $tmp_arr;
			}
		}
	}

	$data_table2=$data_table;
	$data_table = "[";
	if($graph_page=='shopping'){
		$data_table         .= "['".$disp_type."', '".__('Total spent', 'wpjobster')."', '".__('Active Orders', 'wpjobster')."','".__('Completed Orders', 'wpjobster')."','".__('Current Balance', 'wpjobster')."'],".trim($data_table2,",")."]";
	}elseif($graph_page=='sales'){
		$data_table         .= "['".$disp_type."', '".__('Earned', 'wpjobster')."', '".__('Withdrawal', 'wpjobster')."','".__('Pending Clearance', 'wpjobster')."','".__('Available Funds', 'wpjobster')."'],".trim($data_table2,",")."]";
	}else{
		$data_table         .= "['".$disp_type."', '".__('Earned', 'wpjobster')."', '".__('Completed Orders', 'wpjobster')."','".__('Withdrawal', 'wpjobster')."','".__('Pending Clearance', 'wpjobster')."','".__('Available Funds', 'wpjobster')."'],".trim($data_table2,",")."]";
	}
	$data_table_ajax = "['".$disp_type."', '".__('Earned', 'wpjobster')."', '".__('Active Orders', 'wpjobster')."','".__('Completed Orders', 'wpjobster')."','".__('Withdrawal', 'wpjobster')."','".__('Credit Balance', 'wpjobster')."'],".trim($data_table2,",");

	return array(
		"data_table"=>$data_table,
		"type"=>$type,
		"disp_type"=>$disp_type,
		"select_month"=>$select_month,
		"select_year"=>$select_year,
		"total_elements"=>$element_count,
		"data_table_arr"=>$data_table_arr,
		"data_table_ajax"=>$data_table_ajax
	);
}// function wpjobster graph

function wpjobster_get_graph_ajax(){
	$uid = $_REQUEST['uid'];
	$disp_type = $_REQUEST['disp_type'];
	$graph_page = $_REQUEST['graph_page'];
	$select_year = isset($_REQUEST['select_year'])?$_REQUEST['select_year']:"";
	$select_month = isset($_REQUEST['select_month'])?$_REQUEST['select_month']:"";
	$graph_data = wpjobster_get_graph($uid,$disp_type,$select_year,$select_month,$graph_page);
	extract($graph_data);

	echo json_encode($graph_data);

	die();
}

if(!function_exists('wpjobster_show_graph_controls')){
	function wpjobster_show_graph_controls($disp_type,$select_year,$select_month){ ?>

		<div class="cf">
			<div class="chart-controls" style="">
				<div class="cf right">
					<div class="graph-button">
					<input type="radio" name="disp_type" id="radio1" value="all" <?php echo ($disp_type=='all'?'checked':'');?> class="radio" />
					<label for="radio1"><?php _e('All', 'wpjobster'); ?></label>
					</div>

					<div class="graph-button">
					<input type="radio" name="disp_type" id="radio2" value="year"  <?php echo ($disp_type=='year'?'checked':'');?>  class="radio"/>
					<label for="radio2"><?php _e('Year', 'wpjobster'); ?></label>
					</div>

					<div class="graph-button">
					<input type="radio" name="disp_type" id="radio3" value="month"  <?php echo ($disp_type=='month'?'checked':'');?>  class="radio"/>
					<label for="radio3"><?php _e('Month', 'wpjobster'); ?></label>
					</div>

					<div class="graph-button">
					<input type="radio" name="disp_type" id="radio4"  value="lastweek"  <?php echo ($disp_type=='lastweek'?'checked':'');?> class="radio"/>
					<label for="radio4"><?php _e('Last week', 'wpjobster'); ?></label>
					</div>
				</div>
				<div class="cf left">
					<form action="" id="form_graph" class="hidden">
					<div class="graph-options borderselect" id="select_year" >
						<input type="hidden" name="disp_type" id="disp_type" value="<?php echo $disp_type;?>">
						<?php
						$current_year = date('Y');
						$start_from = 2011;
						$year_arr = range($start_from,$current_year);
						$year_arr = array_combine($year_arr,$year_arr);
						$year_dropdown = wpjobster_get_option_drop_down($year_arr,'select_year',$select_year,' class="grey_input white lighter styledselect" ');
						echo $year_dropdown;
						?>
					</div>
					<div class="graph-options borderselect" id="select_month" class="hidden">
						<?php
						$end_month = 12;

						$start_month = 01;
						$month_arr = range($start_month,$end_month);
						$month_arr = array_combine($month_arr,$month_arr);
						$month_dropdown = wpjobster_get_option_drop_down($month_arr,'select_month',$select_month,' class="grey_input white lighter styledselect" ');
						echo $month_dropdown;
						?>
					</div>

						<div class="graph-options" ><input class="btn"  type="button" onclick="get_wpjobster_graph();" value="<?php _e("Go","wpjobster");?>" style="margin-top:0px;"></div>
					</form>
				</div>
			</div>
		</div>

		<div id="chart_div" style="width: 100%; height: 300px; display: none;"></div>
	<?php }
}

if(!function_exists('wpjobster_show_graph')){
	function wpjobster_show_graph($data_table,$uid,$type,$graph_page){

		$type_translate = array(
			'all'       => __('All', 'wpjobster'),
			'year'      => __('Year', 'wpjobster'),
			'month'     => __('Month', 'wpjobster'),
			'lastweek'  => __('Last week', 'wpjobster'),
		);

		if ( is_rtl() ) {
			$code_for_rtl = "reverseAxis: true, legend: { position: 'top' },";
		} else {
			$code_for_rtl = '';
		}
		?>

		<script>

		dt_table = <?php echo $data_table;?>;
		graph_page = '<?php echo $graph_page; ?>';

		function get_wpjobster_graph(){
			// We'll pass this variable to the PHP function example_ajax_request
			graph_page = '<?php echo $graph_page; ?>';
			// This does the ajax request
			disp_type  = $("#disp_type").val();
			select_year  = $("#select_year select[name=select_year]").val();
			select_month  = $("#select_month select[name=select_month]").val();

			$.ajax({
				url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
				data: {
					'uid':'<?php echo $uid;?>',
					'action':'get_wpjobster_graph',
					'disp_type' : disp_type,
					'select_year' : select_year,
					'select_month' : select_month,
					'graph_page':graph_page

				},
				success:function(json_data) {
					chart_data = JSON.parse(json_data);

					$chart_data2 = eval(chart_data['data_table']);
					//$chart_data2 = chart_data['data_table'];
					data = google.visualization.arrayToDataTable($chart_data2);
					// chart_type=chart_data['disp_type'];

					drawChart();
					console.log(data);
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});
		}

		jQuery(document).ready(function($){
			$(".graph-button").click(function(){
				$("#disp_type").val($("input[name=disp_type]:checked").val());
				if($("#disp_type").val()=='lastweek' || $("#disp_type").val()=='all'){
					$("#form_graph").removeClass("hidden");
					$("#form_graph").addClass("hidden");
					$("#select_month").removeClass("hidden");
					$("#select_month").addClass("hidden");
					get_wpjobster_graph();
				}else if($("#disp_type").val()=='month'){
					$("#form_graph").removeClass("hidden");
					$("#select_month").removeClass("hidden");
				}else if($("#disp_type").val()=='year'){
					$("#form_graph").removeClass("hidden");
					$("#select_month").removeClass("hidden");
					$("#select_month").addClass("hidden");
				}
			});
		});

		jQuery(document).ready(function($) {
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);
		});

		function drawChart() {
			if(typeof data=='undefined'){
				data = google.visualization.arrayToDataTable(dt_table);
			}
			if (graph_page == 'my_account') {
				var options = {
					hAxis: {
						titleTextStyle: {color: '#333'}
					},
					vAxis: {minValue: 0},<?php echo $code_for_rtl; ?>

					colors: ['#BEEC78', '#119EE2', '#FFBE00', '#65BEE9', '#83C124']
				};
			} else if (graph_page == 'sales') {
				var options = {
					hAxis: {
						titleTextStyle: {color: '#333'}
					},
					vAxis: {minValue: 0},<?php echo $code_for_rtl; ?>
					colors: ['#BEEC78', '#FFBE00', '#65BEE9', '#83C124', '#83C124']
				};
			} else if (graph_page == 'shopping') {
				var options = {
					hAxis: {
						titleTextStyle: {color: '#333'}
					},
					vAxis: {minValue: 0},<?php echo $code_for_rtl; ?>
					colors: ['#BEEC78', '#FFBE00', '#119EE2', '#83C124', '#83C124']
				};
			} else {
				var options = {
					hAxis: {
						titleTextStyle: {color: '#333'}
					},
					vAxis: {minValue: 0},<?php echo $code_for_rtl; ?>

					colors: ['#83C124', '#83C0D8', '#03C0D8', '#f3CaD8', '#8fCbD8']
				};
			}
			var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
			chart.draw(data, options);
		}
		</script>
	<?php }
}
