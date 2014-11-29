<?php

	require_once('backend/context.php');

	if (!isset($context_user_id)) {
		header('Location: /');
		exit();
	}

	require_once('backend/db/connect.php');
	require_once('backend/db/users_queries.php');
	require_once('backend/db/orders_queries.php');

	db_connect();

	$user_info = get_user_info($context_user_id);
	if ($user_info == NULL) {
		header('Location: error.php');
		exit();
	}

	$orders = get_contractor_active_orders();
	$order_count = $user_info['order_count'];
	$balance = $user_info['balance'];
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
							Баланс: <span id="contractor_balance"><?= $balance ?></span><br/><br/>
							Выполнено заказов: <span id="contractor_order_count"><?= $order_count ?></span>
						</div>
					</div>
				</div>
				<div class="page_content">
					<div class="page_content_title">
						<b>Список доступных заказов</b>
					</div>
					<div id="page_content_wrapper">
						<?php
							require_once 'backend/render.php';

							if (count($orders)) {
								foreach ($orders as $order) {
									render_order($order, false, $context_user_id);
								}
							} else {
								echo 'Нет доступных заказов';
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