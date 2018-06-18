<?php get_header();
/*
Template Name: Calender Features
*/
?>
 <?php 
	//Time Conversion Function
	  function time_convertion($date_formate, $time_formate, $old_timezone, $new_timezone){
		$dt_str=$date_formate.' '.$time_formate;	
		$date = new DateTime($dt_str,  new DateTimeZone($old_timezone));
       	
		$date->setTimezone(new DateTimeZone($new_timezone));
		$new_formate= $date->format('d-F-Y H:i');
		return $new_formate;
	  }
	 
    ?>
	<?php 
	    global $current_user;
		global $session;
	    $current_user = wp_get_current_user();
		$uid = $current_user->ID;
	    $type=user($uid, 'wpjobster_user_type');
		if($type == 'seller'){
			$uid1 = $uid ;
		}
		if(($type == 'buyer') &&  isset($_GET['user_id'])) {
			 $uid1=$_GET['user_id'];
		}
	 
		//Select Time slot from table
		global $wpdb;
		    if(isset($_POST['confirm'])){
		 // print_r($_POST);
		  //Insert Time Slot into The Database For Teacher
		  if($type=='seller'){
			  $tslot=$_POST['bokng_slot'];
			 $rem_tslot=$_POST['remove_bokng_slot'];
			   if(!empty($rem_tslot)){
				  foreach($rem_tslot as $rmv_val){
					$qry_del=  $wpdb->query(
                       'DELETE  FROM `wp_87fsrr_calendar`
                        WHERE `teacher_id` = "'.$_POST["teacher_id"].'" 
						AND  `cal_date` = "'.$_POST["bokng_date"].'" 
						AND `cal_time_zone` = "'.$_POST["bokng_tzone"].'"
						AND `cal_day` = "'.$_POST["bokng_day"].'"
						AND `cal_time` = "'.trim($rmv_val).'"'
                     );
				  }
			   }
			   ?>
			   <?php 
			  if(!empty($tslot)){
			   foreach($tslot as $tval) {
		  $qry=$wpdb->query( $wpdb->prepare(
				"INSERT INTO `wp_87fsrr_calendar` (`teacher_id`, `cal_time`, `cal_date`,`cal_time_zone`,`cal_day`) VALUES ( %d, %s, %s, %s, %s )",
				array(
					$_POST['teacher_id'],
					trim($tval),
					$_POST['bokng_date'],
					$_POST['bokng_tzone'],
					$_POST['bokng_day']
				)
			));
			  }
			   }
			if($qry || $qry_del){
				echo '<h2 style="color:green">Your Selected Time Has Been Saved. Select Other Days and Time for Your Schedule. </h2>';
			   
			}
			  
		  }
		  //Insert Time Slot in database for Student
		  if($type=='buyer'){
			     //print_r($_POST);
				// Passing array
				$stud_data = array(
					'teacher_id' => $_POST['teacher_id'],
					'student_id' => $uid,
					'job_id' => $_POST['job_id'],
					'book_slot' =>$_POST['bokng_slot'],
					'book_date' => $_POST['bokng_date'],
					'book_tzone' => $_POST['bokng_tzone'],
					'book_day' => $_POST['bokng_day'],
				);
				$_SESSION['student']=$stud_data;
				//print_r($_SESSION['student']);
		 ?>
		    <script>
			    location.href ="https://www.studythread.com/?jb_action=purchase_this&jobid=<?php echo $_POST['job_id'];?>";
			</script>
		 <?php
		  }
		}
			
		//Teacher
		$query1 = "
		SELECT *
		FROM `wp_87fsrr_calendar`
		WHERE `teacher_id` =".$uid1;
        $data= $wpdb->get_results($query1);
		
		if(isset($_GET['time_zone']))
		{
			$tme_zn = $_GET['time_zone'];
		}else{
		   $tme_zn = 'US/Hawaii';
		}
		
		$arr_date = array();
		 //print_r($data);
		if(count($data) > 0)
		{ 
			foreach($data as $key => $val)
			{
		if((trim($val->cal_date)!="undefined") && (trim($val->cal_time)!="undefined") && (trim($val->cal_time_zone)!="undefined")){ 
			  $arr_date[] = time_convertion(trim($val->cal_date), trim($val->cal_time), trim($val->cal_time_zone), $tme_zn);
	     	}
			}
		}
		if($type == 'buyer') {
			//Student 
			$query2 = "
			SELECT *
			FROM `wp_87fsrr_calendar_stud`
			WHERE `student_id` =".$uid;
			$data1= $wpdb->get_results($query2);
			
			if(isset($_GET['time_zone']))
			{
				$tme_zn1 = $_GET['time_zone'];
			}else{
			   $tme_zn1 = 'US/Hawaii';
			}
			$arr_date1 = array();
			if(count($data1) > 0)
			{ 
				foreach($data1 as $key1 => $val1)
				{
				  $arr_date1[] = time_convertion( trim($val1->scal_date), trim($val1->scal_time),  trim($val1->scal_time_zone), $tme_zn);
				}
			}
			
			}else{
				//Teacher 
			$query2 = "
			SELECT *
			FROM `wp_87fsrr_calendar_stud`
			WHERE `steacher_id` =".$uid;
			$data1= $wpdb->get_results($query2);
			
			if(isset($_GET['time_zone']))
			{
				$tme_zn1 = $_GET['time_zone'];
			}else{
			   $tme_zn1 = 'US/Hawaii';
			}
			$arr_date1 = array();
			if(count($data1) > 0)
			{ 
				foreach($data1 as $key1 => $val1)
				{
				  $arr_date1[] = time_convertion( trim($val1->scal_date), trim($val1->scal_time),  trim($val1->scal_time_zone), $tme_zn);
				}
			}
			}
	
	
	?>
	
   <div class="entry-content-page">
     <?php  include_once( 'timezone.php' );  ?>
    <div class="calender_view">
	    <div class="calendar_carusal" id="list_id">
	    <ul >
			<?php 
			   if(!isset($_GET['order']))
			   { $custom_date=date("Y-m-d");
				  ?>
				  <li>
			     <h5> – TODAY – </h5>
			    <?php 
			   echo "<p class='day_full'>".date('l', strtotime($custom_date))."</p>";
				echo "<p class='day'>".date('D', strtotime($custom_date))."</p>";
				echo "<p class='date'>".date("F j", strtotime($custom_date))."</p>";
				?>
				<input type="hidden" class="date_formate" name="dt" value="<?php echo date("d-F-Y"); ?>">
			 </li>
				  <?php
			   }else{
				   $custom_date = $_GET['date'];
			   }
			   for($i=1;$i<8;$i++) {	
			  
                  if((($i != 7) && (!isset($_GET['date']))) || (isset($_GET['date']))){				   
				   ?>
			<li>
				<?php 
				if(($_GET['order']=='next') && isset($_GET['date'])) {	
                   echo "<p class='day_full'>".date('l', strtotime('+'.$i.' days',strtotime($custom_date)))."</p>";				
					echo "<p class='day'>".date('D', strtotime('+'.$i.' days',strtotime($custom_date)))."</p>";
				    echo "<p class='date'>".date("F j", strtotime('+'.$i.' days',strtotime( $custom_date)))."</p>"; 
                 ?>
               <input type="hidden" class="date_formate" name="dt" value="<?php echo date("d-F-Y", strtotime('+'.$i.' days',strtotime($custom_date))); ?>">				 
			  <?php			
			     } elseif(($_GET['order']=='prev') && isset($_GET['date'])) {
			        $j = 8-$i;
					echo "<p class='day_full'>".date('l', strtotime('-'.$j.' days',strtotime( $custom_date)))."</p>";
				    echo "<p class='day'>".date('D', strtotime('-'.$j.' days',strtotime( $custom_date)))."</p>";
				    echo "<p class='date'>".date("F j", strtotime('-'.$j.' days',strtotime( $custom_date)))."</p>"; 
					?>
					<input type="hidden" class="date_formate" name="dt" value="<?php echo date("d-F-Y", strtotime('-'.$j.' days',strtotime($custom_date))); ?>">
					<?php
				} else {	 
					echo "<p class='day_full'>".date('l', strtotime('+'.$i.' days',strtotime($custom_date)))."</p>";				
			    	echo "<p class='day'>".date('D', strtotime('+'.$i.' days',strtotime($custom_date)))."</p>";
				    echo "<p class='date'>".date("F j", strtotime('+'.$i.' days',strtotime($custom_date)))."</p>"; 
					?>
					<input type="hidden" class="date_formate" name="dt" value="<?php echo date("d-F-Y", strtotime('+'.$i.' days',strtotime($custom_date))); ?>">
				<?php
				}
				
				?>
				
			</li>
				   <?php 
			        }
				   } 
			?>
        </ul>	
            </div>		
        <div class="control">
		     <a class="prev" href="javascript:void(0);">Prev</a>
			 <a class="next" href="javascript:void(0);">Next</a>
        </div>		
        </div>		 
    </div>
<?php 
         if(isset($_GET['time_zone'])){
?> 
			<script>
				$(document).ready(function() {		
					$(".inp_tzone").val("<?php echo  $_GET['time_zone'];?>");
		             $("#tz").text("<?php echo  $_GET['time_zone'];?>");
					 $("#time_zone option[value='<?php echo  $_GET['time_zone'];?>']").attr('selected','selected');
				});
			</script>
<?php 
		 }else{
?>
	<script>
				$(document).ready(function() {		
					$(".inp_tzone").val("US/Hawaii");
		             $("#tz").text("US/Hawaii");
				});
			</script>
<?php } 		?>
<script>
    $(document).ready(function() {		
	
		$(document).on('click','#select2-time_zone-results li',function(){
		    var tz=$('#time_zone option:selected').attr('value');		
			location.href="https://www.studythread.com/calendar-feature/?user_id=<?php echo $_GET['user_id']; ?>&time_zone="+tz;
		});
		var dat=$('#list_id li:first-child').find('.date_formate').val();
		var cur_date='<?php echo date("d-F-Y");?>';
		if(dat == cur_date){
		   $(".prev").css({"pointer-events": "none", "opacity": "0.5"});
		} else {
			$(".prev").css({"pointer-events": "inherit", "opacity": "1"});
		}
    $(document).on("click",".prev",function() {
        var dt = $('.date_formate:eq(0)').val();
       $('#list_id').load('https://www.studythread.com/calendar-feature/?date='+dt+'&order=prev #list_id ul',function(){
        var dat=$('#list_id li:first-child').find('.date_formate').val();
		$(".calender_view #list_id li:first-child .day").css("margin-top", "0px");
		var cur_date='<?php echo date("d-F-Y");?>';
			if(dat == cur_date){
				$( "<h5>__Today__</h5>" ).prependTo( "#list_id li:first-child" );
				$(".calender_view #list_id li:first-child .day").css("margin-top", "30px");
			   $(".prev").css({"pointer-events": "none", "opacity": "0.5"});
			} else {
				$(".prev").css({"pointer-events": "inherit", "opacity": "1"});
			}
		});
	});
	 $(document).on("click",".next",function() {
		var dt = $('.date_formate:eq(6)').val();
       $('#list_id').load('https://www.studythread.com/calendar-feature/?date='+dt+'&order=next #list_id ul',function(){
       var dat=$('#list_id li:first-child').find('.date_formate').val();
	   $(".calender_view #list_id li:first-child .day").css("margin-top", "0px");
		var cur_date='<?php echo date("d-F-Y");?>';
		if(dat == cur_date){
		   $(".prev").css({"pointer-events": "none", "opacity": "0.5"});
		} else {
			$(".prev").css({"pointer-events": "inherit", "opacity": "1"});
		}
	   });
	});
	 $(document).on("click","#list_id",function() {
		  $("#cal_Modal").show();
		  $(".cclose").click(function(){
			  $(this).parents('#cal_Modal').fadeOut();
		  });
	   });
	   var arr = [];
	   var jk = 0;
	   var dt='';
	   var tt = '';
		for(var i = 0; i<24;i++)
		{
			jk=0;
			while(jk<60)
			{
			    if(i<10)
				{
					dt = 0+i;
				}else{
				    dt = i;
				}
				if(jk == 0)
				{
					tt = '00';
				}else{
					tt = jk;
				}
				arr.push(dt+':'+tt);
				jk=jk+30;
			}
		}
		
	 $(document).on("click",".time_slot_li",function() {
		if($(this).hasClass('alr_bo'))
		{			
			 alert('Sorry! This time slot is already booked by student.');
		}else{
				var vals = $(this).find('.time_slot').text();		
				if($(this).parent().hasClass('formate_12')){
					var ind = $( ".formate_12 li" ).index( $( this ) ) ;		
				}else{
					 var ind = $( ".formate_24 li" ).index( $( this ) ) ;		
				}
			
			   if($(this).hasClass('tred'))
			   {
				   $(this).removeClass('tred');
				   $(this).addClass('tgreen');
				   $('.form_cl').append('<input type="hidden" name="remove_bokng_slot[]" class="inp_tslot" value="'+arr[ind]+'" /> ');
			   }else{
				   var exist_term=0;
				   $('input[name="bokng_slot[]"]').each(function(index,value){
							 if(  $(value).val()==arr[ind]  ) 
							 {
								 $(value).remove();
								 exist_term =1;
								  
							 }
				   });
				   if(exist_term == 0){
					var type_user = $('input[name="user_type_stu_tea"]').val();
						if(type_user == 'buyer' &&  $('input[name="bokng_slot[]"]').length == 2 )
						{
						   alert('Sorry! you are already selected 2 time slot.');
						}else{
							$( this ).find('.time_slot').addClass( "selected" );	
							$('.form_cl').append('<input type="hidden" name="bokng_slot[]" class="inp_tslot" value="'+arr[ind]+'" /> ');
						}					
				 }else{
				   $( this ).find('.time_slot').removeClass( "selected" );	
				   }
			   }
		   }
	   });  
	   
	   $('#time_formt').change(function() {
        if ($(this).prop('checked')) {
			$(".formate_12").hide();
            $(".formate_24").show();
        }
        else {
            $(".formate_24").hide();
            $(".formate_12").show();
        }
    });
});
</script>
<?php    if($type=='buyer'){ ?>
<script>
$(document).ready(function() {
	 
$(document).on("click","#list_id li",function() {
		      $('.formate_12 li').removeClass("tred");
			  $('.formate_12 li').removeClass("tgreen");
			  $('.formate_24 li').removeClass("tred");
			  $('.formate_24 li').removeClass("tgreen");
			  $('.formate_12 li').removeClass("sred");
			  $('.formate_12 li').removeClass("sgreen");
			  $('.formate_24 li').removeClass("sred");
			  $('.formate_24 li').removeClass("sgreen");
		  var fday=$(this).find(".day_full").text();
		 var fdt=$(this).find(".date_formate").val();
		  $("#day").text(fday);
		  $("#dte").text(fdt);
		  $(".inp_day").val(fday);
		  $(".inp_date").val(fdt);
		  $.ajax({
			  method: "POST",
			  url: "https://www.studythread.com/calendar_ajax.php",			
			  data: { arrdata: '<?php echo json_encode($arr_date); ?>', date_for : fdt}
			})
			  .done(function( msg ) {
				var jsn_data=  jQuery.parseJSON(msg);
				  
				 for(var i =0 ; i<jsn_data.length; i++)
				 {  
					  if(jsn_data[i] == true){
					      $('.formate_12 li').eq(i).addClass('tgreen');
						  $('.formate_24 li').eq(i).addClass('tgreen');
					  }else{
						 $('.formate_12 li').eq(i).addClass('tred');
						 $('.formate_24 li').eq(i).addClass('tred');
					  }
				 }
			  });
			   $.ajax({
			  method: "POST",
			  url: "https://www.studythread.com/calendar_ajax.php",			
			  data: { arrdata: '<?php echo json_encode($arr_date1); ?>', date_for : fdt}
			})
			  .done(function( msg ) {
				var jsn_data=  jQuery.parseJSON(msg);
				  
				 for(var i =0 ; i<jsn_data.length; i++)
				 {  
					  if(jsn_data[i] == true){
					      $('.formate_12 li').eq(i).addClass('sred');
						  $('.formate_24 li').eq(i).addClass('sred');
					  }else{
						 $('.formate_12 li').eq(i).addClass('sgreen');
						 $('.formate_24 li').eq(i).addClass('sgreen');
					  }
				 }
			  });
	   });
	});
</script>
<?php }else{ ?>
<script>
$(document).ready(function() {
	
$(document).on("click","#list_id li",function() {
		      $('.formate_12 li').removeClass("tred");
			  $('.formate_12 li').removeClass("tgreen");
			  $('.formate_24 li').removeClass("tred");
			  $('.formate_24 li').removeClass("tgreen");		
			  $('.formate_24 li').removeClass('alr_bo');
			  $('.formate_12 li').removeClass('alr_bo');
		  var fday=$(this).find(".day_full").text();
		 var fdt=$(this).find(".date_formate").val();
		  $("#day").text(fday);
		  $("#dte").text(fdt);
		  $(".inp_day").val(fday);
		  $(".inp_date").val(fdt);
		  $.ajax({
			  method: "POST",
			  url: "https://www.studythread.com/calendar_ajax.php",			
			  data: { arrdata: '<?php echo json_encode($arr_date); ?>', date_for : fdt}
			})
			  .done(function( msg ) {
				var jsn_data=  jQuery.parseJSON(msg);
				  
				 for(var i =0 ; i<jsn_data.length; i++)
				 {  
					  if(jsn_data[i] == true){
					      $('.formate_12 li').eq(i).addClass('tred');
						  $('.formate_24 li').eq(i).addClass('tred');
					  }else{
						 $('.formate_12 li').eq(i).addClass('tgreen');
						 $('.formate_24 li').eq(i).addClass('tgreen');
					  }
				 }
			  });			  
			  $.ajax({
			  method: "POST",
			  url: "https://www.studythread.com/calendar_ajax.php",			
			  data: { arrdata: '<?php echo json_encode($arr_date1); ?>', date_for : fdt}
			}).done(function( msg ) {
				var jsn_data=  jQuery.parseJSON(msg);				  
				 for(var i =0 ; i<jsn_data.length; i++)
				 {  
					  if(jsn_data[i] == true){
					      $('.formate_12 li').eq(i).addClass('alr_bo');
						  $('.formate_24 li').eq(i).addClass('alr_bo');
					  }
				 }
			  });
			  
	   });
	});
</script>
<?php } ?>
<script>
$(document).ready(function() {
  $(document).on("click",".cancel",function(){
				 $("#cal_Modal").fadeOut();
			});
});
</script>
<div class="calendar_popup">
<div id="cal_Modal" class="jmodal">
  <!-- Modal content -->
  <div class="jmodal-content">
  <span class="cclose">&times;</span>
    <div class="jmodal-body">
	<input type="hidden"  id="tm_arr" value="<?php echo json_encode($arr_date); ?>"/> 	
	      	  <?php if($type=='buyer'){ ?>
		   <div class="warng">
			  Hey! Teachers is Available at the following time, Please select the box with the green highlight. 
		   </div>
		   <?php } ?>
	      <div class="time_zone">
			     <p id="day"></p>
				 <p id="dte"></p>
				 <p id="tz"></p>
		  </div>
	      <div class="time_table">
		   <div class="enb_wrap">
		    <div class="slct_time">Select a Time</div>
		    <div class="togle">
			   am/pm
				<label class="switch">
				  <input type="checkbox" id="time_formt"> 
				  <span class="slider round"></span>
				</label>
				24hr
			</div>
			</div>
			<form method="post" >
		     	    <div class="form_cl"></div>
					<input type="hidden" name="user_type_stu_tea" value="<?php echo $type; ?>"/>
				    <input type="hidden" name="bokng_day" class="inp_day" value=""/>
						<input type="hidden" name="bokng_date" class="inp_date" value=""/>
						<input type="hidden" name="bokng_tzone" class="inp_tzone" value=""/>
						<input type="hidden" name="bokng_tformate" class="inp_tf" value=""/>
				        <input type="hidden" name="job_id" class="inp_job" value="<?php if(isset($_GET['job_id'])){ echo $_GET['job_id']; }?>"/>
                          <input type="hidden" name="teacher_id" class="teachr_id" value="<?php if(isset($_GET['user_id'])){echo $_GET['user_id'];}?>"/>        
			 
			 <ul class="formate_12">
				 <?php 
			        for($t=0;$t<=11;$t++) { 
					    $k=0;
					     while($k<60){ 
			  ?>
				<li class="time_slot_li"> 
				    	
                      <div class="time_slot">
					       <?php 
								 echo strlen($t)>1?$t:'0'.$t;
								 echo ':';
								 echo strlen($k)>1?$k:'0'.$k;
					       ?>
					  </div>   
					 
				</li>
					<?php
					       $k=$k+30;
						 }
			         } 
				   ?>
				   
				   <li class="time_slot_li">
				         <div class="js-meridiem meridiem" style="display: block;">
						  <div class="text">noon</div>
						</div> 
						<div class="time_slot">
						     12:00
						 </div>
						
				   </li>
				   <li class="time_slot_li">
				        <div class="time_slot">
						     12:30
						 </div>
						 
				   </li>
				   
				    <?php 
			        for($t1=1;$t1<12;$t1++) { 
					    $k1=0;
					     while($k1<60){ 
			  ?>
				<li class="time_slot_li">
				  <div class="time_slot">
					 <?php 
					 echo strlen($t1)>1?$t1:'0'.$t1;
					 echo ':';
					 echo strlen($k1)>1?$k1:'0'.$k1;
					 ?>
					</div>
					
				</li>
					<?php
					       $k1=$k1+30;
						 }
			         } 
				   ?>
			</ul>
				<ul class="formate_24">
			  <?php 
			        for($t=0;$t<=23;$t++) { 
					    $k=0;
					     while($k<60){ 
			  ?>
				<li class="time_slot_li"> 
				  <div class="time_slot">
					       <?php 
								 echo strlen($t)>1?$t:'0'.$t;
								 echo ':';
								 echo strlen($k)>1?$k:'0'.$k;
					       ?>
					  </div>  
				</li>
					<?php
					       $k=$k+30;
						 }
			         } 
				   ?>
			
			</ul>
			 <div class="btn_box">
				<a href="javascript:void(0)" style="text-align:center" class="tm cancel">Cancel</a>
				<button type="submit" class="cnf" name="confirm">Confirm</button>
			</div>
			</form>
		  </div>
	</div>
  </div>
</div>  
</div>
<?php get_footer(); ?>
