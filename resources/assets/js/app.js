$(document).ready(function() {
    // set nav item for the current page active
    $('#navbar a[href="/' + SiteVars.page + '"]').parent().addClass('active');

    // enable the sticky footer
    $(window).on('load', function() {
        $('footer').stickyFooter({
            class: 'sticky-footer',
            content: '#page-content'
        });
    });

    // page-specific js
    switch (SiteVars.page) {
        case '': // home page
            break;

        case 'builder':
            initBuildTable();
            break;

        case 'packages':
            break;

        case 'team':
            break;

        case 'wiki':
            break;
    }
});
