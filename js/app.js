var aos = aos || {};

aos.showFormValidation = function(msg) {
	$('.form_validation').text(msg).fadeIn();
};

aos.isValidEmail = function(email) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
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