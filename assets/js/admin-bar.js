(function () {
	var timeoutId = 0;

	function init() {
		window.addEventListener("mousemove", onMouseMove);
	}

	function onMouseMove(e) {
		var adminbarShown =
			document.documentElement.classList.contains("show-wpadminbar");
		var safeAreaHeight = adminbarShown ? 32 : 16;

		if (e.clientY > safeAreaHeight) {
			if (!adminbarShown) {
				if (timeoutId) clearTimeout(timeoutId);
				timeoutId = 0;
				return;
			}

			timeoutId = setTimeout(
				hideAdminBar,
				swiftControlAdminBarOpts.transitionDelay,
				e.target
			);

			return;
		}

		clearTimeout(timeoutId);
		showAdminBar();
	}

	function hideAdminBar(target) {
		if (
			target.parentNode.classList.contains("ab-item") ||
			target.classList.contains("ab-item") ||
			target.parentNode.classList.contains("ab-submenu") ||
			target.parentNode.classList.contains("ab-sub-wrapper") ||
			target.parentNode.classList.contains("menupop")
		) {
			if (timeoutId) clearTimeout(timeoutId);
			timeoutId = 0;
			return;
		}

		document.documentElement.classList.remove("show-wpadminbar");
	}

	function showAdminBar() {
		document.documentElement.classList.add("show-wpadminbar");
	}

	init();
})();
