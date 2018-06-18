<?php

global $wpdb;
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

global $charset_collate;
$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE {$wpdb->prefix}job_admin_earnings (
	id int(11) NOT NULL AUTO_INCREMENT,
	orderid bigint(20) NOT NULL DEFAULT '0',
	pid tinyint(4) NOT NULL DEFAULT '0',
	admin_fee double NOT NULL DEFAULT '0',
	datemade bigint(20) NOT NULL DEFAULT '0',
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_affiliate_commissions (
	id int(11) NOT NULL AUTO_INCREMENT,
	uid bigint(20) DEFAULT NULL,
	pid bigint(20) DEFAULT NULL,
	datemade bigint(20) DEFAULT NULL,
	datepaid bigint(20) DEFAULT NULL,
	amount varchar(255) NOT NULL DEFAULT '0',
	paid tinyint(4) NOT NULL DEFAULT '0',
	showme tinyint(4) NOT NULL DEFAULT '1',
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_affiliate_users (
	id int(11) NOT NULL AUTO_INCREMENT,
	owner_id int(11) DEFAULT NULL,
	affiliate_id int(11) DEFAULT NULL,
	datemade bigint(20) DEFAULT NULL,
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_braintree_merchant_ac_ids (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	merchant_ac_id varchar(50) NOT NULL,
	merchant_ac_currency varchar(5) NOT NULL,
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_chatbox (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	content text NOT NULL,
	attachment mediumtext,
	oid int(11) NOT NULL DEFAULT '0',
	uid int(11) NOT NULL DEFAULT '0',
	datemade int(11) NOT NULL DEFAULT '0',
	rd_receiver tinyint(20) NOT NULL DEFAULT '0',
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_credits_balance_log (
	id int(11) NOT NULL AUTO_INCREMENT,
	datemade int(11) NOT NULL,
	uid int(11) NOT NULL,
	credit_balance varchar(55) NOT NULL,
	job_payment_transaction_id int(11) NOT NULL,
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_ipcache (
	id int(11) NOT NULL AUTO_INCREMENT,
	ipnr varchar(255) NOT NULL DEFAULT '0',
	country varchar(255) NOT NULL DEFAULT '0',
	info text NOT NULL,
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_likes (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	date_made bigint(20) NOT NULL DEFAULT '0',
	pid bigint(20) NOT NULL DEFAULT '0',
	uid bigint(20) NOT NULL DEFAULT '0',
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_orders (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	payment_status varchar(20) NOT NULL,
	payment_gateway varchar(100) NOT NULL,
	payment_response longtext NOT NULL,
	payment_details longtext NOT NULL,
	date_made bigint(20) NOT NULL DEFAULT '0',
	expected_delivery bigint(20) NOT NULL DEFAULT '0',
	date_finished bigint(20) NOT NULL DEFAULT '0',
	date_closed bigint(20) NOT NULL DEFAULT '0',
	pid bigint(20) NOT NULL DEFAULT '0',
	uid bigint(20) NOT NULL DEFAULT '0',
	done_seller tinyint(4) NOT NULL DEFAULT '0',
	closed tinyint(4) NOT NULL DEFAULT '0',
	completed tinyint(4) NOT NULL DEFAULT '0',
	done_buyer tinyint(4) NOT NULL DEFAULT '0',
	mc_gross varchar(255) NOT NULL DEFAULT '0',
	processing_fees varchar(55) NOT NULL,
	site_fees varchar(55) NOT NULL,
	tax_amount varchar(55) NOT NULL DEFAULT '0',
	admin_fee varchar(255) NOT NULL DEFAULT '0',
	notes_to_seller mediumtext,
	date_completed bigint(20) NOT NULL,
	extra1 tinyint(4) NOT NULL DEFAULT '0',
	extra1_price varchar(255) NOT NULL DEFAULT '0',
	extra1_title mediumtext NOT NULL,
	extra1_days varchar(255) NOT NULL DEFAULT '0',
	extra2 tinyint(4) NOT NULL DEFAULT '0',
	extra2_price varchar(255) NOT NULL DEFAULT '0',
	extra2_title mediumtext NOT NULL,
	extra2_days varchar(255) NOT NULL DEFAULT '0',
	extra3 tinyint(4) NOT NULL DEFAULT '0',
	extra3_price varchar(255) NOT NULL DEFAULT '0',
	extra3_title mediumtext NOT NULL,
	extra3_days varchar(255) NOT NULL DEFAULT '0',
	extra4 tinyint(4) NOT NULL DEFAULT '0',
	extra4_price varchar(255) NOT NULL DEFAULT '0',
	extra4_title mediumtext NOT NULL,
	extra4_days varchar(255) NOT NULL DEFAULT '0',
	extra5 tinyint(4) NOT NULL DEFAULT '0',
	extra5_price varchar(255) NOT NULL DEFAULT '0',
	extra5_title mediumtext NOT NULL,
	extra5_days varchar(255) NOT NULL DEFAULT '0',
	extra6 tinyint(4) NOT NULL DEFAULT '0',
	extra6_price varchar(255) NOT NULL DEFAULT '0',
	extra6_title mediumtext NOT NULL,
	extra6_days varchar(255) NOT NULL DEFAULT '0',
	extra7 tinyint(4) NOT NULL DEFAULT '0',
	extra7_price varchar(255) NOT NULL DEFAULT '0',
	extra7_title mediumtext NOT NULL,
	extra7_days varchar(255) NOT NULL DEFAULT '0',
	extra8 tinyint(4) NOT NULL DEFAULT '0',
	extra8_price varchar(255) NOT NULL DEFAULT '0',
	extra8_title mediumtext NOT NULL,
	extra8_days varchar(255) NOT NULL DEFAULT '0',
	extra9 tinyint(4) NOT NULL DEFAULT '0',
	extra9_price varchar(255) NOT NULL DEFAULT '0',
	extra9_title mediumtext NOT NULL,
	extra9_days varchar(255) NOT NULL DEFAULT '0',
	extra10 tinyint(4) NOT NULL DEFAULT '0',
	extra10_price varchar(255) NOT NULL DEFAULT '0',
	extra10_title mediumtext NOT NULL,
	extra10_days varchar(255) NOT NULL DEFAULT '0',
	extra_fast tinyint(4) NOT NULL DEFAULT '0',
	extra_fast_price varchar(255) NOT NULL DEFAULT '0',
	extra_fast_days varchar(255) NOT NULL DEFAULT '0',
	extra_revision tinyint(4) NOT NULL DEFAULT '0',
	extra_revision_price varchar(255) NOT NULL DEFAULT '0',
	extra_revision_days varchar(255) NOT NULL DEFAULT '0',
	custom_extras varchar(5000) DEFAULT NULL,
	job_price varchar(255) NOT NULL DEFAULT '0',
	job_amount bigint(20) NOT NULL DEFAULT '1',
	job_title mediumtext NOT NULL,
	job_description mediumtext NOT NULL,
	job_instructions mediumtext NOT NULL,
	job_image bigint(20) NOT NULL DEFAULT '0',
	message_to_buyer mediumtext NOT NULL,
	message_to_seller mediumtext NOT NULL,
	request_cancellation_from_buyer tinyint(4) NOT NULL DEFAULT '0',
	request_cancellation_from_seller tinyint(4) NOT NULL DEFAULT '0',
	request_cancellation tinyint(4) NOT NULL DEFAULT '0',
	force_cancellation tinyint(4) NOT NULL DEFAULT '0',
	accept_cancellation_request tinyint(4) NOT NULL DEFAULT '0',
	date_request_cancellation bigint(20) NOT NULL DEFAULT '0',
	date_accept_cancellation bigint(20) NOT NULL DEFAULT '0',
	request_modification tinyint(4) NOT NULL DEFAULT '0',
	date_request_modification bigint(20) NOT NULL DEFAULT '0',
	message_request_modification mediumtext NOT NULL,
	clearing_period int(11) NOT NULL DEFAULT '0',
	date_to_clear varchar(15) NOT NULL,
	shipping varchar(55) NOT NULL,
	payedamount varchar(50) NOT NULL,
	final_paidamount varchar(50) DEFAULT NULL,
	PRIMARY KEY  (id),
	KEY uid (uid),
	KEY date_made (date_made),
	KEY date_finished (date_finished)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_payment_transactions (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	uid int(11) NOT NULL DEFAULT '0',
	oid bigint(20) NOT NULL,
	rid bigint(20) NOT NULL,
	details mediumtext NOT NULL,
	reason text NOT NULL,
	datemade int(11) NOT NULL DEFAULT '0',
	amount double NOT NULL DEFAULT '0',
	tp tinyint(4) NOT NULL DEFAULT '1',
	uid2 int(11) NOT NULL DEFAULT '0',
	payedamount varchar(50) NOT NULL,
	PRIMARY KEY  (id),
	KEY datemade (datemade),
	KEY uid (uid),
	KEY id (id),
	KEY tp (tp)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_pm (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	owner int(11) NOT NULL DEFAULT '0',
	user int(11) NOT NULL DEFAULT '0',
	content text NOT NULL,
	subject text NOT NULL,
	rd tinyint(4) NOT NULL DEFAULT '0',
	parent bigint(20) NOT NULL DEFAULT '0',
	associate_job_id int(11) NOT NULL DEFAULT '0',
	associate_request_id int(11) NOT NULL,
	datemade int(11) NOT NULL DEFAULT '0',
	readdate int(11) NOT NULL DEFAULT '0',
	initiator int(11) NOT NULL DEFAULT '0',
	custom_offer bigint(20) NOT NULL DEFAULT '0',
	attached text NOT NULL,
	show_to_source tinyint(4) NOT NULL DEFAULT '1',
	show_to_destination tinyint(4) NOT NULL DEFAULT '1',
	archived_to_source tinyint(4) NOT NULL DEFAULT '0',
	archived_to_destination tinyint(4) NOT NULL DEFAULT '0',
	PRIMARY KEY  (id),
	KEY associate_job_id (associate_job_id),
	KEY associate_request_id (associate_request_id),
	KEY user (user)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_ratings (
	id int(11) NOT NULL AUTO_INCREMENT,
	orderid bigint(20) NOT NULL DEFAULT '0',
	grade tinyint(4) NOT NULL DEFAULT '0',
	datemade bigint(20) NOT NULL DEFAULT '0',
	reason text NOT NULL,
	awarded tinyint(4) NOT NULL DEFAULT '0',
	uid bigint(20) NOT NULL DEFAULT '0',
	pid bigint(20) NOT NULL DEFAULT '0',
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_ratings_by_seller (
	id int(11) NOT NULL AUTO_INCREMENT,
	orderid bigint(20) NOT NULL DEFAULT '0',
	grade tinyint(4) NOT NULL DEFAULT '0',
	datemade bigint(20) NOT NULL DEFAULT '0',
	reason text CHARACTER SET utf8 NOT NULL,
	awarded tinyint(4) NOT NULL DEFAULT '0',
	uid bigint(20) NOT NULL DEFAULT '0',
	pid bigint(20) NOT NULL DEFAULT '0',
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_subscriptions (
	id int(11) NOT NULL AUTO_INCREMENT,
	subscription_level varchar(55) NOT NULL,
	sub_start_date bigint(20) NOT NULL,
	subscription_type varchar(55) NOT NULL,
	subscription_amount decimal(19,2) NOT NULL DEFAULT '0.00',
	user_id int(11) NOT NULL,
	subscription_status varchar(55) NOT NULL,
	created_on timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	next_billing_date bigint(20) NOT NULL,
	next_subscription_level varchar(55) NOT NULL,
	next_subscription_type varchar(55) NOT NULL,
	next_subscription_amount varchar(55) NOT NULL,
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_topup_orders (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	package_id bigint(20) DEFAULT NULL,
	user_id int(11) DEFAULT NULL,
	package_amount decimal(19,2) NOT NULL DEFAULT '0.00',
	added_on bigint(20) DEFAULT NULL,
	payment_status VARCHAR(55) DEFAULT NULL,
	payment_gateway_name varchar(55) DEFAULT NULL,
	payment_gateway_transaction_id varchar(255) DEFAULT NULL,
	paid_on bigint(20) DEFAULT NULL,
	created_on timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	payment_response text NOT NULL,
	tax varchar(5) NOT NULL,
	package_cost_without_tax varchar(15) NOT NULL,
	package_credit_without_tax varchar(15) NOT NULL,
	currency varchar(5) NOT NULL,
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_topup_packages (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	cost decimal(19,2) NOT NULL DEFAULT '0.00',
	credit decimal(19,2) NOT NULL DEFAULT '0.00',
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_transactions (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	pid bigint(20) NOT NULL DEFAULT '0',
	datemjobe int(11) NOT NULL DEFAULT '0',
	uid int(11) NOT NULL DEFAULT '0',
	payment_date varchar(255) NOT NULL DEFAULT '0',
	txn_id varchar(255) NOT NULL DEFAULT '0',
	item_name varchar(255) NOT NULL DEFAULT '0',
	mc_currency varchar(255) NOT NULL DEFAULT '0',
	last_name varchar(255) NOT NULL DEFAULT '0',
	first_name varchar(255) NOT NULL DEFAULT '0',
	payer_email varchar(255) NOT NULL DEFAULT '0',
	jobdress_country varchar(255) NOT NULL DEFAULT '0',
	jobdress_state varchar(255) NOT NULL DEFAULT '0',
	jobdress_country_code varchar(255) NOT NULL DEFAULT '0',
	jobdress_zip varchar(255) NOT NULL DEFAULT '0',
	jobdress_street varchar(255) NOT NULL DEFAULT '0',
	mc_fee varchar(255) NOT NULL DEFAULT '0',
	mc_gross varchar(255) NOT NULL DEFAULT '0',
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_uservacation (
	id int(11) NOT NULL AUTO_INCREMENT,
	user_id int(10) NOT NULL,
	away_reason varchar(255) NOT NULL,
	duration_start varchar(255) NOT NULL,
	duration_start_ts int(15) NOT NULL DEFAULT '0',
	duration_end_actual varchar(255) NOT NULL DEFAULT '0',
	duration_end_actual_ts int(15) NOT NULL DEFAULT '0',
	duration_end varchar(255) NOT NULL,
	duration_end_ts int(15) NOT NULL DEFAULT '0',
	vacation_mode int(1) NOT NULL DEFAULT '0',
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_var_costs (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	cost double NOT NULL,
	PRIMARY KEY  (id),
	UNIQUE KEY cost (cost),
	UNIQUE KEY cost_2 (cost),
	UNIQUE KEY cost_3 (cost),
	UNIQUE KEY cost_4 (cost)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_withdraw (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	datemade int(11) NOT NULL DEFAULT '0',
	done int(11) NOT NULL DEFAULT '0',
	datedone int(11) NOT NULL DEFAULT '0',
	payeremail varchar(255) NOT NULL DEFAULT '0',
	uid int(11) NOT NULL DEFAULT '0',
	amount double NOT NULL DEFAULT '0',
	rejected tinyint(4) NOT NULL DEFAULT '0',
	rejected_on bigint(20) NOT NULL DEFAULT '0',
	methods text NOT NULL,
	payedamount varchar(50) NOT NULL,
	activation_key varchar(500) DEFAULT NULL,
	PRIMARY KEY  (id),
	KEY datemade (datemade),
	KEY done (done),
	KEY datedone (datedone),
	KEY payeremail (payeremail),
	KEY uid (uid),
	KEY rejected (rejected),
	KEY rejected_on (rejected_on)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_payment_received (
	id bigint(11) NOT NULL AUTO_INCREMENT,
	payment_status varchar(55) NOT NULL,
	payment_gateway varchar(55) NOT NULL,
	payment_response text NOT NULL,
	payment_details varchar(55) NOT NULL,
	payment_type varchar(55) NOT NULL COMMENT 'like job purchase,feature or topup type payment',
	payment_type_id int(11) NOT NULL COMMENT 'primary key id of the payment_type table like job_orders,topup_orders,feature orders',
	currency varchar(55) NOT NULL COMMENT 'site''s base currency',
	amount varchar(55) NOT NULL COMMENT 'payment amount in base currency',
	tax varchar(55) NOT NULL COMMENT 'tax amount in base currency',
	fees varchar(55) NOT NULL COMMENT 'any fees like processing fees in base currency',
	final_amount varchar(55) NOT NULL COMMENT 'final amount in base currency',
	final_amount_exchanged varchar(55) NOT NULL COMMENT 'final amount in payment currency',
	final_amount_currency varchar(55) NOT NULL COMMENT 'amount paid currency',
	datemade int(11) NOT NULL,
	created_on timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	payment_made_on int(11) NOT NULL,
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_featured_orders (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	feature_pages varchar(55) DEFAULT NULL,
	job_id int(11) NOT NULL,
	user_id int(11) DEFAULT NULL,
	featured_amount decimal(19,2) NOT NULL DEFAULT '0.00',
	added_on bigint(20) DEFAULT NULL,
	payment_status VARCHAR(55) DEFAULT NULL,
	payment_gateway_name varchar(55) CHARACTER SET utf8 DEFAULT NULL,
	payment_gateway_transaction_id varchar(255) CHARACTER SET utf8 DEFAULT NULL,
	paid_on bigint(20) DEFAULT NULL,
	created_on timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	payment_response varchar(555) NOT NULL,
	h_date_start int(11) NOT NULL,
	c_date_start int(11) NOT NULL,
	s_date_start int(11) NOT NULL,
	tax varchar(55) NOT NULL,
	payable_amount varchar(55) NOT NULL,
	currency varchar(15) NOT NULL,
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_custom_extra_orders (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	order_id int(11) NOT NULL,
	custom_extra_id int(11) DEFAULT NULL,
	user_id int(11) DEFAULT NULL,
	custom_extra_amount decimal(19,2) NOT NULL DEFAULT '0.00',
	added_on bigint(20) DEFAULT NULL,
	payment_status VARCHAR(55) DEFAULT NULL,
	payment_gateway_name varchar(55) CHARACTER SET utf8 DEFAULT NULL,
	payment_gateway_transaction_id varchar(255) CHARACTER SET utf8 DEFAULT NULL,
	paid_on bigint(20) DEFAULT NULL,
	created_on timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	payment_response varchar(555) NOT NULL,
	tax varchar(55) NOT NULL,
	payable_amount varchar(55) NOT NULL,
	currency varchar(15) NOT NULL,
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_payment_gateway_log (
	id int(11) NOT NULL AUTO_INCREMENT,
	order_id int(11) NOT NULL,
	payment_type varchar(55) NOT NULL,
	payment_gateway varchar(55) NOT NULL,
	transaction_id varchar(255) NOT NULL,
	payment_status varchar(155) NOT NULL,
	response_received text NOT NULL,
	datemade int(11) NOT NULL,
	created_on timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}job_subscription_orders (
	id int(11) NOT NULL AUTO_INCREMENT,
	user_id int(11) DEFAULT NULL,
	profile_id varchar(500) DEFAULT NULL,
	amount float DEFAULT NULL,
	payment_status varchar(500) DEFAULT NULL,
	subscription_status varchar(255) NOT NULL,
	addon_date int(11) DEFAULT NULL,
	payment_date int(11) DEFAULT NULL,
	mc_currency varchar(500) DEFAULT NULL,
	plan varchar(500) DEFAULT NULL,
	level varchar(500) DEFAULT NULL,
	payment_gateway_name varchar(500) NOT NULL,
	payment_gateway_transaction_id varchar(255) NOT NULL,
	payable_amount float NOT NULL,
	tax float NOT NULL,
	payment_response text NOT NULL,
	PRIMARY KEY (id)
) $charset_collate;
";

dbDelta( $sql );
