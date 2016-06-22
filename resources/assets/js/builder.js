function initBuildTable() {
    var $pkgFilter = $("#package-filter"),
        $package = $(".package"),
        $buildLogsRow = $("#build-logs tr");

    // toggle build status cells when clicking the respective package on mobile
    $package.on("click", function() {
        var $parent = $(this).parent();

        if ($parent.hasClass("visible")) {
            $parent.removeClass("visible");
        } else {
            $buildLogsRow.removeClass("visible");
            $parent.addClass("visible");
        }
    });

    // empty the filter list
    $pkgFilter.val("");

    $(window).on("load", function() {
        // initialize the package name filter
        var buildInfoList = new List("build-logs", {
            valueNames: [
                "package",
                "package-sort",
                "repo-sort",
                "armv6-sort",
                "armv7-sort",
                "i686-sort",
                "x86_64-sort"
            ],
            page: $package.length + 1
        });

        // trigger a resize after changing the filter so the sticky footer updates
        $pkgFilter.on("input", function() {
            setTimeout(function() {
                $(window).trigger("resize");
            }, 100);
        });
    });
}
