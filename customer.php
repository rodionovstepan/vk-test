<?php
	
	require_once('backend/context.php');

	if (!isset($context_user_id)) {
		header('Location: /');
		exit();
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
						<a href="customer.php">
							<?php echo $context_user_name ?>
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
							Баланс: 4000<br/>
							Опубликовано заказов: 2
						</div>
					</div>
					<div class="page_menu">
						<div class="page_menu_title">
							<b>Пополнение баланса</b>
						</div>
						<div>
							
						</div>
					</div>
				</div>
				<div class="page_content">
					Content
				</div>
			</div>
		</div>
	</body>
</html>