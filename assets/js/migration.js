(function ($) {
  var isRequesting = false;
  var loading = {};
  var ajax = {};
  var migrateButton;

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

    if (!migrateButton) migrateButton = this;

    if (isRequesting) return;
    isRequesting = true;

    loading.start();

    var data = {};

    data.action = "swift_control_migration";
    data.nonce = SwiftControlMigration.nonces.migration;

    $.ajax({
      url: ajaxurl,
      type: "post",
      dataType: "json",
      data: data,
    })
      .done(function (r) {
        if (r.success) {
          if (r.data) console.log(r.data);

          document
            .querySelector(".old-swift-control-uninstalled")
            .classList.add("is-checked");

          installBetterAdminBar();
        }
      })
      .always(function () {
        isRequesting = false;
      });
  };

  function installBetterAdminBar() {
    if (isRequesting) return;
    isRequesting = true;

    wp.updates.installPlugin({
      slug: SwiftControlMigration.pluginSlug,
      success: function () {
        console.log("Better Admin Bar installed successfully.");

        document
          .querySelector(".new-better-admin-bar-installed")
          .classList.add("is-checked");

        activateBetterAdminBar();
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
      url: SwiftControlMigration.activationUrl,
      success: function () {
        console.log("Better Admin Bar is activated.");

        document
          .querySelector(".new-better-admin-bar-activated")
          .classList.add("is-checked");

        // Redirect to Better Admin Bar settings page.
        window.location.replace(SwiftControlMigration.redirectUrl);
      },
      error: function (jqXHR, exception) {
        var msg = "";

        if (jqXHR.status === 0) {
          msg = "Not connect.\n Verify Network.";
        } else if (jqXHR.status === 404) {
          msg = "Requested page not found. [404]";
        } else if (jqXHR.status === 500) {
          msg = "Internal Server Error [500].";
        } else if (exception === "parsererror") {
          msg = "Requested JSON parse failed.";
        } else if (exception === "timeout") {
          msg = "Time out error.";
        } else if (exception === "abort") {
          msg = "Ajax request aborted.";
        } else {
          msg = "Uncaught Error.\n" + jqXHR.responseText;
        }

        console.log(msg);
      },
      always: function () {
        loading.stop();
        isRequesting = false;
      },
    });
  }

  init();
})(jQuery);
