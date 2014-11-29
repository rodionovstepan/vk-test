var aos = aos || {};

aos.showFormValidation = function(msg) {
	$('.js_validation').text(msg).fadeIn();
};

aos.isValidEmail = function(email) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
};

aos.isValidOrderPrice = function(price) {
	if (typeof price !== 'string')
		return false;

	var re = /^\d{1,15}([\,\.]\d{1,2})?$/;
	return re.test(price);
};

aos.decrement = function(id, val) {
	var el = document.getElementById(id);
	if (el != undefined) {
		var count = parseInt(el.innerText);
		if (!isNaN(count) && count > 0) {
			el.innerText = val == undefined ? count-1 : val;
		}
	}
};

aos.increment = function(id, val) {
	var el = document.getElementById(id);
	if (el != undefined) {
		var count = parseInt(el.innerText);
		if (!isNaN(count) && count > 0) {
			el.innerText = val == undefined ? count+1 : val;
		}
	}
};

aos.showTempError = function(el, nowtext, latertext) {
	if (el != undefined) {
		var $el = $(el);

		$el.text(nowtext);
		setTimeout(function() {
			$el.text(latertext);
		}, 2000);
	}
};

aos.register = function() {
	var username = $('#username').val(),
		email = $('#email').val(),
		pwd = $('#pwd').val(),
		repwd = $('#repwd').val(),
		role = $('#role').val();

	if (username.trim() == '' || email.trim() == '') {
		aos.showFormValidation(aos.lang.username_and_email_are_required);
		return false;
	}

	if (!aos.isValidEmail(email)) {
		aos.showFormValidation(aos.lang.invalid_email);
		return false;
	}

	if (pwd.length < 5) {
		aos.showFormValidation(aos.lang.small_pwds);
		return false;
	}

	if (pwd !== repwd) {
		aos.showFormValidation(aos.lang.pwds_not_equals);
		return false;
	}

	$.post('/register.php', {
		username: username,
		email: email,
		pwd: pwd,
		repwd: repwd,
		role: role
	}, function (data) {
		if (data.success) {
			window.location = data.url;
			return;
		}

		switch (data.code) {
			case 1: 
				aos.showFormValidation(aos.lang.all_fields_are_required);
				break;
			case 2: 
				aos.showFormValidation(aos.lang.invalid_username);
				break;
			case 3: 
				aos.showFormValidation(aos.lang.invalid_email);
				break;
			case 4: 
				aos.showFormValidation(aos.lang.invalid_pwds);
				break;
			case 5: 
				aos.showFormValidation(aos.lang.invalid_role);
				break;
			case 6: 
				aos.showFormValidation(aos.lang.user_already_registered);
				break;
			default: 
				aos.showFormValidation(aos.lang.something_goes_wrong);
		}
	});
};

aos.login = function() {
	var email = $('#email').val(),
		pwd = $('#pwd').val();

	if (email.trim() == '' || pwd.trim() == '') {
		aos.showFormValidation(aos.lang.username_and_email_are_required);
		return false;
	}

	if (!aos.isValidEmail(email)) {
		aos.showFormValidation(aos.lang.invalid_email);
		return false;
	}

	if (pwd.length < 5) {
		aos.showFormValidation(aos.lang.small_pwds);
		return false;
	}

	$.post('login.php', {
		email: email,
		pwd: pwd
	}, function(data) {
		if (data.success) {
			window.location = data.url;
		}

		switch (data.code) {
			case 1: 
				aos.showFormValidation(aos.lang.invalid_email);
				break;
			case 2:
				aos.showFormValidation(aos.lang.small_pwds);
				break;
			case 3:
				aos.showFormValidation(aos.lang.user_not_registered);
				break;
		}
	});
};

aos.goToCustomer = function() {
	window.location = 'customer.php';
};

aos.addOrder = function() {
	var title = $('#order_title').val(),
		content = $('#order_content').val(),
		price = $('#order_price').val();

	if (title.trim() == '') {
		aos.showFormValidation(aos.lang.order_title_is_required);
		return false;
	}

	if (content.trim() == '') {
		aos.showFormValidation(aos.lang.order_content_is_required);
		return false;
	}

	if (price.trim() == '') {
		aos.showFormValidation(aos.lang.order_price_is_required);
		return false;
	}

	var pp = parseFloat(price.replace(',', '.'));
	if (!aos.isValidOrderPrice(price) || isNaN(pp)) {
		aos.showFormValidation(aos.lang.invalid_order_price);
		return false;
	} else if (pp == 0) {
		aos.showFormValidation(aos.lang.negative_order_price);
		return false;
	}

	$.post('orders.php', {
		act: 'add_order',
		title: title,
		content: content,
		price: pp
	}, function(data) {
		if (data.success) {
			window.location = 'customer.php';
		}

		switch (data.code) {
			case 1:
				aos.showFormValidation(aos.lang.all_fields_are_required);
				break;
			case 2:
				aos.showFormValidation(aos.lang.invalid_order_price);
				break;
			case 3:
				aos.showFormValidation(aos.lang.too_small_balance);
				break;
			default:
				aos.showFormValidation(aos.lang.something_goes_wrong);
		}
	});

};

aos.cancelOrder = function(el, id) {
	id = parseInt(id);
	if (id <= 0)
		return;

	$.post('orders.php', {
		act: 'cancel_order',
		oid: id
	}, function(data) {
		if (data.success) {
			aos.decrement('customer_order_count');
			aos.increment('customer_balance', data.balance);

			$('#order' + id).fadeOut(function() {
				$(this).remove();

				if ($('.order').length === 0) {
					$('#page_content_wrapper').text(aos.lang.no_active_orders);
				}
			});
		} else {
			aos.showTempError(el, 
				aos.lang.cannot_cancel_order,
				aos.lang.cancel_order);
		}
	});
};

aos.incBalance = function(el) {
	$.post('users.php', {
		act: 'inc_balance'
	}, function (data) {
		if (data.success) {
			$('#customer_balance').text(data.balance);
		} else {
			aos.showTempError(el,
				aos.lang.cannot_inc_balance,
				aos.lang.inc_balance);
		}
	});
};