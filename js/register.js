var aos = aos || {};

aos.register = function() {
	var username = $('#username').val(),
		email = $('#email').val(),
		pwd = $('#pwd').val(),
		repwd = $('#repwd').val(),
		role = $('#role').val();

	var showValidation = function(msg) {
		$('.form_validation').text(msg).show();
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

	
};