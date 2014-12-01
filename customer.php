<?php
	
	require_once('backend/context.php');
	
	if (empty($context_user_id)) {
		header('Location: /');
		exit();
	}

	$user_id = $context_user_id;
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);

		if ($id > 0) {
			$user_id = $id;
		}
	} else if ($context_user_role != CUSTOMER_ROLE) {
		header('Location: /');
		exit();
	}

	$add_order = $_GET['act'] == 'add_order' && $context_user_role == CUSTOMER_ROLE;

	require_once('backend/db/connect.php');
	require_once('backend/db/users_queries.php');
	require_once('backend/db/orders_queries.php');

	db_connect();

	$user_info = get_user_info_query($user_id);
	if ($user_info == NULL) {
		header('Location: error.php');
		exit();
	}

	if (!$add_order) {
		$orders = get_customer_active_orders_query($user_id);
	}

	$balance = $user_info['balance'];
	$order_count = $user_info['order_count'];

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
				<?php 
					if ($context_user_id == $user_id) {
						echo "Баланс: <span id=\"customer_balance\">$balance</span><br/><br/>";
					}
				?>
				Опубликовал заказов: <span id="customer_order_count"><?= $order_count ?></span>
			</div>
		</div>
		<?php
			if ($context_user_id == $user_id) {
				if (!$add_order) {
					include 'html/context_customer_menu.html';
				} else {
					include 'html/add_order_customer_menu.html';
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
					include 'html/new_order_form.html';
				} else {
					require_once('backend/render.php');

					if (count($orders)) {
						foreach ($orders as $order) {
							render_order($order, $context_user_role == CUSTOMER_ROLE, $context_user_id);
						}
					} else {
						echo 'Нет активных заказов';
					}
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