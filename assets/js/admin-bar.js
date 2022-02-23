(function () {
	var timeoutId = 0;

	function init() {
		window.addEventListener("mousemove", onMouseMove);
	}

	function onMouseMove(e) {
		var adminbarShown =
			document.documentElement.classList.contains("show-wpadminbar");
		var safeAreaHeight = adminbarShown ? 200 : 10;

		if (e.clientY > safeAreaHeight) {
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
