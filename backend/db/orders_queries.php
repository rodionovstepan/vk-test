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
		mysql_query("START TRANSACTION");

		$update = mysql_query(
			"UPDATE users SET order_count = order_count+1 WHERE id = " . $customer_id . ";"
		);

		$insert = mysql_query(
			"INSERT INTO orders (title, content, price, customer_id, customer_name, is_completed)
			 VALUES ('" . $title . "', '" . $content . "', " . $price . ", " . $customer_id . ", '" . $customer_name . "', FALSE);"
		);

		$id = mysql_insert_id();

		if (!$update || !$insert) {
			mysql_query("ROLLBACK");
			return 0;
		} 

		mysql_query("COMMIT");
		return $id;
	}

?>