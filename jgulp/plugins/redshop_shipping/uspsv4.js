var gulp = require('gulp');

// Load config
var config = require('../../../gulp-config.json');

// Dependencies
var browserSync = require('browser-sync');
var del = require('del');

var group = 'redshop_shipping';
var name = 'uspsv4';

var baseTask = 'plugins.' + group + '.' + name;
var extPath = './plugins/' + group + '/' + name;

var wwwExtPath = config.wwwDir + '/plugins/' + group + '/' + name;

// Clean
gulp.task('clean:' + baseTask,
    [
        'clean:' + baseTask + ':plugin'
    ],
    function () {
    });

// Clean: plugin
gulp.task('clean:' + baseTask + ':plugin', function () {
    return del(wwwExtPath, {force: true});
});


// Copy
gulp.task('copy:' + baseTask,
    [
        'copy:' + baseTask + ':plugin'
    ],
    function () {
    });

// Copy: plugin
gulp.task('copy:' + baseTask + ':plugin', ['clean:' + baseTask + ':plugin'], function () {
    return gulp.src([
        extPath + '/**'
    ])
        .pipe(gulp.dest(wwwExtPath));
});

// Watch
gulp.task('watch:' + baseTask,
    [
        'watch:' + baseTask + ':plugin'
    ],
    function () {
    });

// Watch: plugin
gulp.task('watch:' + baseTask + ':plugin', function () {
    gulp.watch([
            extPath + '/**/*'
        ],
        ['copy:' + baseTask, browserSync.reload]
    );
});
