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
// var uglify = require('gulp-uglify');
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

// gulp-uglify can't not identify async and await keywords for Promise HTTP client Library, axios.
// There is much information about babel transpile ES6 to ES5 and gulp-uglify can compress the transpiled with async and await keywords.

// gulp.task('uglify', function() {
//     return gulp.src(config.paths.dist + '/scripts/discover_bundle.js')
//         .pipe(uglify())
//         .on('error',function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
//         .pipe(rename('discover_bundle.min.js'))
//         .pipe(gulp.dest(config.paths.dist + '/scripts'));
// });

gulp.task('watch',function(){
    gulp.watch(config.paths.html,['html']);
    gulp.watch(config.paths.js,['js','lint']);
    // gulp.watch(config.paths.js,['js','uglify','lint']);
});

gulp.task('default',['html','js','css','webfonts','images','lint','open','watch']);
// gulp.task('default',['html','js','css','webfonts','images','lint','uglify','open','watch']);