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
		_query_all("START TRANSACTION", $link);
	}

	function rollback_transaction($link = NULL) {
		_query_all("ROLLBACK", $link);
	}

	function commit_transaction($link = NULL) {
		_query_all("COMMIT", $link);
	}

	function update_query($query, $args, $link) {
		if ($stmt = mysqli_prepare($link, $query)) {
			if (!empty($args)) {
				call_user_func_array(array($stmt, 'bind_param'), _ref_array($args));
			}

			if (!mysqli_stmt_execute($stmt) || !mysqli_stmt_affected_rows($stmt)) {
				return 0;
			}

			mysqli_stmt_close($stmt);
			return 1;
		}

		return 0;
	}

	function insert_query($query, $args, $link) {
		if ($stmt = mysqli_prepare($link, $query)) {
			if (!empty($args)) {
				call_user_func_array(array($stmt, 'bind_param'), _ref_array($args));
			}
			
			if (!mysqli_stmt_execute($stmt)) {
				return 0;
			}

			$id = mysqli_stmt_insert_id($stmt);
			mysqli_stmt_close($stmt);

			return $id;
		}

		return 0;
	}

	function select_query($query, $args, $link) {
		if ($stmt = mysqli_prepare($link, $query)) {
			if (!empty($args)) {
				call_user_func_array(array($stmt, 'bind_param'), _ref_array($args));
			}

			if (!mysqli_stmt_execute($stmt)) {
				return array();
			}

			$rows = array();
			$vars = array();
			$data = array();
			$metadata = mysqli_stmt_result_metadata($stmt);

	      while ($field = mysqli_fetch_field($metadata)) {
	         $vars[] = &$data[$field->name];
	      }

	      call_user_func_array(array($stmt, 'bind_result'), _ref_array($vars));

	      while (mysqli_stmt_fetch($stmt)) {
	      	$row = array();

	      	foreach ($data as $k => $v) {
	      		$row[$k] = $v;
	      	}

	      	$rows[] = $row;
	      }

			return $rows;
		}

		return array();
	}

	function _query_all($query, $link) {
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

	function _ref_array($array) {
		$refs = array();
		foreach ($array as $k => $v) {
			$refs[$k] = &$array[$k];
		}

		return $refs;
	}

?>