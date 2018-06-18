<?php   	  
	  $arr = array();	  
	 $dt1 = json_decode( $_POST['arrdata']);
	// print_r($_POST['arrdata']);
	// print_r($_POST['date_for']);
	   for($t=0;$t<=23;$t++) { 
					    $k=0;						
					     while($k<60){ 
						   $dt=  $_POST['date_for'].' ';
						   $dt .= strlen($t)>1?$t:'0'.$t;
						   $dt .= ':';
						   $dt .= strlen($k)>1?$k:'0'.$k;		
						   if(in_array($dt, $dt1 ))
						   {
							   $arr[] = true;
						   }else{
							   $arr[] = false;
						   }
						    $k=$k+30;
						 }
						
	   }

						   if(in_array($dt, $dt1 ))
						   {
							   $arr[] = true;
						   }else{
							   $arr[] = false;
						   }
	   echo json_encode($arr);
?>