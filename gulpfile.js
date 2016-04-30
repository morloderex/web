var elixir = require('laravel-elixir');
require('laravel-elixir-livereload');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
  var assets = [
            'js/app.js',
            'js/main.js',
            'css/app.css',
            'css/main.css'
        ];


    mix
    	.copy('node_modules/vue-strap/dist/vue-strap.js', 'resources/assets/js/vue-strap.js')
    	.copy('node_modules/vue-countup/dist/vue-count-up.js', 'resources/assets/js/vue-count-up.js')
    	.copy('node_modules/lity/dist/lity.js', 'resources/assets/js/lity.js')
		.copy('node_modules/jquery/dist/jquery.js', 'resources/assets/js/jquery.js')
		
		.copy('node_modules/lity/dist/lity.css', 'resources/assets/css/lity.css')
    	
    	.scripts(['*.js'], 'public/js/app.js')
    	.styles(['*.css'], 'public/css/main.css')
    	.sass('app.scss')
    	.version(assets)
        .livereload('build/rev-manifest.json', {
            liveCSS: true
        })
        .browserSync()
    	.browserify('es6/main.js');
});
