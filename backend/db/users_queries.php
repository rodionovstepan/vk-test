<?php
	
	function register_user_query($username, $email, $email_hash, $pwd, $role) {
		global $users_db_link;

		$pwd_hash = md5(md5($pwd));

		start_transaction($users_db_link);

		$exists = select_query(
			"SELECT count(id) FROM users WHERE email_hash = ?", 
			array('s', $email_hash), $users_db_link
		);

		if (empty($exists)) {
			return 0;
		} else if ($exists[0][0]) {
			return -6;
		}

		$inserted_id = insert_query(
			"INSERT INTO users (username, email, email_hash, password_hash, role) VALUES (?, ?, ?, ?, ?)",
			array('ssssi', $username, $email, $email_hash, $pwd_hash, $role), $users_db_link
		);

		if (!$inserted_id) {
			rollback_transaction($users_db_link);
			return 0;
		}

		commit_transaction($users_db_link);

		return $inserted_id;
	}

	function get_user_by_email_pwd_query($email, $pwd) {
		global $users_db_link;

		$email_hash = md5($email);
		$pwd_hash = md5(md5($pwd));

		$select = select_query(
			"SELECT id, username, role FROM users WHERE email_hash = ? AND password_hash = ?",
			array('ss', $email_hash, $pwd_hash), $users_db_link
		);

		if (empty($select)) {
			return NULL;
		}

		return $select[0];
	}

	function get_user_info_query($id) {
		global $users_db_link;

		$select = select_query(
			"SELECT balance, order_count, username FROM users WHERE id = ?",
			array('i', $id), $users_db_link
		);

		if (empty($select)) {
			return NULL;
		}

		return $select[0];
	}

	function inc_balance_query($customer_id, $value) {
		global $users_db_link;
		
		$update = update_query(
			"UPDATE users SET balance = balance + ? WHERE id = ?",
			array('di', $value, $customer_id), $users_db_link
		);

		if (!$update) {
			return 0;
		}

		$select = select_query(
			"SELECT balance FROM users WHERE id = ?", 
			array('i', $customer_id), $users_db_link
		);

		if (empty($select)) {
			return 0;
		}

		return $select[0]['balance'];
	}

?>