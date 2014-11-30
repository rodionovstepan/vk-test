<?php

	require_once('backend/context.php');

	if (empty($context_user_id)) {
		header('Location: /');
		exit();
	}

	if (!$is_ajax) {
		include 'html/context_page_header.html';
	}

?>

<div class="page_welcome">
	<div class="page_welcome_title">Sorry!</div>
	<div class="page_welcome_desc">
		Странно, но произошла какая-то ошибка.. Мы уже работаем над ее исправлением!
	</div>
</div>

<?php
	
	if (!$is_ajax) {
		include 'html/context_page_footer.html';
	}

?>