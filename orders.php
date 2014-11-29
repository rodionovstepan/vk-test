<?php
	
	require_once('backend/ajax_handler.php');
	require_once('backend/handlers/orders_handler.php');

	if (empty($context_user_id)) {
		invalid_request();
		exit();
	}

	if ($act == 'add_order') {
		$title = trim(mysql_real_escape_string(htmlspecialchars($_POST['title'])));
		$content = trim(mysql_real_escape_string(htmlspecialchars($_POST['content'])));
		$price = floatval($_POST['price']);

		echo json_encode(add_order_handler($title, $content, $price));
	} else if ($_POST['act'] == 'cancel_order') {
		$id = intval($_POST['oid']);

		echo json_encode(cancel_order_handler($id));
	} else if ($_POST['act'] == 'take_order') {
		$id = intval($_POST['oid']);

		echo json_encode(take_order_handler($id));
	} else {
		invalid_request();
	}

?>