window.$ = window.jQuery = require('jquery');

jQuery(document).ready(function() {
	let div_comment_container = jQuery('#div-comment-container'),
		ul_comments_list      = jQuery('#ul-comments-list');

	jQuery('#button-submit-comment').click(function(event) {
		// Preventing default form action. In case of JS off after submit site will just reload
		event.preventDefault();

		div_comment_container.find('> p.err').removeClass('err');

		let data = div_comment_container.find('[name]').serializeArray();

		// may be moved to ajaxStart
		div_comment_container.find('input,textarea').prop('disabled', true);
		div_comment_container.find('img').show();

		jQuery.post('/comments/submit', data, function(response) {
			div_comment_container[0].reset();
			
			if (ul_comments_list.find('li').length >= _PER_PAGE) {
				ul_comments_list.find('li:last').remove();
			}

			let container = jQuery('<li/>').prependTo(ul_comments_list);

			jQuery('<span/>').text(response.created_at).appendTo(container);

			if (response.email) {
				jQuery('<a/>').attr('href', 'mailto:' + response.email).text(response.fullname).appendTo(container);
			} else {
				container.append(response.fullname);
			}

			container.append(`, ${response.birthdate} m.`);

			jQuery('<br/>').appendTo(container);

			container.append(response.message);
		}).fail(function(response) {
			// may be moved to ajaxError
			if (response.status == 422) {
				for (let name of response.responseJSON) {
					div_comment_container.find(`[name="${name}"]`).closest('p').addClass('err');
				}
			}
		}).always(function() {
			// may be moved to ajaxStop
			div_comment_container.find('input,textarea').prop('disabled', false);
			div_comment_container.find('img').hide();
		})
	})
})