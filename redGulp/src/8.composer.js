var gulp = require("gulp");
var path = require("path");
var gutil = require('gulp-util');
var zip = require("gulp-zip");
var fs = require("fs");
var del = require('del');
var sass = require('gulp-sass');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
// Get console args
var argv = require("yargs").argv;
// XML parser
var xml2js = require("xml2js");
var parser = new xml2js.Parser();
var config = require('../../gulp-config.json');


/// Define component tasks
var componentName = 'com_redshop';

/// Define paths of source and destination
var extPath = '.';
var mediaPath = extPath + '/media/' + componentName;
var assetsPath = extPath + '/src/assets/' + componentName;


// Composer
gulp.task('clean:libraries.redshop:composer.lock', function (cb) {
    del(extPath + '/composer.lock', { force: true });
    cb();
});

gulp.task("composer:libraries.redshop", gulp.series('clean:libraries.redshop:composer.lock'), function (cb) {
    executeComposer(extPath).on('end', cb);
});

/// Minified and deploy from Src to Media.
gulp.task('scripts:components.redshop', function (cb) {
    return gulp.src([
        assetsPath + '/js/*.js',
        assetsPath + '/js/**/*.js'
    ])
        .pipe(gulp.dest(mediaPath + '/js'))
        .pipe(uglify())
        .on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
        .pipe(rename(function (path) {
            path.basename += '.min';
        }))
        .pipe(gulp.dest(mediaPath + '/js'))
        .on("end", cb);
});

/// Sass Compiler
gulp.task('sass:components.redshop', function (cb) {
    return gulp.src([
        assetsPath + "/scss/*.scss",
        assetsPath + "/scss/**/*.scss"
    ])
        .pipe(sass())
        .pipe(gulp.dest(mediaPath + "/css"))
        .pipe(sass({
            outputStyle: "compressed",
            errLogToConsole: true
        }))
        .pipe(rename(function (path) {
            path.basename += '.min';
        }))
        .pipe(gulp.dest(mediaPath + "/css"))
        .on("end", cb);
});