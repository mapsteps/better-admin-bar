(function () {
	var timeoutId = 0;

	function init() {
		window.addEventListener("mousemove", onMouseMove);
	}

	function onMouseMove(e) {
		var adminbarShown =
			document.documentElement.classList.contains("show-wpadminbar");
		var limit = adminbarShown ? 32 : 5;

		if (e.clientY > limit) {
			if (timeoutId && !adminbarShown) clearTimeout(timeoutId);

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
