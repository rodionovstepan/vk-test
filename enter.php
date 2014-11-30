<?php
	
	require_once('backend/context.php');

	if (isset($context_user_role)) {
		header('Location: ' . url_by_role($context_user_role));
		exit();
	}

	if (!$is_ajax) {
		include 'html/no_context_page_header.html';
	}

?>

<div class="page_welcome">
	<div class="page_welcome_title">
		Вход в AOS
	</div>
	<div class="page_welcome_content">
		<div class="page_welcome_form">
			<div class="form_validation js_validation" style="display:none;"></div>
			<div class="form_item">
				<input type="text" id="email" name="email" placeholder="Электронная почта">
			</div>
			<div class="form_item">
				<input type="password" id="pwd" name="pwd" placeholder="Пароль">
			</div>
			<div class="form_item">
				<button type="button" class="main_button" onclick="aos.login();">Войти</button>
			</div>
			<div class="form_item">
				<a href="/">На главную</a>
			</div>
		</div>
	</div>
</div>
		
<?php

	if (!$is_ajax) {
		include 'html/no_context_page_footer.html';
	}

?>