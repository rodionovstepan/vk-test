<?php
	
	function register_user_query($username, $email, $email_hash, $pwd, $role) {
		global $users_db_link;

		$pwd_hash = md5(md5($pwd));

		start_transaction();

		$registered = mysql_query(
			"SELECT count(id) FROM users WHERE email_hash = '$email_hash';",
			$users_db_link
		);

		if (!$registered || mysql_result($registered, 0) > 0) {
			rollback_transaction();
			return -6;
		}

		$result = mysql_query(
			"INSERT INTO users (username, email, email_hash, password_hash, role) 
			 VALUES ('$username', '$email', '$email_hash', '$pwd_hash', $role);",
			$users_db_link
		);

		if (!$result || !mysql_affected_rows($users_db_link)) {
			rollback_transaction();
			return 0;
		}

		$id = mysql_insert_id($users_db_link);

		commit_transaction();
		return $id;
	}

	function get_user_by_email_pwd_query($email, $pwd) {
		global $users_db_link;

		$pwd_hash = md5(md5($pwd));
		$email_hash = md5($email);

		$result = mysql_query(
			"SELECT id, username, role 
			 FROM users
			 WHERE email_hash = '$email_hash' AND 
			       password_hash = '$pwd_hash';",
			$users_db_link
		);

		if (!$result || !mysql_num_rows($result)) {
			return NULL;
		}

		return mysql_fetch_assoc($result);
	}

	function get_user_info_query($id) {
		global $users_db_link;

		$result = mysql_query(
			"SELECT balance, order_count, username
			 FROM users 
			 WHERE id = $id;",
			$users_db_link
		);

		if (!$result || !mysql_num_rows($result)) {
			return NULL;
		}

		return mysql_fetch_assoc($result);
	}

	function inc_balance_query($customer_id, $value) {
		global $users_db_link;
		
		$result = mysql_query(
			"UPDATE users 
			 SET balance = balance+$value 
			 WHERE id = $customer_id;",
			$users_db_link
		);

		if (!$result || !mysql_affected_rows($users_db_link)) {
			return 0;
		}

		$result = mysql_query(
			"SELECT balance FROM users WHERE id = $customer_id;", $users_db_link
		);

		if (!$result) {
			return 0;
		}

		$row = mysql_fetch_array($result);
		return $row[0];
	}
?>