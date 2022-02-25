(function ($) {
  var isRequesting = false;
  var loading = {};
  var ajax = {};

  /**
   * Call the main functions here.
   */
  function init() {
    var migrateButton;

    $(document).on(
      "click",
      ".swift-control-migration-button",
      ajax.migrate
    );
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
        installBetterAdminBar();
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
