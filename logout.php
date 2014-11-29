<?php

	require_once('backend/auth.php');

	logout_user();

	header('Location: /');

?>