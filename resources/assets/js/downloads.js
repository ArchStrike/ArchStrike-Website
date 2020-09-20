function initDownloads() {
    var $dlContainer = $("#downloads-form-container"),
        $dlSubmit = $dlContainer.find(".download-iso-submit"),
        $dlISO = $("#downloads-form-iso"),
        $dlMirror = $("#downloads-form-mirror");

    // show the direct downloads form for javascript-enabled devices (the noscript version serves the rest)
    $dlContainer.show();

    // initialize the dropdown functionality
    $dlContainer.find("select").dropdown();

    // initialize download form functionality
    $dlSubmit.on("click", function(e) {
        var iso = $dlISO.val(),
            mirror = $dlMirror.val();

        e.preventDefault();

        // remove error classes from previous attempts
        $(".fs-dropdown.error").removeClass("error");

        // start the download if both values are not blank, otherwise apply the appropriate error classes
        if (iso !== "" && mirror !== "") {
            window.location.href = mirror + "os/new_iso/" + iso;
        } else {
            if (iso === "") {
                $dlISO.closest(".fs-dropdown").addClass("error");
            }

            if (mirror === "") {
                $dlMirror.closest(".fs-dropdown").addClass("error");
            }
        }
    });
}
