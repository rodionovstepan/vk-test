<?php
	
	require_once('backend/context.php');
	
	if (!isset($context_user_id)) {
		header('Location: /');
		exit();
	} 

	require_once('backend/db/connect.php');
	require_once('backend/db/users_queries.php');

	db_connect();

	$user_info = get_user_info($context_user_id);
	if ($user_info == NULL) {
		header('Location: error.php');
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
						<a href="logout.php">
							Выход
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="page_container" id="content">
			<div class="page_content_wrapper">
				<div class="page_side">
					<div class="page_menu">
						<div class="page_menu_title">
							<b><?php echo $context_user_name ?></b>
						</div>
						<div>
							Баланс: <?php echo $user_info['balance'] ?><br/><br/>
							Опубликовано заказов: <?php echo $user_info['order_count'] ?>
						</div>
					</div>
					<div class="page_menu">
						<button type="button" class="main_button balance_button">Пополнить баланс</button>
					</div>
				</div>
				<div class="page_content">
					<div class="page_content_title">
						<b>Список твоих активных заказов</b>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>