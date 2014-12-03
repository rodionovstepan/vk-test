<?php
	
	require_once('backend/ajax_handler.php');
	require_once('backend/handlers/orders_handler.php');

	if (empty($context_user_id) || empty($context_user_token) || $context_user_token != $token) {
		invalid_request();
	}

	if ($act == 'add_order') {

		$title = trim(mysql_real_escape_string(htmlspecialchars($_POST['title'])));
		$content = trim(mysql_real_escape_string(htmlspecialchars($_POST['content'])));
		$price = $_POST['price'];

		echo json_encode(add_order_handler($title, $content, $price));
	} else if ($_POST['act'] == 'cancel_order') {

		echo json_encode(cancel_order_handler(intval($_POST['oid'])));
	} else if ($_POST['act'] == 'take_order') {

		echo json_encode(take_order_handler(intval($_POST['oid'])));
	} else {
		
		invalid_request();
	}

?>