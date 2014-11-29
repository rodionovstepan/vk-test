<?php

	require_once('backend/auth.php');

	logoutUser();
	
	header('Location: /');

?>