function initPackageSearch() {
    var $pkgSearch = $("#package-search"),
        $pkgSearchString = $("#search-string"),
        $pkgSearchType = $("#search-type"),
        $pkgSearchSubmit = $("#search-submit");

    $pkgSearch.on("submit", function(e) {
        // grab the string in the package search input box
        var pkgSearchString = $pkgSearchString.val(),
            pkgSearchType = $pkgSearchType.val();

        e.preventDefault();

        // disable the submit button
        $pkgSearchSubmit.prop("disabled", true);

        // search for the string if one was provided, otherwise load the package list
        if (pkgSearchString) {
            window.location.href = "/packages/search/" + pkgSearchString + "/" + pkgSearchType;
        } else {
            window.location.href = "/packages";
        }
    });

    if ($pkgSearchSubmit.length) {
        $pkgSearchSubmit.on("click", function() {
            $pkgSearch.trigger("submit");
        });
    }
}
