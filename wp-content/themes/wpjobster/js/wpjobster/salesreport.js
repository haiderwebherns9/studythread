// Sales report main tables/graph
jQuery(document).ready(function( $ ){

	jQuery("#form_sales_report").submit();

	sales_tab_action_arr = new Array();
	sales_tab_action_arr["tabs1"] = "summary_sales_report";
	sales_tab_action_arr["tabs2"] = "job_sales_report";
	sales_tab_action_arr["tabs3"] = "topup_sales_report";
	sales_tab_action_arr["tabs4"] = "featured_sales_report";
	sales_tab_action_arr["tabs5"] = "refund_sales_report";
	sales_tab_action_arr["tabs6"] = "withdrawal_sales_report";
	sales_tab_action_arr["tabs7"] = "custom_extra_sales_report";

	jQuery(".sales-report-title").click(function(){
		action_key = jQuery(this).attr('href');
		action_key = action_key.replace('#','');
		jQuery("#sales_report_action").val(sales_tab_action_arr[action_key]);
		jQuery("#form_sales_report").submit();
	});

	jQuery('#graph-range').on('change', function() {
		jQuery("#form_sales_report").submit();
	})

	var d = new Date();
	var month = d.getMonth()+1;
	var day = d.getDate();
	var dateNow = d.getFullYear() + '/' + ((''+month).length < 2 ? '0' : '') + month + '/' + ((''+day).length < 2 ? '0' : '') + day;

	jQuery('table').each(function() {
		jQuery('#btn-summary-csv').click(function( event ) {
			event.preventDefault();
			CSV.begin('#summary-table').download('wpjobster-summary-sales-report-' + dateNow + '.csv','','csv-separator').go();
		});

		jQuery('#btn-job-purchase-csv').click(function( event ) {
			event.preventDefault();
			CSV.begin('#job-purchase-table').download('wpjobster-job-purchase-sales-report-' + dateNow + '.csv').go();
		});

		jQuery('#btn-topup-csv').click(function( event ) {
			event.preventDefault();
			CSV.begin('#topup-table').download('wpjobster-topup-sales-report-' + dateNow + '.csv').go();
		});

		jQuery('#btn-featured-csv').click(function( event ) {
			event.preventDefault();
			CSV.begin('#featured-table').download('wpjobster-featured-sales-report-' + dateNow + '.csv').go();
		});

		jQuery('#btn-refund-csv').click(function( event ) {
			event.preventDefault();
			CSV.begin('#refund-table').download('wpjobster-refund-sales-report-' + dateNow + '.csv').go();
		});

		jQuery('#btn-withdrawal-csv').click(function( event ) {
			event.preventDefault();
			CSV.begin('#withdrawal-table').download('wpjobster-withdrawal-sales-report-' + dateNow + '.csv').go();
		});

		jQuery('#btn-custom-extra-csv').click(function( event ) {
			event.preventDefault();
			CSV.begin('#custom-extra-table').download('wpjobster-custom-extra-sales-report-' + dateNow + '.csv').go();
		});
	});

	jQuery("#form_sales_report").submit(function(){
		if(typeof all_report_data=='undefined'){
			action_key = 'tabs1';
			jQuery("#sales_report_action").val(sales_tab_action_arr[action_key]);
		}
		all_report_data = jQuery("#form_sales_report").serialize();
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: all_report_data,
			beforeSend: function() {
				jQuery("#"+action_key+" .report-table").html('<i class="report-loader notched circle loading icon"></i>');
				jQuery("#"+action_key+" .report-graph .graph-position").html('<i class="report-loader notched circle loading icon"></i>');
			},
			success: function (data) {
				obj = JSON.parse(data);
				jQuery("#"+action_key+" .report-table").html(obj.tableInfo);

				jQuery("#user_error_report").html(obj.userError);

				if(action_key == 'tabs2'){
					var vAxisTitle = base.amount;
					var hAxisTitle = base.date;
					var divName = 'job-report-graph-chart';
				} else if(action_key == 'tabs3'){
					var vAxisTitle = base.amount;
					var hAxisTitle = base.date;
					var divName = 'topup-report-graph-chart';
				} else if(action_key == 'tabs4'){
					var vAxisTitle = base.amount;
					var hAxisTitle = base.date;
					var divName = 'featured-report-graph-chart';
				} else if(action_key == 'tabs5'){
					var vAxisTitle = base.amount;
					var hAxisTitle = base.date;
					var divName = 'refunded-report-graph-chart';
				} else if(action_key == 'tabs6'){
					var vAxisTitle = base.amount;
					var hAxisTitle = base.date;
					var divName = 'withdrawal-report-graph-chart';
				} else if(action_key == 'tabs7'){
					var vAxisTitle = base.amount;
					var hAxisTitle = base.date;
					var divName = 'custom-extra-report-graph-chart';
				} else {
					var vAxisTitle = base.amount;
					var hAxisTitle = base.payment_type;
					var divName = 'summary-report-graph-chart';
				}

				google.charts.load('current', {'packages':['corechart']});
				google.charts.setOnLoadCallback(drawChart);

				function drawChart() {

					var space_str = "";
					if (base.space == "yes") {space_str = " ";}

					if (base.position == "front") {
						var vAxisFormat = base.symbol + space_str + '#,###';
						var toolTipFormat = 'prefix';
						var toolTipVal = base.symbol + space_str;
					} else {
						var vAxisFormat = '#,###' + space_str + base.symbol;
						var toolTipFormat = 'suffix';
						var toolTipVal = space_str + base.symbol;
					}

					var data = new google.visualization.DataTable(obj.graphInfo);
					var options = {
						vAxis: {title: vAxisTitle, format: vAxisFormat, textStyle : { fontSize: 12 } },
						hAxis: {title: hAxisTitle, format: '##/##/####', textStyle : { fontSize: 12 } },
						pointSize: 7,
						pointShape: 'square',
						seriesType: 'line',
						series: {
							0: { lineWidth: 3, type: 'line', color: '#208bee' },
							1: { lineWidth: 3, type: 'line', color: '#99c4f6' },
							2: { lineWidth: 3, type: 'line', color: '#e9463d' },
							3: { lineWidth: 3, type: 'line', color: '#009b5d' },
							4: { lineWidth: 3, type: 'line', color: '#a536ab' },
							5: { lineWidth: 3, type: 'line', color: '#ffe859' },
							6: { lineWidth: 3, type: 'line', color: '#141D36' },
							7: { lineWidth: 3, type: 'line', color: '#B5CBE9' },
							8: { lineWidth: 3, type: 'line', color: '#838EA1' },
							9: { lineWidth: 3, type: 'line', color: '#208bee' },
							10: { lineWidth: 3, type: 'line', color: '#99c4f6' },
						},
						legend: {position: 'bottom', alignment: 'center'},
						backgroundColor: {fill: '#f1f1f1'},
						chartArea: {
							left: 150,
							top: 10,
							width: 1980,
							height: 200
						}
					};

					var formatter = new google.visualization.NumberFormat( { [toolTipFormat] : toolTipVal } );
					var cols = obj.graphInfo.cols.length;
					for(i=1;i<cols;i++){
						formatter.format(data, i);
					}

					var chart = new google.visualization.ComboChart(document.getElementById(divName));
					chart.draw(data, options);
				}
			}
		});
	});
});

// New daterange picker initialisation
jQuery(document).ready(function($) {
	var cb = function(start, end, label) {
		$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	};

	var today = base.today;
	var yesterday = base.yesterday;
	var last_7_days = base.last_7_days;
	var last_30_days = base.last_30_days;
	var this_month = base.this_month;
	var last_month = base.last_month;

	var optionSet1 = {
		startDate: moment().subtract(29, 'days'),
		endDate: moment(),
		minDate: '01/01/1980',
		maxDate: '12/31/2015',
		dateLimit: {
			days: 60
		},
		showDropdowns: true,
		showWeekNumbers: true,
		timePicker: false,
		timePickerIncrement: 1,
		timePicker12Hour: true,
		ranges: {
			[today]        : [moment(), moment()],
			[yesterday]    : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			[last_7_days]  : [moment().subtract(6, 'days'), moment()],
			[last_30_days] : [moment().subtract(29, 'days'), moment()],
			[this_month]   : [moment().startOf('month'), moment().endOf('month')],
			[last_month]   : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		},
		opens: 'left',
		buttonClasses: ['ui compact button'],
		applyClass: 'primary small',
		cancelClass: 'small',
		format: 'MM/DD/YYYY',
		separator: ' to ',
		locale: {
			applyLabel: 'Submit',
			cancelLabel: 'Clear',
			fromLabel: 'From',
			toLabel: 'To',
			customRangeLabel: 'Custom',
			daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
			monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
			firstDay: 1
		}
	};

	var optionSet2 = {
		startDate: moment().subtract(7, 'days'),
		endDate: moment(),
		opens: 'right',
		format: 'MM/DD/YYYY',
		locale: {
			applyLabel: base.submit,
			cancelLabel: base.clear,
			fromLabel: base.from,
			toLabel: base.to,
			customRangeLabel: base.custom,
			daysOfWeek: [base.su, base.mo, base.tu, base.we, base.th, base.fr, base.sa],
			monthNames: [base.january, base.february, base.march, base.april, base.may, base.june, base.july, base.august, base.september, base.october, base.november, base.december],
			firstDay: 1
		},
		ranges: {
			[today]        : [moment(), moment()],
			[yesterday]    : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			[last_7_days]  : [moment().subtract(6, 'days'), moment()],
			[last_30_days] : [moment().subtract(29, 'days'), moment()],
			[this_month]   : [moment().startOf('month'), moment().endOf('month')],
			[last_month]   : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		}
	};
	start_date = moment().subtract(29, 'days');
	to_date = moment();
	$("#from_date").val(start_date.format('YYYY/MM/DD')) ;
	$("#to_date").val(to_date.format('YYYY/MM/DD')) ;

	$('#reportrange span').html( start_date.format('MMMM D, YYYY') + ' - ' + to_date.format('MMMM D, YYYY'));

	$('#reportrange').daterangepicker(optionSet2, cb);

	$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
		$("#from_date").val(picker.startDate.format('YYYY/MM/DD')) ;
		$("#to_date").val(picker.endDate.format('YYYY/MM/DD')) ;
		jQuery("#form_sales_report").submit();
	});
	$('#reportrange').on('cancel.daterangepicker', function(ev, picker) {

	});

	jQuery("#form_sales_report").submit();
});
