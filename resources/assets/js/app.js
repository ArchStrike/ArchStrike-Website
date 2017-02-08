$(document).ready(function() {
    // set nav item for the current page active
    $("#navbar a[href=\"/" + (SiteVars.page === "builder" ? "packages" : SiteVars.page) + "\"]").parent().addClass("active");

    // enable the sticky footer
    $("footer").stickyFooter({
        class: "sticky-footer",
        content: "#page-content"
    });

    // trigger a resize after everything has loaded to ensure the sticky footer runs
    $(window).on("load", function() {
        $(this).trigger("resize");
    });

    // page-specific js
    switch (SiteVars.page) {
        case "": // home page
            initPackageSearch();
            break;

        case "builder":
            initBuildTable();
            break;

        case "downloads":
            initDownloads();
            break;

        case "mirrorlist":
            initCustomMirrorlist();
            break;

        case "packages":
            initPackageSearch();
            break;
    }
});
