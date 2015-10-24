function initBuildTable() {
    // initialize the package name filter
    window.onload = function() {
        var buildInfoList = new List('build-logs', {
            valueNames: ['package'],
            page: $('.package').length + 1
        });
    };

    // setup mobile toggles
    $('td.package').on('click', function() {
        var $parent = $(this).parent();

        if ($parent.hasClass('visible')) {
            $parent.removeClass('visible');
        } else {
            $('#build-logs tr').removeClass('visible');
            $parent.addClass('visible');
        }
    });
}
