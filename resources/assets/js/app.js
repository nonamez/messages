window.$ = window.jQuery = require('jquery');

jQuery(document).ready(function() {
	let div_comment_container = jQuery('#div-comment-container');

	jQuery('#button-submit-comment').click(function() {
		let data = div_comment_container.find('[name]').serializeArray();

		jQuery.post('/comments/submit', data, function(response) {
			
		})
	})
})