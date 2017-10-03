'use strict';

var gulp         = require('gulp'),
    uglify       = require('gulp-uglify'),
    concat       = require('gulp-concat'),
    cleanCSS     = require('gulp-clean-css'),
    sourcemaps   = require('gulp-sourcemaps'),
    autoprefixer = require('gulp-autoprefixer'),
    notify       = require('gulp-notify'),
    plumber      = require('gulp-plumber'),
    watch        = require('watch'),
    livereload   = require('gulp-livereload');

gulp.task('js', function () {
    return gulp.src([
        './assets/js/src/popper.min.js', 
        './assets/js/src/bootstrap.js', 
        './assets/js/src/jquery.mask.js', 
        './assets/js/src/main.js'
    ])
        .pipe(plumber( {errorHandler: notify.onError("Error: <%= error.message %>")} ))
        .pipe(sourcemaps.init())
        .pipe(uglify())
        .pipe(concat('main.min.js'))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('./assets/js/dist/'))
        .pipe(notify('Task JS finished!'))
        .pipe(livereload());
});

gulp.task('css', function() {
    return gulp.src([
        './assets/css/src/bootstrap.css',
        './assets/css/src/*.css'
    ])
        .pipe(plumber( {errorHandler: notify.onError("Error: <%= error.message %>")} ))
        .pipe(autoprefixer())
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(concat('main.min.css'))
        .pipe(gulp.dest('./assets/css/dist/'))
        .pipe(notify('Task CSS finished!'))
        .pipe(livereload());
});

gulp.task('watch', function() {
    livereload.listen();
    gulp.watch('./assets/css/src/*.css', ['css']);
    gulp.watch('./assets/js/src/*.js', ['js']);
});

gulp.task('default', ['css', 'js', 'watch']);