<?php

	require_once('backend/context.php');

	if (empty($context_user_id) || $context_user_role != CONTRACTOR_ROLE) {
		header('Location: /');
		exit();
	}

	require_once('backend/db/connect.php');
	require_once('backend/db/users_queries.php');
	require_once('backend/db/orders_queries.php');

	db_connect2();

	$user_info = get_user_info_query($context_user_id);
	if ($user_info == NULL) {
		header('Location: error.php');
		exit();
	}

	$orders = get_contractor_active_orders_query();
	$order_count = $user_info['order_count'];
	$balance = $user_info['balance'];

	if (!$is_ajax) {
		include 'html/context_page_header.html';
	}
?>
<div class="page_content_wrapper">
	<div class="page_side">
		<div class="page_menu">
			<div class="page_menu_title">
				<b><?= $user_info['username'] ?></b>
			</div>
			<div>
				Баланс: <span id="contractor_balance"><?= $balance ?></span><br/><br/>
				Выполненных заказов: <span id="contractor_order_count"><?= $order_count ?></span>
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
					echo 'Нет активных заказов';
				}
			?>
		</div>
	</div>
</div>
<?php
	
	if (!$is_ajax) {
		include 'html/context_page_footer.html';
	}

?>