// Core packages
const gulp = require("gulp"),
    minimist = require("minimist"),
    log = require("fancy-log"),
    plumber = require("gulp-plumber"),
    concat = require("gulp-concat");

// Less and CSS packages
const less = require("gulp-less"),
    lessGlob = require("less-plugin-glob"),
    postCSS = require("gulp-postcss"),
    autoprefixer = require("autoprefixer"),
    lessCleanCSS = require("less-plugin-clean-css");

// Javascript packages
const uglify = require("gulp-uglify");

// Determine if gulp has been run with --production
const isProduction = minimist(process.argv.slice(2)).production !== undefined;

// Include browsersync when gulp has not been run with --production
let browserSync = undefined;

if (!isProduction) {
    browserSync = require("browser-sync").create();
}

// declare plugin settings
const lessPlugins = isProduction ? [ lessGlob, new lessCleanCSS({ advanced: true }) ] : [ lessGlob ],
    autoprefixerSettings = { remove: false, cascade: false };

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
    "bower_components/formstone/dist/js/dropdown.js",
    "bower_components/jQuery.stickyFooter/assets/js/jquery.stickyfooter.min.js",
    "bower_components/list.js/dist/list.min.js"
];

// paths to folders containing fonts that should be copied to public/fonts/
const fontPaths = [
    "resources/assets/fonts/**",
    "bower_components/bootstrap/dist/fonts/**",
    "bower_components/font-awesome/fonts/**"
];

// Handle errors
function handleError(err) {
    log.error(err);
    this.emit("end");
}

// function to handle the processing of less files
function processLess(filename) {
    const css = gulp.src("resources/assets/less/" + filename + ".less")
        .pipe(plumber(handleError))
        .pipe(less({ plugins: lessPlugins, paths: "bower_components/" }))
        .pipe(postCSS([ autoprefixer(autoprefixerSettings) ]))
        .pipe(concat(filename + ".css"))
        .pipe(gulp.dest("public/css/"));

    if (!isProduction) {
        css.pipe(browserSync.stream({ match: `**/${filename}.css` }));
    }

    return css;
}

// function to handle the processing of javascript files
function processJavaScript(ouputFilename, inputFiles) {
    const javascript = gulp.src(inputFiles)
        .pipe(plumber(handleError))
        .pipe(concat(`${ouputFilename}.js`));

    if (isProduction) {
        javascript.pipe(uglify());
    }

    return javascript.pipe(gulp.dest("public/js/"));
}

// gulp task for public styles
gulp.task("less-public", () => {
    return processLess("app");
});

// gulp task for public javascript
gulp.task("js-public", () => {
    return processJavaScript("app", jsPublic);
});

// gulp task for public javascript libraries
gulp.task("js-public-libs", () => {
    return processJavaScript("lib", jsPublicLibs);
});

// gulp task to copy fonts
gulp.task("fonts", (done) => {
    gulp.src(fontPaths)
        .pipe(plumber(handleError))
        .pipe(gulp.dest("public/fonts/"));

    done();
});

// gulp watch task
gulp.task("watch", () => {
    const browserSyncReload = (done) => {
        browserSync.reload();
        done();
    };

    browserSync.init({
        logLevel: "silent",
        baseDir: "./public",
        notify: false,

        ghostMode: {
            clicks: false,
            forms: true,
            scroll: false
        }
    });

    gulp.watch([ "app/**/*.php", "routes/**/*.php", "resources/views/**/*.blade.php" ], gulp.series(browserSyncReload));
    gulp.watch("resources/assets/js/**/*.js", gulp.series("js-public", browserSyncReload));
    gulp.watch("resources/assets/less/**/*.less", gulp.parallel("less-public"));
});

// gulp default task
gulp.task("default", gulp.parallel(
    "less-public",
    "js-public",
    "js-public-libs",
    "fonts"
));
