<?php

	require_once('backend/db/connect.php');
	require_once('backend/db/users_queries.php');

	db_connect();

	function inc_balance_handler() {
		global $context_user_id;

		$balance = inc_balance_query($context_user_id, BALANCE_INC_PART);
		
		return array(
			'success' => $balance > 0,
			'balance' => $balance
		);
	}

?>