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
	<div class="page_welcome_title">Добро пожаловать!</div>
	<div class="page_welcome_desc">
		AOS &mdash; это система выполнения абстрактных заказов, где "исполнители" выполняют заказы "заказчиков".
	</div>
	<div class="page_welcome_content">
		<div class="page_welcome_content_title">
			<b>Присоединяйся</b>, пройдя очень простую процедуру регистрации:
		</div>
		<form class="page_welcome_form" action="users.php" method="POST" onsubmit="return false;">
			<div class="form_validation js_validation" style="display:none;"></div>
			<div class="form_item">
				<input type="text" id="username" name="username" maxlength="30" placeholder="Имя пользователя">
			</div>
			<div class="form_item">
				<input type="text" id="email" name="email" placeholder="Электронная почта">
			</div>
			<div class="form_item">
				<input type="password" id="pwd" name="pwd" placeholder="Пароль">
			</div>
			<div class="form_item">
				<input type="password" id="repwd" name="repwd" placeholder="Пароль еще раз">
			</div>
			<div class="form_item">
				<select name="role" id="role">
					<option value="1">Я - заказчик</option>
					<option value="2">Я - исполнитель</option>
				</select>
			</div>
			<input type="hidden" name="act" value="register">
			<div class="form_item">
				<button type="submit" class="main_button" onclick="return aos.register();">Зарегистрироваться</button>
			</div>
			<div class="form_item">
				<a href="enter.php">Войти</a>
			</div>
		</form>
	</div>
</div>

<?php

	if (!$is_ajax) {
		include 'html/no_context_page_footer.html';
	}

?>