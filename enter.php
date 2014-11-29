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
						<div class="form_validation js_validation" style="display:none;"></div>
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