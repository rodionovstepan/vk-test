<?php
	
	require_once('backend/context.php');
	
	if (!isset($context_user_id)) {
		header('Location: /');
		exit();
	}

	$user_id = $context_user_id;
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);

		if ($id > 0) {
			$user_id = $id;
		}
	}


	$add_order = $_GET['act'] == 'add_order';

	require_once('backend/db/connect.php');
	require_once('backend/db/users_queries.php');
	require_once('backend/db/orders_queries.php');

	db_connect();

	$user_info = get_user_info($context_user_id);
	if ($user_info == NULL) {
		header('Location: error.php');
		exit();
	}

	$orders = get_customer_active_orders($context_user_id);

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
						<?php 
							if (!$add_order) {
								echo '
								<div class="page_menu_item">
									<a href="customer.php?act=add_order" class="main_button menu_button">Создать заказ</a>
								</div>';
							}
						?>
						<div  class="page_menu_item">
							<button type="button" class="def_button menu_button">Пополнить баланс</button>
						</div>
					</div>
				</div>
				<div class="page_content">
					<div class="page_content_title">
						<?php
							if (!$add_order) {
								echo '<b>Список твоих активных заказов</b>';
							} else {
								echo '<b>Добавление нового заказа</b>';
							}
						?>
					</div>
					<div>
						<?php
							if ($add_order) {
								require 'html/new_order_form.html';
							}
						?>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="js/customer.js"></script>
	</body>
</html>