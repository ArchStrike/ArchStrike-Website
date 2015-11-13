function initPackageSearch() {
    var $pkgSearch = $('#package-search'),
        $pkgSubmit = $pkgSearch.find('button');

    $pkgSearch.on('submit', function(e) {
        e.preventDefault();

        // grab the string in the package search input box
        var pkgSearchString = $(this).find('input').val();

        // disable the submit button
        $pkgSubmit.prop('disabled', true);

        // search for the string if one was provided, otherwise load the package list
        if (pkgSearchString) {
            window.location.href = '/packages/search/' + pkgSearchString;
        } else {
            window.location.href = '/packages';
        }
    });

    $pkgSubmit.on('click', function() {
        $pkgSearch.trigger('submit');
    });
}
