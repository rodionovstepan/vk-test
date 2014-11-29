<?php

	require_once('backend/ajax_handler.php');
	require_once('backend/handlers/users_handler.php');

	if ($act == 'inc_balance') {
		echo json_encode(inc_balance_handler());
	} else {
		invalid_request();
	}

?>