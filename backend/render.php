<?php

	function render_order($order, $is_customer, $context_user_id) {
		$title = $order['title'];
		$content = nl2br($order['content']);
		$price = $order['price'];
		$id = $order['id'];
		$customer_name = $order['customer_name'];
		$customer_id = $order['customer_id'];

		$btn = "";
		if ($context_user_id == $customer_id) {
			$btn = "<button type=\"button\" class=\"def_button\" onclick=\"aos.cancelOrder(this, $id);\">Удалить заказ</button>";
		} else if (!$is_customer) {
			$btn = "<button type=\"button\" class=\"main_button\" onclick=\"aos.takeOrder(this, $id);\">Выполнить заказ</button>";
		}

		echo "
		<div class=\"order\" id=\"order$id\">
			<div class=\"order_title\"><b>$title</b> добавил <a href=\"customer.php?id=$customer_id\">$customer_name</a></div>
			<div class=\"order_content\">$content</div>
			<div class=\"order_footer\">
				<div class=\"order_price fl_l\">Вознаграждение: <b>$price</b></div>
				<div class=\"fl_r\">" . $btn . "</div>
			</div>
		</div>";
	}

?>