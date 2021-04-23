(function ($) {
	var ajax = {};

	function init() {
		window.addEventListener('load', function (e) {
			setupNoticeDismissal();
		});
	}

	function setupNoticeDismissal() {
		var panel = document.querySelector('.swift-control-discontinue-notice.is-dismissible');
		if (!panel) return;
		panel.querySelector('.notice-dismiss').addEventListener('click', ajax.saveDismissal);
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