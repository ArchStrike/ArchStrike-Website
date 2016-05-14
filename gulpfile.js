// include packages
var gulp           = require('gulp'),
    gUtil          = require('gulp-util'),
    gLess          = require('gulp-less'),
    gConcat        = require('gulp-concat'),
    gPlumber       = require('gulp-plumber'),
    gUglify        = require('gulp-uglify'),
    lessGlob       = require('less-plugin-glob'),
    lessAutoPrefix = require('less-plugin-autoprefix'),
    lessCleanCSS   = require('less-plugin-clean-css');

// determine if gulp has been run with --production
var prod = gUtil.env.production;

// initialize plugins
var autoprefix = new lessAutoPrefix({ remove: false, cascade: false, browsers: [ 'last 2 versions' ] }),
    cleancss   = new lessCleanCSS({ advanced: true });

// declare less plugins based on wither gulp has been run with --production
var lessPlugins = prod ? [ lessGlob, autoprefix, cleancss ] : [ lessGlob, autoprefix ];

// javascript files for the public site
var jsPublic = [
    'resources/assets/js/site-vars.js',
    'resources/assets/js/builder.js',
    'resources/assets/js/packages.js',
    'resources/assets/js/app.js'
];

// javascript libraries for the public site
var jsPublicLibs = [
    'bower_components/jquery/dist/jquery.min.js',
    'bower_components/bootstrap/dist/js/bootstrap.min.js',
    'bower_components/jQuery.stickyFooter/assets/js/jquery.stickyfooter.min.js',
    'bower_components/list.js/dist/list.min.js'
];

// paths to folders containing fonts that should be copied to public/fonts/
var fontPaths = [
    'resources/assets/fonts/**',
    'bower_components/bootstrap/dist/fonts/**',
    'bower_components/font-awesome/fonts/**'
];

// function to handle gulp-plumber errors
function plumberError(err) {
    console.log(err);
    this.emit('end');
}

// function to handle the processing of less files
function processLess(filename) {
    return gulp.src('resources/assets/less/' + filename + '.less')
        .pipe(gPlumber(plumberError))
        .pipe(gLess({ plugins: lessPlugins, paths: 'bower_components/' }))
        .pipe(gConcat(filename + '.css'))
        .pipe(gulp.dest('public/css/'));
}

// function to handle the processing of javascript files
function processJavaScript(ouputFilename, inputFiles) {
    var javascript = gulp.src(inputFiles)
        .pipe(gPlumber(plumberError))
        .pipe(gConcat(ouputFilename + '.js'));

    // minify if running gulp with --production
    if (prod) javascript.pipe(gUglify());
    return javascript.pipe(gulp.dest('public/js/'));
}

// gulp task for public styles
gulp.task('less-public', function() {
    return processLess('app');
});

// gulp task for public javascript
gulp.task('js-public', function() {
    return processJavaScript('app', jsPublic);
});

// gulp task for public javascript libraries
gulp.task('js-public-libs', function() {
    return processJavaScript('lib', jsPublicLibs);
});

// gulp task to copy fonts
gulp.task('fonts', function() {
    return gulp.src(fontPaths)
        .pipe(gPlumber(plumberError))
        .pipe(gulp.dest('public/fonts/'));
});

// gulp watch task
gulp.task('watch', function() {
    var gLiveReload = require('gulp-livereload');

    var liveReloadUpdate = function(wait) {
        setTimeout(function() {
            gLiveReload.changed('.');
        }, wait || 1);
    };

    gLiveReload.listen();
    gulp.watch(jsPublic, [ 'js-public' ]).on('change', liveReloadUpdate);
    gulp.watch([ 'app/**/*.php', 'resources/views/**/*.blade.php' ]).on('change', liveReloadUpdate);

    gulp.watch('resources/assets/less/**/*.less', [ 'less-public' ]).on('change', function() {
        liveReloadUpdate(1000);
    });
});

// gulp default task
gulp.task('default', [
    'less-public',
    'js-public',
    'js-public-libs',
    'fonts'
]);
