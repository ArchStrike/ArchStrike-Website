function initPackageSearch() {
    $('#package-search').on('submit', function(e) {
        e.preventDefault();
        var $this = $(this),
            pkgSearchString = $this.find('input').val();

        $this.find('button').prop('disabled', true);
        if (pkgSearchString) {
            window.location.href = '/packages/search/' + pkgSearchString;
        } else {
            window.location.href = '/packages';
        }
    });
}
