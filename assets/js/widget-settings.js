/**
 * Settings page: the widgets of the quick access panel (drag & drop, edit, preview).
 *
 * Used global objects: ajaxurl, jQuery, SwiftControl.
 */
(function ($) {
	var activeList = document.getElementById("active-items");
	var statusIndicator = document.querySelector(
		".active-widgets-box .heatbox-status-indicator"
	);
	var isRequesting = false;

	if (!activeList) return;

	/**
	 * Drag & drop between the active & available widgets.
	 */
	function setupSortable() {
		var options = {
			connectWith: ".widget-items",
			// A widget in edit mode stays in place.
			cancel: ".edit-mode, button",
			distance: 5,
			placeholder: "widget-item-placeholder",
		};

		$(activeList).sortable(
			$.extend({}, options, {
				// Only the active list saves, it's part of every move.
				update: changeWidgetsOrder,
			})
		);

		$("#available-items").sortable(
			$.extend({}, options, {
				receive: function (e, ui) {
					escapeEditMode(ui.item[0]);
				},
			})
		);
	}

	/**
	 * Set the readonly state of a widget's name field.
	 *
	 * @param {HTMLElement} widgetItem The widget item.
	 * @param {boolean}     readonly   Whether the field is readonly.
	 */
	function setReadonly(widgetItem, readonly) {
		var field = widgetItem.querySelector(".widget-text-field");
		if (field) field.readOnly = readonly;
	}

	/**
	 * Start editing a widget.
	 *
	 * @param {HTMLElement} widgetItem The widget item.
	 */
	function switchEditMode(widgetItem) {
		escapeEditMode("all");

		widgetItem.classList.add("edit-mode");
		widgetItem.querySelector(".edit-button").textContent = SwiftControl.labels.save;
		setReadonly(widgetItem, false);
		widgetItem.querySelector(".widget-text-field").focus();
	}

	/**
	 * Stop editing a widget (or all with "all").
	 *
	 * @param {HTMLElement|string} widgetItem The widget item or "all".
	 */
	function escapeEditMode(widgetItem) {
		var items =
			widgetItem === "all"
				? document.querySelectorAll(".swift-control-settings .widget-item")
				: [widgetItem];

		items.forEach(function (item) {
			var button = item.querySelector(".edit-button");

			item.classList.remove("edit-mode");
			if (button) button.textContent = SwiftControl.labels.edit;
			setReadonly(item, true);
		});

		$(".icon-picker-container").remove();
		$(document).off(".icon-picker");
	}

	/**
	 * Show the status in the box title.
	 *
	 * @param {string} state "loading" or "done".
	 */
	function setStatus(state) {
		if (!statusIndicator) return;

		statusIndicator.classList.remove("is-loading", "is-done");
		statusIndicator.textContent = "";

		if (state === "done") {
			statusIndicator.textContent = SwiftControl.labels.saved;
			// Restart the fade-out animation.
			void statusIndicator.offsetWidth;
		}

		statusIndicator.classList.add("is-" + state);
	}

	/**
	 * Run one request at a time, they write to the same option.
	 *
	 * @param {Function} request The request, returns a jqXHR.
	 */
	function queue(request) {
		if (isRequesting) {
			setTimeout(function () {
				queue(request);
			}, 250);

			return;
		}

		isRequesting = true;
		setStatus("loading");

		request()
			.done(function () {
				buildPreview();
				setStatus("done");
			})
			.fail(function () {
				setStatus("");
				window.alert(SwiftControl.labels.error);
			})
			.always(function () {
				isRequesting = false;
			});
	}

	/**
	 * Save the settings of a widget.
	 *
	 * @param {HTMLElement} widgetItem The widget item.
	 */
	function saveWidgetSettings(widgetItem) {
		var data = {
			action: "swift_control_change_widget_settings",
			nonce: SwiftControl.nonces.changeWidgetSettings,
			widget_key: widgetItem.dataset.widgetKey,
			icon_class: widgetItem.querySelector(".widget-icon i").className,
			text: widgetItem.querySelector(".widget-text-field").value,
		};
		var newTab = widgetItem.querySelector(".new-tab-field");
		var redirectUrl = widgetItem.querySelector(".redirect-url-field");

		if (newTab) data.new_tab = newTab.checked ? 1 : 0;
		if (redirectUrl) data.redirect_url = redirectUrl.value;

		escapeEditMode(widgetItem);

		queue(function () {
			return $.post(ajaxurl, data, null, "json").then(function (r) {
				return r && r.success ? r : $.Deferred().reject();
			});
		});
	}

	/**
	 * Save the active widgets & their order.
	 */
	function changeWidgetsOrder() {
		var keys = [];

		activeList.querySelectorAll(".widget-item").forEach(function (el) {
			keys.push(el.dataset.widgetKey);
		});

		queue(function () {
			return $.post(
				ajaxurl,
				{
					action: "swift_control_change_widgets_order",
					nonce: SwiftControl.nonces.changeWidgetsOrder,
					active_widgets: keys,
				},
				null,
				"json"
			);
		});
	}

	/**
	 * Rebuild the preview of the quick access panel from the active widgets.
	 */
	function buildPreview() {
		var panel = document.querySelector(".swift-control-widgets");
		if (!panel) return;

		// Keep the settings button (the first item).
		$(panel).find(".swift-control-widget-item").not(":first").remove();

		activeList.querySelectorAll(".widget-item").forEach(function (el) {
			var item = document.createElement("li");
			var link = document.createElement("a");
			var icon = document.createElement("i");
			var title = document.createElement("span");

			item.className = "swift-control-widget-item";
			item.dataset.widgetKey = el.dataset.widgetKey;
			link.className = "swift-control-widget-link";
			link.href = "#";
			icon.className = el.querySelector(".widget-icon > i").className;
			title.className = "swift-control-widget-title";
			title.textContent = el.querySelector(".widget-text-field").value;

			link.appendChild(icon);
			item.appendChild(link);
			item.appendChild(title);
			panel.appendChild(item);
		});

		rebuildTransition(panel);
	}

	/**
	 * Rebuild the staggered animation of the preview's widgets.
	 *
	 * @param {HTMLElement} panel The preview panel.
	 */
	function rebuildTransition(panel) {
		var items = [].slice.call(panel.querySelectorAll(".swift-control-widget-item")).slice(1);
		var oldStyle = document.querySelector(".swift-control-transition-style");
		var style = document.querySelector(".swift-control-preview-transition-style");
		var transition = 75;
		var showingDelay = 0;
		var hidingDelay = (items.length - 1) * transition;
		var css = "";

		if (oldStyle) oldStyle.remove();
		if (!style) return;

		items.forEach(function (el) {
			var selector = '[data-widget-key="' + CSS.escape(el.dataset.widgetKey || "") + '"]';

			css += ".swift-control-widgets " + selector + " { transition-delay: " + hidingDelay + "ms; }";
			css += ".swift-control-widgets.is-expanded " + selector + " { transition-delay: " + showingDelay + "ms; }";

			showingDelay += transition;
			hidingDelay -= transition;
		});

		style.textContent = css;
	}

	/**
	 * Register the events.
	 */
	function setupEvents() {
		var $settings = $(".swift-control-settings");

		$settings.on("click", ".widget-item .edit-button", function () {
			var widgetItem = this.closest(".widget-item");

			if (widgetItem.classList.contains("edit-mode")) {
				saveWidgetSettings(widgetItem);
			} else {
				switchEditMode(widgetItem);
			}
		});

		$settings.on("dblclick", ".active-items .dblclick-trigger", function () {
			var widgetItem = this.closest(".widget-item");

			if (!widgetItem.classList.contains("edit-mode")) switchEditMode(widgetItem);
		});

		$settings.on("keydown", ".widget-item input", function (e) {
			var widgetItem = this.closest(".widget-item");

			if (e.key === "Enter") {
				// Don't submit the settings form.
				e.preventDefault();
				if (widgetItem.classList.contains("edit-mode")) saveWidgetSettings(widgetItem);
			} else if (e.key === "Escape") {
				escapeEditMode(widgetItem);
			}
		});

		$settings.on("click", ".widget-item .icon-picker", function () {
			var widgetItem = this.closest(".widget-item");

			if (!widgetItem.classList.contains("edit-mode")) return;

			window.getSelection().removeAllRanges();
			widgetItem.querySelector(".widget-text-field").blur();
		});

		$(document).on("iconPicker:selected", function (e, trigger, selectedIcon) {
			trigger.closest(".widget-icon").querySelector("i").className = selectedIcon;
		});
	}

	setupSortable();
	setupEvents();
})(jQuery);
