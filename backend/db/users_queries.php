<?php
	
	function register_user_query($username, $email, $email_hash, $pwd, $role) {
		global $users_db_link;

		$pwd_hash = md5(md5($pwd));

		start_transaction();

		$registered = mysqli_query($users_db_link,
			"SELECT count(id) FROM users WHERE email_hash = '$email_hash';"
		);

		if (!$registered) {
			rollback_transaction();
			return -6;
		} else {
			$count = mysqli_fetch_array($registered);
			if ($count[0]) {
				rollback_transaction();
				return -6;
			}
		}

		$result = mysqli_query($users_db_link,
			"INSERT INTO users (username, email, email_hash, password_hash, role) 
			 VALUES ('$username', '$email', '$email_hash', '$pwd_hash', $role);"
		);

		if (!$result || !mysqli_affected_rows($users_db_link)) {
			rollback_transaction();
			return 0;
		}

		$id = mysqli_insert_id($users_db_link);

		commit_transaction();
		return $id;
	}

	function get_user_by_email_pwd_query($email, $pwd) {
		global $users_db_link;

		$pwd_hash = md5(md5($pwd));
		$email_hash = md5($email);

		$result = mysqli_query($users_db_link,
			"SELECT id, username, role 
			 FROM users
			 WHERE email_hash = '$email_hash' AND 
			       password_hash = '$pwd_hash';"
		);

		if (!$result || !mysqli_num_rows($result)) {
			return NULL;
		}

		return mysqli_fetch_assoc($result);
	}

	function get_user_info_query($id) {
		global $users_db_link;

		$result = mysqli_query($users_db_link,
			"SELECT balance, order_count, username
			 FROM users 
			 WHERE id = $id;"
		);

		if (!$result || !mysqli_num_rows($result)) {
			return NULL;
		}

		return mysqli_fetch_assoc($result);
	}

	function inc_balance_query($customer_id, $value) {
		global $users_db_link;
		
		$result = mysqli_query($users_db_link,
			"UPDATE users 
			 SET balance = balance+$value 
			 WHERE id = $customer_id;"			
		);

		if (!$result || !mysqli_affected_rows($users_db_link)) {
			return 0;
		}

		$result = mysqli_query($users_db_link,
			"SELECT balance FROM users WHERE id = $customer_id;"
		);

		if (!$result) {
			return 0;
		}

		$row = mysqli_fetch_array($result);
		return $row[0];
	}
?>