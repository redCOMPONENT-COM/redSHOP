var gulp = require('gulp');

// Load config
var config = require('../../../gulp-config.json');

// Dependencies
var browserSync = require('browser-sync');
var del         = require('del');

var group = 'redshop_user';
var name  = 'cmc_integrate';

var baseTask   = 'plugins.' + group + '.' + name;
var extPath    = './plugins/' + group + '/' + name;

var wwwExtPath = config.wwwDir + '/plugins/' + group + '/' + name;

// Clean
gulp.task('clean:' + baseTask, function() {
    return del(wwwExtPath, {force : true});
});


// Copy
gulp.task('copy:' + baseTask, function() {
    return gulp.src([
        extPath + '/**'
    ])
        .pipe(gulp.dest(wwwExtPath));
});

// Watch
gulp.task('watch:' + baseTask, function() {
    gulp.watch(
        [extPath + '/**/*'],
        ['copy:' + baseTask, browserSync.reload]
    );
});
