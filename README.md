# Implement React in Discovery Page 

 The goal is to improve front-end performance by Virtual DOM and State techniques offerred by React and by bundled and compressed large number of javascript files. Currently, ES5 javascript version is implemented because of better browser compatibility. 

## Future Work
* Add state - (Done)
* Link to PDX database once P.I. finishes data curation - (Done)
* Router for page redirection.
* Flux pattern (ES5) or Redux pattern (ES6) - (Done with ES5.)
* To avoid XSRF, modify axios header config, php header config, and server-side Cross Origin Resource Sharing (CORS) to comply with Same Origin Policy (SOP)
* Get Apache and Node working together on the same domain
* React Production Build
* [Optional] Change Browserify to Webpack
* [Optional] Change reactify to babelify  
	(ps. Reactify only transforms JSX to Javascript ES5. Babelify can  transform JSX with ES6 capabilities to ES5.)	
* [Optional] Server-Side Rendering if you want to use React and Node.js together  
	(ps. This requires Apache configuration to listen to Node.js port)


## Requirements
* Node.js and npm

## Instructions
* Install Node.js and npm [link](https://nodejs.org/en/)
* Initialize npm package management ``$npm init``
* Install required js packages  
	``
	$npm install --save react@0.13.3 reactify@1.1.1 react-router@0.13.3 flux@2.0.3 object-assign  
	$npm install --save lodash@4.17.10
	$npm install --save browserify@11.0.1 vinyl-source-stream@1.1
	$npm instal --save gulp@3.9.0 gulp-eslint@0.15.0 gulp-connect gulp-open gulp-concat@2.6.0
	$npm install --save-dev gulp-rename gulp-uglify gulp-util
	$npm install --save gulp-postcss autoprefixer cssnano
	$npm install --save gulp-htmlmin   
	$npm install --save bootstrap@3.3.5 jquery@2.1.4 typed.js wowjs react-highcharts@3.0.0
	$npm install --save axios
	``  
* Configure Gulp:  
	+ Create `gulpfile.js` under thr project folder `pdx`.  
	+ Type following content into `gulpfile.js`  

    ```
	"use strict";

	var gulp = require('gulp');
	// Runs a local development server
	var connect = require('gulp-connect');
	// Open a URL in a web browser
	var open = require('gulp-open');
	// Gulp utility
	var gutil = require('gulp-util');
	// Minify HTML file
	var htmlmin = require('gulp-htmlmin');
	// Bundle JS
	var browserify = require('browserify');
	// Transforms React JSX to JS
	var reactify = require('reactify');
	// Use conventional text streams with Gulp,
	// i.e. converts readable stream from browserify into a vinyl stream what gulp is expecting to get
	// In the past, Gulp needs to write a temporal file between different transformations.
	// By using vinyl with Gulp, we avoid this overhead.
	var source = require('vinyl-source-stream');
	// Concatenates files
	var concat = require('gulp-concat');
	// Rename file name
	var rename = require('gulp-rename');
	// Compress js file
	var uglify = require('gulp-uglify');
	// Auto-prefix css styles for cross browser compatibility
	var autoprefixer = require('autoprefixer');
	// Minify the CSS file
	var cssnano = require('cssnano');
	// Pipe CSS through several plugins, but parse CSS only once.
	var postcss = require('gulp-postcss');
	// Lint JS files, including JSX
	var lint = require('gulp-eslint');


	var config = {
	    port: 9005,
	    devBaseUrl: 'http://localhost',
	    paths: {
	        html: './src/*.html',
	        js: './src/**/*.js',
	        css: [
	            'node_modules/bootstrap/dist/css/bootstrap.min.css',
	            'node_modules/bootstrap/dist/css/bootstrap-theme.min.css',
	            'css/animate.min.css',
	            'css/fontawesome-all.min.css',
	            'css/templatemo-style.css',
	            'css/tree.css'
	        ],
	        webfonts: 'webfonts/*',
	        images: './src/images/*',
	        mainJs: './src/main.js',
	        dist: './dist'
	    }
	};

	const AUTOPREFIXER_BROWSERS = [
	  'ie >= 10',
	  'ie_mob >= 10',
	  'ff >= 30',
	  'chrome >= 34',
	  'safari >= 7',
	  'opera >= 23',
	  'ios >= 7',
	  'android >= 4.4',
	  'bb >= 10'
	];


	// Start a local development server
	gulp.task('connect', function(){
	    connect.server({
	        root: ['dist'],
	        port: config.port,
	        base: config.devBaseUrl,
	        livereload: true
	    });
	});

	gulp.task('open', ['connect'],function(){
	    gulp.src('dist/index.html')
	        .pipe(open({ uri: config.devBaseUrl + ':' + config.port + '/'}));
	});

	gulp.task('html',function(){
	    gulp.src(config.paths.html)
	        .pipe(htmlmin({
	          collapseWhitespace: true,
	          removeComments: true
	        }))
	        .pipe(gulp.dest(config.paths.dist))
	        .pipe(connect.reload());
	});

	gulp.task('js',function(){
	    browserify(config.paths.mainJs)
	        .transform(reactify)
	        .bundle()
	        .on('error',console.error.bind(console))
	        .pipe(source('discover_bundle.js'))
	        .pipe(gulp.dest(config.paths.dist + '/scripts'))
	        .pipe(connect.reload());
	});

	gulp.task('css',function(){
	    gulp.src(config.paths.css)
	        .pipe(concat('discover_bundle.css'))
	        .pipe(gulp.dest(config.paths.dist + '/css'))
	        .pipe(rename('discover_bundle.min.css'))
	        .pipe(postcss([autoprefixer({browsers: AUTOPREFIXER_BROWSERS}),cssnano()]))
	        .pipe(gulp.dest(config.paths.dist + '/css'))
	        
	});

	gulp.task('images',function(){
	    gulp.src(config.paths.images)
	        .pipe(gulp.dest(config.paths.dist + '/images'))
	        .pipe(connect.reload());

	});

	gulp.task('webfonts',function(){
	    gulp.src(config.paths.webfonts)
	        .pipe(gulp.dest(config.paths.dist + '/webfonts'))
	        .pipe(connect.reload());

	});

	gulp.task('lint', function() {
	    return gulp.src(config.paths.js)
	        .pipe(lint({config: 'eslint.config.json'}))
	        .pipe(lint.format());
	});

	gulp.task('uglify', function() {
	    return gulp.src(config.paths.dist + '/scripts/discover_bundle.js')
	        .pipe(uglify())
	        .on('error',function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	        .pipe(rename('discover_bundle.min.js'))
	        .pipe(gulp.dest(config.paths.dist + '/scripts'));
	});


	gulp.task('watch',function(){
	    gulp.watch(config.paths.html,['html']);
	    gulp.watch(config.paths.js,['js','uglify','lint']);
	});

	gulp.task('default',['html','js','css','webfonts','images','lint','uglify','open','watch']);
    ```

* Configure ESLint:  
	+ Create `eslint.config.json` under thr project folder `pdx`.  
	+ Type following content into `eslint.config.json`  

    ```
	    {
	  "ecmaFeatures": {
	    "jsx": true
	  },
	  "env": {
	    "browser": true,
	    "node": true,
	    "jquery": true
	  },
	  "rules": {
	    "quotes": 0,
	    "no-trailing-spaces": 0,
	    "eol-last": 0,
	    "no-unused-vars": 0,
	    "no-underscore-dangle": 0,
	    "no-alert": 0,
	    "no-lone-blocks": 0
	  },
	  "globals": {
	    jQuery: true,
	    $: true
	  }
	}

    ```
* Create `src` and `dist` folder under the project folder `pdx`
* Start coding React js under `src` folder
* Run gulp `$gulp`
* Once development is finished, move `dist/scripts/discover_bundle.min.js` and `dist/css/discover_bundle.min.css` to the Apache web project folder. Refer these two files in `discovery.php`.

## Other Resources
* [How to get Apache and Node working together on the same domain with Proxied Javascript AJAX requests](https://blog.cloudboost.io/get-apache-and-node-working-together-on-the-same-domain-with-javascript-ajax-requests-39db51959b79)
	(ps. This link uses ES6 which has keywords, `let` and `const`, not used in ES5)
* [React Production Build](https://reactjs.org/docs/optimizing-performance.html#use-the-production-build)
* [Webpack vs. Browserify](https://stackshare.io/stackups/browserify-vs-webpack)
* [babelify](https://github.com/babel/babelify)
