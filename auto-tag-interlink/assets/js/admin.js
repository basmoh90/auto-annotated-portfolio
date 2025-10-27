/**
 * Admin JavaScript for Auto Tag Interlink Plugin
 *
 * @package AutoTagInterlink
 */

(function ($) {
	'use strict';

	/**
	 * Initialize when document is ready.
	 */
	$(document).ready(function () {
		// Handle clear cache button click
		$('#ati-clear-cache').on('click', function (e) {
			e.preventDefault();

			var $button = $(this);
			var $message = $('#ati-cache-message');

			// Disable button and show loading state
			$button.prop('disabled', true).addClass('loading');
			$message.text('').removeClass('success error');

			// Send AJAX request
			$.ajax({
				url: atiAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'ati_clear_cache',
					nonce: atiAdmin.nonce
				},
				success: function (response) {
					if (response.success) {
						$message
							.addClass('success')
							.text(response.data.message)
							.fadeIn();

						// Hide message after 3 seconds
						setTimeout(function () {
							$message.fadeOut();
						}, 3000);
					} else {
						$message
							.addClass('error')
							.text(response.data.message || 'An error occurred.')
							.fadeIn();
					}
				},
				error: function (xhr, status, error) {
					$message
						.addClass('error')
						.text('An error occurred: ' + error)
						.fadeIn();
				},
				complete: function () {
					// Re-enable button and remove loading state
					$button.prop('disabled', false).removeClass('loading');
				}
			});
		});

		// Add confirmation for disabling the plugin
		$('input[name="auto_tag_interlink_settings[enabled]"]').on('change', function () {
			if (!$(this).is(':checked')) {
				var confirm_disable = confirm(
					'Are you sure you want to disable auto-linking? ' +
					'Existing links will remain, but new links will not be created.'
				);

				if (!confirm_disable) {
					$(this).prop('checked', true);
				}
			}
		});

		// Validate max related posts input
		$('input[name="auto_tag_interlink_settings[max_related_posts]"]').on('change', function () {
			var value = parseInt($(this).val());
			var min = parseInt($(this).attr('min'));
			var max = parseInt($(this).attr('max'));

			if (value < min) {
				$(this).val(min);
				alert('Minimum value is ' + min);
			} else if (value > max) {
				$(this).val(max);
				alert('Maximum value is ' + max);
			}
		});

		// Ensure at least one post type is selected
		$('form').on('submit', function (e) {
			var checkedPostTypes = $('input[name="auto_tag_interlink_settings[post_types][]"]:checked').length;

			if (checkedPostTypes === 0) {
				e.preventDefault();
				alert('Please select at least one post type.');
				return false;
			}
		});

		// Add tooltips for better UX (if needed in future)
		$('.description').each(function () {
			var $this = $(this);
			var text = $this.text();

			// You can add tooltip functionality here if needed
		});
	});

})(jQuery);
