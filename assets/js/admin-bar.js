(function () {
	var hoverIntentMs = swiftControlAdminBarOpts.hoverIntent;
	var windowWidth = document.documentElement.clientWidth;
	var triggerAreaHeight = windowWidth <= 782 ? 46 : 32;
	var timeoutId = 0;

	var mouseAction = "";
	var showingIntentMs = 0;
	var hidingIntentMs = 0;

	function init() {
		window.addEventListener("resize", onWindowResize);
		window.addEventListener("mousemove", onMouseMove);
	}

	function onWindowResize() {
		windowWidth = document.documentElement.clientWidth;

		triggerAreaHeight = windowWidth <= 782 ? 46 : 32;
	}

	function onMouseMove(e) {
		var adminbarIsShown =
			document.documentElement.classList.contains("show-wpadminbar");

		if (e.clientY > triggerAreaHeight) {
			handleMouseLeave(e.target, adminbarIsShown);
			return;
		}

		handleMouseEnter(e.target, adminbarIsShown);
	}

	function handleMouseLeave(target, adminbarIsShown) {
		var now = Date.now();

		if (mouseAction !== "mouseleave") {
			hidingIntentMs = now;
			mouseAction = "mouseleave";
		}

		showingIntentMs = now;

		if (!adminbarIsShown) {
			return;
		}

		timeoutId = setTimeout(function () {
			hideAdminBar(target);
		}, hoverIntentMs);
	}

	function handleMouseEnter(target, adminbarIsShown) {
		var now = Date.now();

		if (mouseAction !== "mouseenter") {
			showingIntentMs = now;
			mouseAction = "mouseenter";
		}

		hidingIntentMs = now;

		if (adminbarIsShown) {
			return;
		}

		timeoutId = setTimeout(function () {
			showAdminBar(target);
		}, hoverIntentMs);
	}

	function hideAdminBar(target) {
		if (mouseAction !== "mouseleave") {
			return;
		}

		if (Date.now() - hidingIntentMs < hoverIntentMs) {
			return;
		}

		if (
			target.parentNode.classList.contains("ab-item") ||
			target.classList.contains("ab-item") ||
			target.parentNode.classList.contains("ab-submenu") ||
			target.parentNode.classList.contains("ab-sub-wrapper") ||
			target.parentNode.classList.contains("menupop")
		) {
			return;
		}

		setTimeout(function () {
			document.documentElement.classList.remove("show-wpadminbar");
		}, swiftControlAdminBarOpts.transitionDelay - hoverIntentMs);
	}

	function showAdminBar() {
		if (mouseAction !== "mouseenter") {
			return;
		}

		if (Date.now() - showingIntentMs < hoverIntentMs) {
			return;
		}

		document.documentElement.classList.add("show-wpadminbar");
	}

	init();
})();
