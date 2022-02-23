(function () {
	var timeoutId = 0;

	function init() {
		window.addEventListener("mousemove", onMouseMove);
	}

	function onMouseMove(e) {
		var adminbarShown =
			document.documentElement.classList.contains("show-wpadminbar");
		var safeAreaHeight = adminbarShown ? 32 : 10;

		if (e.clientY > safeAreaHeight) {
			if (!adminbarShown) {
				if (timeoutId) clearTimeout(timeoutId);
				timeoutId = 0;
				return;
			}

			if (e.target.classList.contains('ab-item') || e.target.parentNode.classList.contains('ab-submenu')) {
				if (timeoutId) clearTimeout(timeoutId);
				timeoutId = 0;
				return;
			}

			timeoutId = setTimeout(
				hideAdminBar,
				swiftControlAdminBarOpts.transitionDelay
			);

			return;
		}

		clearTimeout(timeoutId);
		showAdminBar();
	}

	function hideAdminBar() {
		document.documentElement.classList.remove("show-wpadminbar");
	}

	function showAdminBar() {
		document.documentElement.classList.add("show-wpadminbar");
	}

	init();
})();
