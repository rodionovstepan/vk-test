<?php
	
	function is_user_registered($email) {
		$result = mysql_query("SELECT count(id) FROM users WHERE email = '" . $email . "';");
		
		if (!$result) {
			die(mysql_error());
		}

		return mysql_result($result, 0) > 0;
	}

	function register_user($username, $email, $pwd, $role) {
		$pwdhash = md5(md5($pwd));

		$result = mysql_query(
			"INSERT INTO users (username, email, password_hash, role) 
			 VALUES ('" . $username . "', '" . $email . "', '" . $pwdhash . "', " . $role . ");");

		if (!$result) {
			die(mysql_error());
		}

		return mysql_insert_id();
	}
?>