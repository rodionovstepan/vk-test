var aos = aos || {};

aos.showFormValidation = function(msg) {
	$('.js_validation').text(msg).fadeIn();
};

aos.hideFormValidation = function() {
	$('.js_validation').hide();
}

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

aos.decrement = function(id) {
	var el = document.getElementById(id);
	if (el != undefined) {
		var count = parseInt(el.innerText);
		if (!isNaN(count)) {
			el.innerText = count-1;
		}
	}
};

aos.increment = function(id, val) {
	var el = document.getElementById(id);
	if (el != undefined) {
		if (val != undefined) {
			el.innerText = val;
		} else {
			var count = parseInt(el.innerText);
			if (!isNaN(count)) {
				el.innerText = count+1;
			}
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
	aos.hideFormValidation();

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

	$.post('users.php', {
		act: 'register',
		username: username,
		email: email,
		pwd: pwd,
		repwd: repwd,
		role: role
	}, function (data) {
		if (data.success) {
			return aos.go(data.url);
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
	aos.hideFormValidation();

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

	$.post('users.php', {
		act: 'login',
		email: email,
		pwd: pwd
	}, function(data) {
		if (data.success) {
			return aos.go(data.url);
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
			default:
				aos.showFormValidation(aos.lang.something_goes_wrong);
		}
	});
};

aos.addOrder = function() {
	aos.hideFormValidation();
	
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
		price: price
	}, function(data) {
		if (data.success) {
			return aos.go('customer.php');
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

aos.orderFadeOut = function($order) {
	$order.fadeOut(function() {
		$order.remove();

		if ($('.order').length === 0) {
			$('#page_content_wrapper').text(aos.lang.no_active_orders);
		}
	})
};

aos.cancelOrder = function(id) {
	$.post('orders.php', {
		act: 'cancel_order',
		oid: id
	}, function(data) {
		var $order = $('#order' + id);

		if (data.success) {
			aos.decrement('customer_order_count');
			aos.increment('customer_balance', data.balance);
			aos.orderFadeOut($order);
		} else {
			$order.addClass('order_danger');
			$('.order_content', $order).text(aos.lang.cannot_cancel_order);

			setTimeout(function() {
				aos.orderFadeOut($order);
			}, 5000);
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

aos.takeOrder = function(id) {
	$.post('orders.php', {
		act: 'take_order',
		oid: id
	}, function (data) {
		var $order = $('#order' + id);

		if (data.success) {
			aos.increment('contractor_balance', data.balance);
			aos.increment('contractor_order_count', data.ordercount);
			aos.orderFadeOut($order);
		} else {
			$order.addClass('order_danger');
			$('.order_content', $order).text(aos.lang.cannot_take_order);

			setTimeout(function() {
				aos.orderFadeOut($order);
			}, 5000);
		}
	});
};

aos.go = function (url, manual) {
	$.get(url, {}, function(html, status) {
		if (status === 'success') {
			$('#content').html(html);

			if (!manual) {
				aos.pushUrl(url);
			}
		}
	});

	return false;
};

aos.pushUrl = function (url) {
	window.history.pushState({ data: url }, document.title, url);
};

$(function() {
	$(document).on('click', 'a', function() {
		return aos.go($(this).attr('href'));
	});

	$(window).bind('popstate', function(e) {
		if (e.originalEvent.state && e.originalEvent.state.data) {
			aos.go(e.originalEvent.state.data, true);
		}
	});
});