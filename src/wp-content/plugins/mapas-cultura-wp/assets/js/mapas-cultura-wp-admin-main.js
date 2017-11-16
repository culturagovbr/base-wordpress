jQuery(document).ready(function($) {
	$('.mce-mcwp-layout-cols').mask('0', {
		translation: {
			'0': {
				pattern: /[1-4]/
			}
		},
	});
	$('.mce-mcwp-limit').mask('000');
});