window.$ = window.jQuery = require('jquery');

jQuery(document).ready(function() {
	let div_comment_container = jQuery('#div-comment-container');

	jQuery('#button-submit-comment').click(function() {
		div_comment_container.find('> p.err').removeClass('err');
		
		let data = div_comment_container.find('[name]').serializeArray();

		jQuery.post('/comments/submit', data, function(response) {
			console.log(response)
		}).fail(function(response) {
			if (response.status == 422) {
				for (let name of response.responseJSON) {
					div_comment_container.find(`[name="${name}"]`).closest('p').addClass('err');
				}
			}
		})
	})
})