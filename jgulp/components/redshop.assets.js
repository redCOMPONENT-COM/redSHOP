/**
 * Gulp components for redSHOP,
 * This is for developer mode only.
 * Don't ever use this on live site.
 *
 * Short Tag:
 * - gulp watch
 * - gulp copy
 *
 * Every task begin with <task name>:components.redshop
 *
 * There are 3 task types:
 * - clean
 * - copy
 * - watch
 *
 * We handle scripts by gulp pipe and uglify.
 * We handle css by sass and minify
 *
 * For more details:
 * - https://www.npmjs.com/package/gulp-watch
 * - http://sass-lang.com
 * - https://github.com/mishoo/UglifyJS
 */

/// Define gulp and its config
var gulp = require('gulp');
var config = require('../../gulp-config.json');

/// Define dependencies
var del = require('del');
var sass = require('gulp-sass');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var path = require("path");
var fs = require('fs');
var changed = require('gulp-changed');

/// Define component tasks
var componentName = 'com_redshop';
var baseTask = 'components.redshop';

/// Define paths of source and destination
var extPath = '.';
var mediaPath = extPath + '/media/' + componentName;
var assetsPath = extPath + '/src/assets/' + componentName;

/// Minified and deploy from Src to Media.
gulp.task('scripts:' + baseTask, function () {
    return gulp.src([
        assetsPath + '/js/*.js',
        assetsPath + '/js/**/*.js'
    ])
        .pipe(changed(mediaPath + "/js"))
        .pipe(rename(function (path) {
            path.basename += '-uncompressed';
        }))
        .pipe(gulp.dest(mediaPath + '/js'))
        .pipe(uglify())
        .pipe(rename(function (path) {
            path.basename = path.basename.replace('-uncompressed', '');
        }))
        .pipe(gulp.dest(mediaPath + '/js'));
});

/// Sass Compiler
gulp.task('sass:' + baseTask, function () {
    return gulp.src([
        assetsPath + "/scss/*.scss",
        assetsPath + "/scss/**/*.scss"
    ])
        .pipe(changed(mediaPath + "/css"))
        .pipe(rename(function (path) {
            path.basename += '-uncompressed';
        }))
        .pipe(sass())
        .pipe(gulp.dest(mediaPath + "/css"))
        .pipe(sass({
            outputStyle: "compressed",
            errLogToConsole: true
        }))
        .pipe(rename(function (path) {
            path.basename = path.basename.replace('-uncompressed', '');
        }))
        .pipe(gulp.dest(mediaPath + "/css"));
});

/// Watcher for Assets only
gulp.task('watch:' + baseTask + '.assets',
    [
        'watch:' + baseTask + ':asset:script',
        'watch:' + baseTask + ':asset:sass',
        'watch:' + baseTask + ':media'
    ]
);

/// Watcher will watching for scss changes in Src/assets,
/// then minify its and copy to Media
gulp.task('watch:' + baseTask + ':asset:sass', function() {
    gulp.watch([assetsPath + "/scss/*.scss", assetsPath + "/scss/**/*.scss"], ['sass:' + baseTask]);
});

/// Watcher will watching for js changes in Src/assets,
/// then minify its and copy to Media
gulp.task('watch:' + baseTask + ':asset:script', function () {
    gulp.watch([assetsPath + '/js/**/*.js', assetsPath + '/js/*.js'], ['scripts:' + baseTask]);
});

gulp.task('copy:' + baseTask + '.assets', function() { return true; });