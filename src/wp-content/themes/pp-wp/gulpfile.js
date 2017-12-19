'use strict';

/**
 * Defining base paths
 *
 */
var basePaths = {
    node: './node_modules/',                                        // Path to node packages
    projectPHPFiles: './**/*.php',                                  // Path to all PHP files.
    projectStylesheetFiles: './assets/stylesheets/src/',   // Path to all *.scss files inside css folder and inside them.
    projectJSFiles: './assets/js/src/'                          // Path to all custom JS files.
};

/**
 * Defining requirements
 *
 */
var gulp = require('gulp'),
    uglify = require('gulp-uglify-es').default,
    sassVariables = require('gulp-sass-variables'),
    sass = require('gulp-sass'),
    concat = require('gulp-concat'),
    cleanCSS = require('gulp-clean-css'),
    sourcemaps = require('gulp-sourcemaps'),
    notify = require('gulp-notify'),
    plumber = require('gulp-plumber'),
    watch = require('gulp-watch'),
    browserSync = require('browser-sync').create(),
    livereload = require('gulp-livereload');

/**
 * Configure the javascript bundle for the application
 *
 */
gulp.task('scripts', function () {
    return gulp.src([
        // @TODO The current version of bootstrap (4.0.0-beta.2) is not working alongside WP jQuery
        // basePaths.node + 'popper.js/dist/umd/popper.js',
        // basePaths.node + 'jquery/dist/jquery.js',
        // basePaths.node + 'bootstrap/dist/js/bootstrap.min.js',
        basePaths.projectJSFiles + 'vendor/popper.js',
        basePaths.projectJSFiles + 'vendor/bootstrap.js',
        basePaths.projectJSFiles + 'vendor/*.js',
        basePaths.projectJSFiles + '*.js'
    ])
        .pipe(plumber())
        .pipe(concat('bundle.min.js'))
        .pipe(sourcemaps.init())
        .pipe(uglify())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('./assets/js/dist/'))
        .pipe(notify('Task JS finished!'))
        .pipe(livereload());
});

/**
 * Configure the stylesheet bundle for the application
 *
 */
gulp.task('styles', function () {
    return gulp.src([
        // basePaths.node + 'bootstrap/scss/bootstrap.scss',
        basePaths.node + 'font-awesome/scss/font-awesome.scss',
        basePaths.projectStylesheetFiles + 'main.scss'
    ])
        .pipe(sassVariables({
            // Set variables for define font-awesome fonts folder
            '$fa-font-path': '../../fonts'
        }))
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(concat('bundle.min.css'))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('./assets/stylesheets/dist/'))
        .pipe(notify('Task CSS finished!'))
        .pipe(livereload());
});

/**
 * Copy fonts files to distribution
 *
 */
gulp.task('font-awesome', function () {
    gulp.src([
        basePaths.node + 'font-awesome/fonts/**/*.{ttf,woff,woff2,eof,svg}'
    ]).pipe(gulp.dest('./assets/fonts/'));
});

/**
 * Watch for PHP changes
 *
 */
gulp.task('php', function () {
    gulp.src(basePaths.projectPHPFiles).pipe(livereload());
});

/**
 * Synchronised browser testing
 *
 */
gulp.task('browser-sync', function() {
    browserSync.init([
        basePaths.projectPHPFiles,
        basePaths.projectStylesheetFiles,
        basePaths.projectJSFiles
    ]);
});

/**
 * Watch for changes
 *
 */
gulp.task('watch', function () {
    livereload.listen();
    // gulp.watch('./assets/stylesheets/src/*/*.scss', ['styles']);
    gulp.watch('./assets/stylesheets/src/**/*.scss', ['styles']);
    gulp.watch('./assets/js/src/*.js', ['scripts']);
    gulp.watch('**/*.php', ['php']);
});

/**
 * Default task
 *
 */
gulp.task('default', ['styles', 'font-awesome', 'scripts', 'watch']);