<?php

	function db_connect() {
		mysql_connect('localhost', 'root', '') or die(mysql_error());
		mysql_select_db('vktest') or die(mysql_error());
		mysql_set_charset('utf-8');
	}

?>