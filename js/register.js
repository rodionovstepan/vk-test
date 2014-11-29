var aos = aos || {};

aos.register = function() {
	var username = $('#username').val(),
		email = $('#email').val(),
		pwd = $('#pwd').val(),
		repwd = $('#repwd').val(),
		role = $('#role').val();

	var showValidation = function(msg) {
		$('.form_validation').text(msg).fadeIn();
	};

	var validateEmail = function(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	};

	if (username.trim() == '' || email.trim() == '') {
		showValidation(aos.lang.username_and_email_are_required);
		return false;
	}

	if (!validateEmail(email)) {
		showValidation(aos.lang.invalid_email);
		return false;
	}

	if (pwd.length < 5) {
		showValidation(aos.lang.small_pwds);
		return false;
	}

	if (pwd !== repwd) {
		showValidation(aos.lang.pwds_not_equals);
		return false;
	}

	$.post('/register.php', {
		username: username,
		email: email,
		pwd: pwd,
		repwd: repwd,
		role: role
	}, function (data) {
		if (data.success === true) {
			location.reload();
			return;
		}

		switch (data.code) {
			case 1: showValidation(aos.lang.all_fields_are_required);
			case 2: showValidation(aos.lang.invalid_pwds);
			case 3: showValidation(aos.lang.invalid_role);
			case 4: showValidation(aos.lang.user_already_registered);
			default: showValidation(aos.lang.something_goes_wrong);
		}
	});
};