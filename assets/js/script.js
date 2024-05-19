jQuery(document).ready(function ($) {
  // Potwierdzenie przed eksportem
  $("#bd-migrator-form__export").on("submit", function (e) {
    if ($(this).find('input[type="submit"]').val() === "Export Data") {
      if (!confirm("Are you sure you want to export the data?")) {
        e.preventDefault();
        return false;
      }

      // Wyczyść wynik eksportu
      $(".bd-migrator-export-result").html("");

      // Pokaż wskaźnik ładowania
      $("#bd-migrator-form__export .submit").append(
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
          $(".bd-migrator-export-result").html(response.data.message);
        } else {
          $(".bd-migrator-export-result").html(
            "<p>There was an error during export.</p>"
          );
        }
      });
    }
  });
});
