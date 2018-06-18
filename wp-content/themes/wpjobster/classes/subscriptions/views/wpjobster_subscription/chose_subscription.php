<div class="p40t">

	<?php $is_subscribed='0';
	if(is_object($this->current_subscription) ){
		$is_subscribed='1';
		$current_level = $this->current_subscription->subscription_level;
		$current_type = $this->current_subscription->subscription_type;
		$current_amount = $this->current_subscription->subscription_amount;
	}

	include('subscription_top.php');
	$sub_action = isset($_GET['sub_action'])?$_GET['sub_action']:'default';

	if($is_subscribed=='1'){
		if($sub_action=='cancel'){
			include('subscription_cancel.php');
		}elseif($sub_action=='schedule'){
			$schedule_only = 1;
			include('subscription_schedule.php');
		}elseif($sub_action=='change'){
			include('subscription_change.php');
		}else{
			include('subscription_details.php');
		}
	}else{ // if already subscription
		if($sub_action=='schedule'){
			$schedule_only = 1;
		}else{
			$schedule_only = 0;
		}
		include('subscription_form.php');
	} ?>

</div>
