jQuery(document).ready(function ($) {
  // Potwierdzenie przed eksportem
  $(".bd-migrator-form").on("submit", function (e) {
    if (!confirm("Are you sure you want to export the data?")) {
      e.preventDefault();
      return false;
    }

    // Pokaż wskaźnik ładowania
    $(".submit").append(
      '<span class="spinner is-active" style="float: none; margin: 0 10px;"></span>'
    );

    // Przetwarzanie AJAX
    e.preventDefault();
    var data = {
      action: "export_breakdance_data",
      export_icons: $("#export_icons").is(":checked"),
      nonce: bdMigrator.nonce,
    };

    $.post(bdMigrator.ajax_url, data, function (response) {
      $(".submit .spinner").remove();
      if (response.success) {
        $(".bd-migrator-result").html(response.data.message);
      } else {
        $(".bd-migrator-result").html(
          "<p>There was an error during export.</p>"
        );
      }
    });
  });
});
