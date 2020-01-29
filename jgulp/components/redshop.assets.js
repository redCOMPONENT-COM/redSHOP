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
var gulp   = require('gulp');
var config = require('../../gulp-config.json');

/// Define dependencies
var del     = require('del');
var sass    = require('gulp-sass');
var rename  = require('gulp-rename');
var uglify  = require('gulp-uglify');
var log     = require('fancy-log');
var color   = require('ansi-colors');

require('./redshop.js');

/// Define component tasks
var componentName = 'com_redshop';

/// Define paths of source and destination
var extPath    = '.';
var mediaPath  = extPath + '/media/' + componentName;
var assetsPath = extPath + '/src/assets/' + componentName;


/// Watcher will watching for scss changes in Src/assets,
/// then minify its and copy to Media
gulp.task('watch:components.redshop:asset:sass', function () {
    gulp.watch([assetsPath + "/scss/*.scss", assetsPath + "/scss/**/*.scss"], gulp.series('sass:components.redshop'));
});

/// Watcher will watching for js changes in Src/assets,
/// then minify its and copy to Media
gulp.task('watch:components.redshop:asset:script', function () {
    gulp.watch([assetsPath + '/js/**/*.js', assetsPath + '/js/*.js'], gulp.series('scripts:components.redshop'));
});

/// Minified and deploy from Src to Media.
gulp.task('scripts:components.redshop', function () {
    return gulp.src([
            assetsPath + '/js/*.js',
            assetsPath + '/js/**/*.js'
        ])
        .pipe(gulp.dest(mediaPath + '/js'))
        .pipe(uglify())
        .on('error', function (err) { log(color.red('[Error]'), err.toString()); })
        .pipe(rename(function (path) {
            path.basename += '.min';
        }))
        .pipe(gulp.dest(mediaPath + '/js'));
});

/// Sass Compiler
gulp.task('sass:components.redshop', function () {
    return gulp.src([
            assetsPath + "/scss/*.scss",
            assetsPath + "/scss/**/*.scss"
        ])
        .pipe(sass())
        .pipe(gulp.dest(mediaPath + "/css"))
        .pipe(sass({
            outputStyle    : "compressed",
            errLogToConsole: true
        }))
        .pipe(rename(function (path) {
            path.basename += '.min';
        }))
        .pipe(gulp.dest(mediaPath + "/css"));
});

gulp.task('copy:components.redshop.assets', function () {
    return true;
});

/// Watcher for Assets only
gulp.task('watch:components.redshop.assets',
    gulp.series(
        'watch:components.redshop:asset:script',
        'watch:components.redshop:asset:sass',
        'watch:components.redshop:media'
    )
);