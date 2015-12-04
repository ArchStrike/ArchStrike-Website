$(document).ready(function() {
    // set nav item for the current page active
    $('#navbar a[href="/' + (SiteVars.page == 'builder' ? 'packages' : SiteVars.page) + '"]').parent().addClass('active');

    // enable the sticky footer
    $('footer').stickyFooter({
        class: 'sticky-footer',
        content: '#page-content'
    });

    // trigger a resize after everything has loaded to ensure the sticky footer runs
    $(window).load(function() {
        $(this).trigger('resize');
    });

    // page-specific js
    switch (SiteVars.page) {
        case '': // home page
            break;

        case 'builder':
            initBuildTable();
            break;

        case 'packages':
            initPackageSearch();
            break;

        case 'team':
            break;

        case 'wiki':
            break;
    }
});
