<?php

	function get_customer_active_orders($customer_id) {
		$result = mysql_query(
			"SELECT id, title, content, price
			 FROM orders
			 WHERE is_completed = FALSE AND customer_id = " . $customer_id . " 
			 ORDER BY id DESC;");

		if (!$result) {
			die(mysql_error());
		}

		$rows = array();
		while($row = mysql_fetch_assoc($result)) {
			$rows[] = $row;
		}

		return $rows;
	}

	function add_order($customer_id, $customer_name, $title, $content, $price) {
		$result = mysql_query(
			"INSERT INTO orders (title, content, price, customer_id, customer_name, is_completed)
			 VALUES ('" . $title . "', '" . $content . "', " . $price . ", " . $customer_id . ", '" . $customer_name . "', FALSE);");

		if (!$result) {
			die(mysql_error());
		}

		return mysql_insert_id();
	}

?>