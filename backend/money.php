<?php

	function aos_money($val) {
		return is_float($val)
			? number_format($val, 2, '.', '')
			: str_replace(',', '.', $val);
	}

	function aos_money_compare($user_balance, $price) {
		$bc_balance = aos_money($user_balance);
		$bc_price = aos_money($price);

		return bccomp($bc_balance, $bc_price, 2);
	}

	function aos_money_mul($left, $right) {
		$bc_left = aos_money($left);
		$bc_right = aos_money($right);

		return bcmul($bc_left, $bc_right, 2);
	}

	function aos_money_sub($left, $right) {
		$bc_left = aos_money($left);
		$bc_right = aos_money($right);

		return bcsub($bc_left, $bc_right, 2);
	}

	function aos_money_add($left, $right) {
		$bc_left = aos_money($left);
		$bc_right = aos_money($right);

		return bcadd($bc_left, $bc_right, 2);
	}

?>