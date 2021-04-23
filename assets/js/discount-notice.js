(function ($) {
	var ajax = {};

	function init() {
		window.addEventListener('load', function (e) {
			// Give some time-out to wait for WordPress to do their admin notice works.
			setTimeout(setupNoticeDismissal, 150);
		});
	}

	function setupNoticeDismissal() {
		var dismissButton = document.querySelector('.swift-control-discontinue-notice.is-dismissible .notice-dismiss');
		if (!dismissButton) return;
		dismissButton.addEventListener('click', ajax.saveDismissal);
	}

	ajax.saveDismissal = function (e) {
		$.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				action: 'sc_discount_notice_dismissal',
				nonce: swiftControlDismissal.nonces.dismissalNonce,
				dismiss: 1
			}
		}).always(function (r) {
			if (r.success) console.log(r.data);
		});
	};

	init();
})(jQuery);