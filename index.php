<?php

	require_once('backend/context.php');

	if (isset($context_user_role)) {
		header('Location: ' . url_by_role($context_user_role));
		exit();
	}

?>
<!doctype html>
<html>
	<head>
		<title>Abstract Order System | VK Test</title>
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
						Abstract Order System
					</div>
				</div>
			</div>
		</div>
		<div class="page_container" id="content">
			<div class="page_welcome">
				<div class="page_welcome_title">Добро пожаловать!</div>
				<div class="page_welcome_desc">
					AOS &mdash; это система выполнения абстрактных заказов, где "исполнители" выполняют заказы "заказчиков".
				</div>
				<div class="page_welcome_content">
					<div class="page_welcome_content_title">
						<b>Присоединяйся</b>, пройдя очень простую процедуру регистрации:
					</div>
					<div class="page_welcome_form">
						<div class="form_validation js_validation" style="display:none;"></div>
						<div class="form_item">
							<input type="text" id="username" name="username" maxlength="30" placeholder="Имя пользователя">
						</div>
						<div class="form_item">
							<input type="text" id="email" name="email" placeholder="Электронная почта">
						</div>
						<div class="form_item">
							<input type="password" id="pwd" name="pwd" placeholder="Пароль">
						</div>
						<div class="form_item">
							<input type="password" id="repwd" name="repwd" placeholder="Пароль еще раз">
						</div>
						<div class="form_item">
							<select name="role" id="role">
								<option value="1">Я - заказчик</option>
								<option value="2">Я - исполнитель</option>
							</select>
						</div>
						<div class="form_item">
							<button type="button" class="main_button" onclick="aos.register();">Зарегистрироваться</button>
						</div>
						<div class="form_item">
							<a href="enter.php">Войти</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="page_footer">
			&copy; 2014
		</div>

		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/lang.js"></script>
		<script type="text/javascript" src="js/app.js"></script>
	</body>
</html>