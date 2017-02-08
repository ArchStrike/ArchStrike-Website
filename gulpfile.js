// include packages
const gulp = require("gulp"),
    gUtil = require("gulp-util"),
    gLess = require("gulp-less"),
    gConcat = require("gulp-concat"),
    gPlumber = require("gulp-plumber"),
    gUglify = require("gulp-uglify"),
    gPostCSS = require("gulp-postcss"),
    autoprefixer = require("autoprefixer"),
    lessGlob = require("less-plugin-glob"),
    lessCleanCSS = require("less-plugin-clean-css");

// determine if gulp has been run with --production
const prod = gUtil.env.production;

// initialize plugins
const cleancss = new lessCleanCSS({ advanced: true });

// declare plugin settings
const lessPlugins = prod ? [ lessGlob, cleancss ] : [ lessGlob ],
    autoprefixerSettings = { remove: false, cascade: false, browsers: [ "last 6 versions" ] };

// javascript files for the public site
const jsPublic = [
    "resources/assets/js/site-vars.js",
    "resources/assets/js/builder.js",
    "resources/assets/js/downloads.js",
    "resources/assets/js/mirrorlist.js",
    "resources/assets/js/packages.js",
    "resources/assets/js/app.js"
];

// javascript libraries for the public site
const jsPublicLibs = [
    "bower_components/jquery/dist/jquery.min.js",
    "bower_components/bootstrap/dist/js/bootstrap.min.js",
    "bower_components/formstone/dist/js/core.js",
    "bower_components/formstone/dist/js/touch.js",
    "bower_components/formstone/dist/js/checkbox.js",
    "bower_components/jQuery.stickyFooter/assets/js/jquery.stickyfooter.min.js",
    "bower_components/list.js/dist/list.min.js"
];

// paths to folders containing fonts that should be copied to public/fonts/
const fontPaths = [
    "resources/assets/fonts/**",
    "bower_components/bootstrap/dist/fonts/**",
    "bower_components/font-awesome/fonts/**"
];

// function to handle gulp-plumber errors
function plumberError(err) {
    console.log(err);
    this.emit("end");
}

// function to handle the processing of less files
function processLess(filename) {
    return gulp.src("resources/assets/less/" + filename + ".less")
        .pipe(gPlumber(plumberError))
        .pipe(gLess({ plugins: lessPlugins, paths: "bower_components/" }))
        .pipe(gPostCSS([ autoprefixer(autoprefixerSettings) ]))
        .pipe(gConcat(filename + ".css"))
        .pipe(gulp.dest("public/css/"));
}

// function to handle the processing of javascript files
function processJavaScript(ouputFilename, inputFiles) {
    const javascript = gulp.src(inputFiles)
        .pipe(gPlumber(plumberError))
        .pipe(gConcat(ouputFilename + ".js"));

    // minify if running gulp with --production
    if (prod) { javascript.pipe(gUglify()); }
    return javascript.pipe(gulp.dest("public/js/"));
}

// gulp task for public styles
gulp.task("less-public", function() {
    return processLess("app");
});

// gulp task for public javascript
gulp.task("js-public", function() {
    return processJavaScript("app", jsPublic);
});

// gulp task for public javascript libraries
gulp.task("js-public-libs", function() {
    return processJavaScript("lib", jsPublicLibs);
});

// gulp task to copy fonts
gulp.task("fonts", function() {
    return gulp.src(fontPaths)
        .pipe(gPlumber(plumberError))
        .pipe(gulp.dest("public/fonts/"));
});

// gulp watch task
gulp.task("watch", function() {
    const gLiveReload = require("gulp-livereload");

    const liveReloadUpdate = function(wait) {
        setTimeout(function() {
            gLiveReload.changed(".");
        }, wait || 1);
    };

    gLiveReload.listen();
    gulp.watch(jsPublic, [ "js-public" ]).on("change", liveReloadUpdate);
    gulp.watch([ "app/**/*.php", "resources/views/**/*.blade.php" ]).on("change", liveReloadUpdate);

    gulp.watch("resources/assets/less/**/*.less", [ "less-public" ]).on("change", function() {
        liveReloadUpdate(1000);
    });
});

// gulp default task
gulp.task("default", [
    "less-public",
    "js-public",
    "js-public-libs",
    "fonts"
]);
