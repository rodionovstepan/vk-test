<?php

	$users_db_link = NULL;
	$orders_db_link = NULL;
	$events_db_link = NULL;

	function db_connect() {
		global $users_db_link, $orders_db_link, $events_db_link;

		if (!isset($users_db_link)) {
			$users_db_link = _connect(DB_USERS_HOST, DB_USERS_USER, DB_USERS_PWD, DB_USERS_NAME);
		}
		
		if (!isset($orders_db_link)) {
			if (DB_USERS_NAME != DB_ORDERS_NAME) {
				$orders_db_link = _connect(DB_ORDERS_HOST, DB_ORDERS_USER, DB_ORDERS_PWD, DB_ORDERS_NAME);
			} else {
				$orders_db_link = $users_db_link;
			}
		}

		if (!isset($events_db_link)) {
			if (DB_USERS_NAME != DB_EVENTS_NAME && DB_ORDERS_NAME != DB_EVENTS_NAME) {
				$events_db_link = _connect(DB_EVENTS_HOST, DB_EVENTS_USER, DB_EVENTS_PWD, DB_EVENTS_NAME);
			} else if (DB_USERS_NAME == DB_EVENTS_NAME) {
				$events_db_link = $users_db_link;
			} else {
				$events_db_link = $orders_db_link;
			}
		}
	}

	function start_transaction($link = NULL) {
		_query("START TRANSACTION", $link);
	}

	function rollback_transaction($link = NULL) {
		_query("ROLLBACK", $link);
	}

	function commit_transaction($link = NULL) {
		_query("COMMIT", $link);
	}

	function _query($query, $link) {
		global $users_db_link, $orders_db_link, $events_db_link;

		if (isset($link)) {
			mysqli_query($link, $query);
			return;
		}

		if (isset($users_db_link) && isset($orders_db_link) && isset($events_db_link)) {
			mysqli_query($users_db_link, $query);

			if ($users_db_link != $orders_db_link) {
				mysqli_query($orders_db_link, $query);
			}

			if ($users_db_link != $events_db_link && $orders_db_link != $events_db_link) {
				mysqli_query($events_db_link, $query);
			}
		}
	}

	function _connect($host, $user, $pwd, $db_name) {
		$link = mysqli_connect($host, $user, $pwd) or die('Сайт временно недоступен');
		mysqli_select_db($link, $db_name) or die('Сайт временно недоступен');
		mysqli_set_charset($link, 'utf-8');

		return $link;
	}

?>