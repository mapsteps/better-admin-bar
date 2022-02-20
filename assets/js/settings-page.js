/**
 * This script is intended to handle the settings page.
 *
 * @param {Object} $ jQuery object.
 * @return {Object}
 */
(function ($) {
	/**
	 * Initialize the module, call the main functions.
	 *
	 * This function is the only function that should be called on top level scope.
	 * Other functions are called / hooked from this function.
	 */
	function init() {
		setupTabsNavigation();
	}

	/**
	 * Setup the tabs navigation for settings page.
	 */
	function setupTabsNavigation() {
		$(".heatbox-tab-nav-item").on("click", function () {
			$(".heatbox-tab-nav-item").removeClass("active");
			$(this).addClass("active");

			var link = this.querySelector("a");
			var hashValue = link.href.substring(link.href.indexOf("#") + 1);

			if ("tools" === hashValue) {
				$(".general-settings-area .submit").hide();
			} else {
				$(".general-settings-area .submit").show();
			}

			$(".heatbox-form-container .heatbox-admin-panel").css("display", "none");

			$(".heatbox-form-container .swift-control-" + hashValue + "-panel").css(
				"display",
				"block"
			);
		});

		window.addEventListener("load", function () {
			var hashValue = window.location.hash.substring(1);

			if (!hashValue) {
				hashValue = "settings";
			}

			if ("tools" === hashValue) {
				$(".general-settings-area .submit").hide();
			} else {
				$(".general-settings-area .submit").show();
			}

			$(".heatbox-tab-nav-item").removeClass("active");
			$(".heatbox-tab-nav-item.swift-control-" + hashValue + "-panel").addClass(
				"active"
			);

			$(".heatbox-form-container .heatbox-admin-panel").css("display", "none");

			$(".heatbox-form-container .swift-control-" + hashValue + "-panel").css(
				"display",
				"block"
			);
		});
	}

	// Run the module.
	init();
})(jQuery);
