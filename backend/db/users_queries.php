<?php
	
	function is_user_registered_query($email) {
		$result = mysql_query(
			"SELECT count(id) FROM users WHERE email = '$email';"
		);

		return !$result || mysql_result($result, 0) > 0;
	}

	function register_user_query($username, $email, $pwd, $role) {
		$pwdhash = md5(md5($pwd));

		$result = mysql_query(
			"INSERT INTO users (username, email, password_hash, role) 
			 VALUES ('$username', '$email', '$pwdhash', $role);"
		);

		if (!$result || !mysql_affected_rows()) {
			return 0;
		}

		return mysql_insert_id();
	}

	function get_user_by_email_pwd_query($email, $pwd) {
		$pwdhash = md5(md5($pwd));

		$result = mysql_query(
			"SELECT id, username, role 
			 FROM users
			 WHERE email = '$email' AND 
			       password_hash = '$pwdhash';"
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