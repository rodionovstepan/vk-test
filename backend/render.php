<?php

	function render_order($order, $is_customer) {
		$title = $order['title'];
		$content = nl2br($order['content']);
		$price = $order['price'];
		$id = $order['id'];

		echo "
		<div class=\"order\" id=\"order$id\">
			<div class=\"order_title\"><b>$title</b></div>
			<div class=\"order_content\">$content</div>
			<div class=\"order_footer\">
				<div class=\"order_price fl_l\">Вознаграждение: <b>$price</b></div>
				<div class=\"fl_r\">
					<button type=\"button\" class=\"def_button\" onclick=\"aos.cancelOrder(this, $id);\">Удалить заказ</button>
				</div>
			</div>
		</div>";
	}

?>