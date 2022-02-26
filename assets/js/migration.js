/**
 * Migrate from old Swift Control to the new Better Admin Bar.
 */
(function ($) {
  var isRequesting = false;
  var loading = {};
  var ajax = {};
  var migrateButton;

  var elms = {};

  elms.migrationFailed = document.querySelector(
    ".swift-control-migration-status.migration-failed"
  );

  elms.errorMessage = elms.migrationFailed.querySelector(".error-message");

  /**
   * Call the main functions here.
   */
  function init() {
    $(document).on("click", ".swift-control-migration-button", ajax.migrate);
  }

  loading.start = function () {
    migrateButton.classList.add("is-loading");
  };

  loading.stop = function () {
    migrateButton.classList.remove("is-loading");
  };

  /**
   * Send ajax request to save the settings.
   */
  ajax.migrate = function (e) {
    e.preventDefault();

    if (
      !confirm(
        "Your settings will be brought to the new \"Better Admin Bar\". However, this action can't be cancelled nor be un-done. Once it's' running, it needs to be done. Migrate now?"
      )
    ) {
      return;
    }

    if (!migrateButton) migrateButton = this;

    if (isRequesting) return;
    isRequesting = true;

    loading.start();

    var data = {};

    data.action = "swift_control_migration";
    data.nonce = SwiftControlMigration.nonces.migration;
    data.old_plugin_slug = SwiftControlMigration.oldPlugin.slug;
    data.old_plugin_basename =
      SwiftControlMigration.oldPlugin.old_plugin_basename;
    data.new_plugin_slug = SwiftControlMigration.newPlugin.slug;
    data.new_plugin_basename =
      SwiftControlMigration.newPlugin.new_plugin_basename;

    $.ajax({
      url: ajaxurl,
      type: "post",
      dataType: "json",
      data: data,
    })
      .done(function (r) {
        if (!r.success) {
          elms.errorMessage.innerHTML = r.data;
          elms.migrationFailed.classList.add("is-checked");
          return;
        }

        document
          .querySelector(".old-swift-control-uninstalled")
          .classList.add("is-checked");

        isRequesting = false;

        installBetterAdminBar();
      })
      .fail(function (jqXHR) {
        if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
          elms.errorMessage.innerHTML = jqXHR.responseJSON.data;
        }

        elms.migrationFailed.classList.add("is-checked");
        loading.stop();
      })
      .always(function () {
        isRequesting = false;
      });
  };

  function installBetterAdminBar() {
    if (isRequesting) return;
    isRequesting = true;

    wp.updates.installPlugin({
      slug: SwiftControlMigration.newPlugin.slug,
      success: function () {
        console.log("Better Admin Bar installed successfully.");

        document
          .querySelector(".new-better-admin-bar-installed")
          .classList.add("is-checked");

        isRequesting = false;

        activateBetterAdminBar();
      },
      fail: function (jqXHR) {
        if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
          elms.errorMessage.innerHTML = jqXHR.responseJSON.data;
        }

        elms.migrationFailed.classList.add("is-checked");
        loading.stop();
      },
      always: function () {
        isRequesting = false;
      },
    });
  }

  function activateBetterAdminBar() {
    if (isRequesting) return;
    isRequesting = true;

    $.ajax({
      async: true,
      type: "GET",
      url: SwiftControlMigration.newPlugin.activationUrl,
      success: function () {
        console.log("Better Admin Bar is activated.");

        document
          .querySelector(".new-better-admin-bar-activated")
          .classList.add("is-checked");

        loading.stop();
        isRequesting = false;

        // Redirect to Better Admin Bar settings page.
        window.location.replace(SwiftControlMigration.redirectUrl);
      },
      fail: function (jqXHR) {
        if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
          elms.errorMessage.innerHTML = jqXHR.responseJSON.data;
        }

        elms.migrationFailed.classList.add("is-checked");
        loading.stop();
      },
      always: function () {
        loading.stop();
        isRequesting = false;
      },
    });
  }

  init();
})(jQuery);
