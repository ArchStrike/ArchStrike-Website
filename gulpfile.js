process.env.DISABLE_NOTIFIER = true;

var gulp   = require("gulp");
var elixir = require('laravel-elixir');

// require livereload when not production
if (!elixir.config.production)
    require('laravel-elixir-livereload');

// autoprefixer settings
elixir.config.autoprefix = { remove: false, cascade: false, browsers: ['last 2 versions'] };

// javascript files in resources/assets/js/
var jsLocal = [
    'site-vars.js',
    'app.js'
];

// javascript files in bower_components/
var jsBower = [
    'jquery/dist/jquery.min.js',
    'bootstrap/dist/js/bootstrap.min.js'
];

// less import path locations other than resources/assets/less/
var lessPaths = [
    'bower_components/bootstrap/less'
];

elixir(function(mix) {
    // elixir mix functions
    mix.copy('bower_components/bootstrap/dist/fonts/bootstrap/**', 'public/fonts')
       .less('app.less', 'public/css/app.css', { paths: lessPaths })
       .scripts(jsLocal, 'public/js/app.js', 'resources/assets/js/')
       .scripts(jsBower, 'public/js/lib.js', 'bower_components/')
       .version(['css/app.css', 'js/app.js', 'js/lib.js']);

    // start livereload when not production
    if (!elixir.config.production)
        mix.livereload();
});
