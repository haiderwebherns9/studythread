<?php

class WPJ_Query{
	public $_wpdb;
	public function __construct(){
		global $wpdb, $_select_sql, $_select_result;
		$this->_wpdb = $wpdb;
	}

	public function get_wpj_query( $params = array() ){
		if(isset($params['columns']) && $params['columns'] != ''){
			$select = "SELECT " . $params['columns'];
		}else{
			$select = "SELECT *";
		}

		if(isset($params['table']) && $params['table'] != ''){
			$from = " FROM " . $params['table'];
		}else{
			$from = " FROM ";
		}

		if(isset($params['where']) && $params['where'] != ''){
			$where = " WHERE " . $params['where'];
		}else{
			$where = "";
		}

		if(isset($params['order_by']) && $params['order_by'] != ''){
			$order_by = " ORDER BY " . $params['order_by'];
		}else{
			$order_by = "";
		}

		if(isset($params['group_by']) && $params['group_by'] != ''){
			$group_by = " GROUP BY " . $params['group_by'];
		}else{
			$group_by = "";
		}

		if(isset($params['limit']) && $params['limit'] != ''){
			$limit = " LIMIT " . $params['limit']['1'].", ".$params['limit']['0'];
		}else{
			$limit = "";
		}

		$this->_select_sql = $select . $from . $where . $order_by . $group_by . $limit;
		$this->_result_sql = $this->_wpdb->get_results($this->_select_sql);
		return $this->_result_sql;
	}
}

?>
