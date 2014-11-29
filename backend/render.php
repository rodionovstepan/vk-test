<?php

	function render_order($order, $is_customer) {
		$title = $order['title'];
		$content = $order['content'];
		$price = $order['price'];
		$id = $order['id'];

		echo "
		<div class=\"order\">
			<div class=\"order_title\"><b>$title</b></div>
			<div class=\"order_content\">$content</div>
			<div class=\"order_footer\">
				<div class=\"order_price\">$price</div>
			</div>
		</div>";
	}

?>