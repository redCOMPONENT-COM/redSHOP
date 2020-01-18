var gulp = require('gulp');

// Load config
var config = require('../../../gulp-config.json');

// Dependencies
var browserSync = require('browser-sync');
var del         = require('del');

var group = 'redshop_export';
var name  = 'user';

var baseTask   = 'plugins.' + group + '.' + name;
var extPath    = './plugins/' + group + '/' + name;

var wwwExtPath = config.wwwDir + '/plugins/' + group + '/' + name;

// Clean: plugin
gulp.task('clean:' + baseTask, function() {
    return del(wwwExtPath, {force : true});
});

// Copy: plugin
gulp.task('copy:' + baseTask, gulp.series('clean:' + baseTask), function() {
    return gulp.src([
            extPath + '/**'
        ])
        .pipe(gulp.dest(wwwExtPath));
});

// Watch
gulp.task('watch:' + baseTask, function() {
    gulp.watch([
            extPath + '/**/*'
        ],
        gulp.series('copy:' + baseTask, browserSync.reload)
    );
});
