<?php

	$users_db_link = NULL;
	$orders_db_link = NULL;

	function db_connect2() {
		global $users_db_link, $orders_db_link;

		if (!isset($users_db_link)) {
			$users_db_link = _connect(DB_USERS_HOST, DB_USERS_USER, DB_USERS_PWD, DB_USERS_NAME);
		}
		
		if (!isset($orders_db_link)) {
			if (DB_USERS_HOST != DB_ORDERS_HOST || DB_USERS_NAME != DB_ORDERS_NAME) {
				$orders_db_link = _connect(DB_ORDERS_HOST, DB_ORDERS_USER, DB_ORDERS_PWD, DB_ORDERS_NAME);
			} else {
				$orders_db_link = $users_db_link;
			}
		}
	}

	function start_transaction() {
		_query("START TRANSACTION");
	}

	function rollback_transaction() {
		_query("ROLLBACK");
	}

	function commit_transaction() {
		_query("COMMIT");
	}

	function _query($query) {
		global $users_db_link, $orders_db_link;

		if (isset($users_db_link) && isset($orders_db_link)) {
			if ($users_db_link != $orders_db_link) {
				mysql_query($query, $users_db_link);
				mysql_query($query, $orders_db_link);
			} else {
				mysql_query($query, $users_db_link);
			}
		}
	}

	function _connect($host, $user, $pwd, $db_name) {
		$link = mysql_connect($host, $user, $pwd, true) or die(mysql_error());
		mysql_select_db($db_name, $link) or die(mysql_error());
		mysql_set_charset('utf-8', $link);

		return $link;
	}

?>