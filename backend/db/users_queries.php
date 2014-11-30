<?php
	
	function is_user_registered_query($email_hash) {
		$result = mysql_query(
			"SELECT count(id) FROM users WHERE email_hash = '$email_hash';"
		);

		return !$result || mysql_result($result, 0) > 0;
	}

	function register_user_query($username, $email, $email_hash, $pwd, $role) {
		$pwd_hash = md5(md5($pwd));

		$result = mysql_query(
			"INSERT INTO users (username, email, email_hash, password_hash, role) 
			 VALUES ('$username', '$email', '$email_hash', '$pwd_hash', $role);"
		);

		if (!$result || !mysql_affected_rows()) {
			return 0;
		}

		return mysql_insert_id();
	}

	function get_user_by_email_pwd_query($email, $pwd) {
		$pwd_hash = md5(md5($pwd));
		$email_hash = md5($email);

		$result = mysql_query(
			"SELECT id, username, role 
			 FROM users
			 WHERE email_hash = '$email_hash' AND 
			       password_hash = '$pwd_hash';"
		);

		if (!$result || !mysql_num_rows($result)) {
			return NULL;
		}

		return mysql_fetch_assoc($result);
	}

	function get_user_info_query($id) {
		$result = mysql_query(
			"SELECT balance, order_count, username
			 FROM users 
			 WHERE id = $id;"
		);

		if (!$result || !mysql_num_rows($result)) {
			return NULL;
		}

		return mysql_fetch_assoc($result);
	}

	function inc_balance_query($customer_id, $value) {
		$result = mysql_query(
			"UPDATE users 
			 SET balance = balance+$value 
			 WHERE id = $customer_id;"
		);

		if (!$result || !mysql_affected_rows()) {
			return 0;
		}

		$result = mysql_query(
			"SELECT balance FROM users WHERE id = $customer_id;"
		);

		if (!$result) {
			return 0;
		}

		$row = mysql_fetch_array($result);
		return $row[0];
	}
?>