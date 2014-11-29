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

	$user_info = get_user_info($user_id);
	if ($user_info == NULL) {
		header('Location: error.php');
		exit();
	}

	if (!$add_order) {
		$orders = get_customer_active_orders($user_id);
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
							<b><?= $user_info['username'] ?></b>
						</div>
						<div>
							Баланс: <span id="customer_balance"><?= $user_info['balance'] ?></span><br/><br/>
							Всего заказов: <span id="customer_order_count"><?= $user_info['order_count'] ?></span><br/><br/>
						</div>
					</div>
					<?php
						if ($context_user_id == $user_id) {
							if (!$add_order) {
								require 'html/context_customer_menu.html';
							} else {
								require 'html/add_order_customer_menu.html';
							}
						}
					?>
				</div>
				<div class="page_content">
					<div class="page_content_title">
						<?php
							if (!$add_order) {
								echo '<b>Список активных заказов</b>';
							} else {
								echo '<b>Добавление нового заказа</b>';
							}
						?>
					</div>
					<div id="page_content_wrapper">
						<?php
							if ($add_order) {
								require 'html/new_order_form.html';
							} else {
								require_once 'backend/render.php';

								if (count($orders)) {
									foreach ($orders as $order) {
										render_order($order, $user_id == $context_user_id);
									}
								} else {
									echo 'Нет активных заказов';
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/lang.js"></script>
		<script type="text/javascript" src="js/app.js"></script>
	</body>
</html>