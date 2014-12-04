/*
Anti-spam plugin
No spam in comments. No captcha.
wordpress.org/plugins/anti-spam/
*/

(function($) {

	function anti_spam_init() {
		$('.antispam-group').hide(); // hide inputs from users

		var answer = $('.antispam-group .antispam-control-a').val(); // get answer
		$('.antispam-group-q .antispam-control-q').val(answer); // set answer into other input instead of user
		$('.antispam-group-e .antispam-control-e').val(''); // clear value of the empty input because some themes are adding some value for all inputs

		var current_date = new Date();
		var current_year = current_date.getFullYear();
		var dynamic_control = '<input type="hidden" name="antspm-q" class="antispam-control-q" value="'+current_year+'" />';

		if ($('#comments form .antispam-control-q').length == 0) { // anti-spam input does not exist (could be because of cache or because theme does not use 'comment_form' action)
			$('#comments form').append(dynamic_control); // add whole input with answer via js to comment form
		}

		if ($('#respond form .antispam-control-q').length == 0) { // similar, just in case (used because user could bot have #comments)
			$('#respond form').append(dynamic_control); // add whole input with answer via js to comment form
		}

		if ($('form#commentform .antispam-control-q').length == 0) { // similar, just in case (used because user could bot have #respond)
			$('form#commentform').append(dynamic_control); // add whole input with answer via js to comment form
		}
	}

	$(document).ready(function() {
		anti_spam_init();
	});

	$(document).ajaxSuccess(function() { // add support for comments forms loaded via ajax
		anti_spam_init();
	});

})(jQuery);