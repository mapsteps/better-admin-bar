(function ($) {
	if (window.NodeList && !NodeList.prototype.forEach) {
		NodeList.prototype.forEach = Array.prototype.forEach;
	}

	if (!window.swiftControlOpt) return;

	var ajax = {};

	var menu = document.querySelector(".swift-control-widgets");
	var panelsWrapper = document.querySelector(".swift-control-helper-panels");
	var widgetSize = parseInt(swiftControlOpt.size, 10);

	var position = {
		x: swiftControlOpt.position.x,
		x_direction: swiftControlOpt.position.x_direction,
		y: swiftControlOpt.position.y,
		y_direction: swiftControlOpt.position.y_direction,
		y_percentage: swiftControlOpt.position.y_percentage,
	};

	var docWidth;
	var halfHeight;

	function init() {
		if (!menu) return;
		setupMenu();
		checkDisabledWidget();
		checkInlineEditing();
		setupWallaceSupport();
		setupPositions();
	}

	function setupMenu() {
		document
			.querySelector(".swift-control-widgets .swift-control-widget-setting a")
			.addEventListener("click", function (e) {
				e.preventDefault();

				if (!this.parentNode.parentNode.classList.contains("is-dragging")) {
					this.parentNode.parentNode.classList.toggle("is-expanded");
				}
			});
	}

	function checkDisabledWidget() {
		var disabledItems = document.querySelectorAll(
			".swift-control-widgets .swift-control-widget-item.is-disabled a"
		);
		if (!disabledItems.length) return;

		disabledItems.forEach(function (el) {
			el.addEventListener("click", function (e) {
				e.preventDefault();
			});
		});
	}

	function checkInlineEditing() {
		var inlineEdits = document.querySelectorAll(
			".swift-control-widgets .swift-control-widget-item.inline-edit a"
		);
		if (!inlineEdits.length) return;

		inlineEdits.forEach(function (el) {
			el.addEventListener("click", function (e) {
				e.preventDefault();

				if (this.parentNode.classList.contains("wallace-edit")) {
					document.dispatchEvent(
						new CustomEvent("walInlineAdminButtonClicked")
					);
				}
			});
		});
	}

	function hideMenuOnInlineEditing() {
		menu.classList.remove("is-expanded");

		// Hide the whole menu after widget items hidden.
		setTimeout(function () {
			menu.classList.add("is-hidden-mode");
		}, swiftControlOpt.settingButton.hidingDelay);
	}

	function setupWallaceSupport() {
		document.addEventListener("WallaceInlineOpened", function (e) {
			hideMenuOnInlineEditing();
		});

		document.addEventListener("WallaceInlineClosed", function (e) {
			menu.classList.remove("is-hidden-mode");
		});
	}

	function setupPositions() {
		window.addEventListener("load", setupPosition);
		window.addEventListener("resize", setupPosition);
	}

	function setupPosition(e) {
		docWidth = document.documentElement.clientWidth;
		halfHeight = document.documentElement.clientHeight / 2;

		position.x =
			position.x_direction === "left"
				? (position.x = 0)
				: docWidth - widgetSize;
		position.y = (position.y_percentage / 100) * halfHeight;
		position.y =
			position.y > halfHeight
				? halfHeight - swiftControlOpt.size / 2
				: position.y;
		position.y = position.y_direction === "top" ? position.y * -1 : position.y;

		menu.style.webkitTransform = menu.style.transform =
			"translate(" + position.x + "px, " + position.y + "px)";

		if (e.type === "load") {
			menu.classList.remove("is-invisible");
		}
	}

	function showHelperPanels() {
		var fragment = document.createDocumentFragment();
		var panels = [
			createElement("div", "helper-panel vertical-panel left-panel"),
			createElement("div", "helper-panel vertical-panel center-panel"),
			createElement("div", "helper-panel vertical-panel right-panel"),

			createElement("div", "helper-panel horizontal-panel top-middle-panel"),
			createElement("div", "helper-panel horizontal-panel middle-panel"),
			createElement("div", "helper-panel horizontal-panel bottom-middle-panel"),
		];

		for (var i = 0; i < panels.length; i++) {
			// Append child to fragment, not DOM.
			fragment.appendChild(panels[i]);
		}

		// Now append the fragment to the DOM.
		panelsWrapper.appendChild(fragment);
	}

	function hideHelperPanels() {
		while (panelsWrapper.firstChild) {
			panelsWrapper.removeChild(panelsWrapper.firstChild);
		}
	}

	function createElement(tagName, className, child) {
		var tag = document.createElement(tagName);
		tag.className = className;

		if (child) tag.appendChild(child);
		return tag;
	}

	ajax.savePosition = function () {
		var data = {};
		data.action = "swift_control_save_position";
		data.nonce = swiftControlOpt.nonce;

		for (var prop in position) {
			if (position.hasOwnProperty(prop)) {
				data[prop] = position[prop];
			}
		}

		$.ajax({
			url: swiftControlOpt.ajaxUrl,
			type: "post",
			dataType: "json",
			data: data,
		})
			.done(function (r) {
				// console.log(r.data);
			})
			.fail(function () {
				console.log("Failed to save widget position");
			});
	};

	init();
})(jQuery);
