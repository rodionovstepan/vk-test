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

?>