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

	if (username.trim() == '' || email.trim() == '') {
		showValidation(aos.lang.username_and_email_are_required);
		username.focus();
		return false;
	}
};