<?php

	if (isset($_POST['username']) && isset($_POST['pwd'])) {
		header('Content-Type: application/json;');

		$email = trim(mysql_real_escape_string($_POST['email']));
		$pwd = $_POST['pwd'];

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo json_encode(array('success' => false, 'code' => 1));
			exit();
		}

		if (strlen($pwd) < 5) {
			echo json_encode(array('success' => false, 'code' => 2));
			exit();
		}

		require_once('backend/db/connect.php');	
		require_once('backend/db/users_queries.php');

		db_connect();

		$user = user_by_email_pwd($email, $pwd);
		if ($user == NULL) {
			echo json_encode(array('success' => false, 'code' => 3));
			exit();
		}

		echo json_encode(array('success' => true, 'url' => '/'));
		exit();
	}

?>


<!doctype html>
<html>
	<head>
		<title>Abstract Order System | Log In</title>
		<meta charset="utf-8"> 
		<link rel="stylesheet" href="css/common.css">
	</head>
	<body>
		<div class="navbar">
			<div class="page_container">
				<div class="navbar_container">
					<div class="navbar_left">
						<a href="/">AOS | VK Test</a>
					</div>
					<div class="navbar_right">
						Вход в Abstract Order System
					</div>
				</div>
			</div>
		</div>
		<div class="page_container" id="content">
			<div class="page_welcome">
				<div class="page_welcome_title">
					Вход в AOS
				</div>
				<div class="page_welcome_content">
					<div class="page_welcome_form">
						<div class="form_validation" style="display:none;"></div>
						<div class="form_item">
							<input type="text" id="email" name="email" placeholder="Электронная почта">
						</div>
						<div class="form_item">
							<input type="password" id="pwd" name="pwd" placeholder="Пароль">
						</div>
						<div class="form_item">
							<button type="button" class="main_button" onclick="aos.login();">Войти</button>
						</div>
						<div class="form_item">
							<a href="/">На главную</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="page_footer">
			&copy; 2014
		</div>

		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/app.js"></script>
		<script type="text/javascript" src="js/lang.js"></script>
	</body>
</html>