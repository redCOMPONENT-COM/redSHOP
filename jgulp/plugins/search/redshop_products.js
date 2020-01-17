var gulp = require('gulp');

// Load config
var config = require('../../../gulp-config.json');

// Dependencies
var browserSync = require('browser-sync');
var del         = require('del');

var group = 'search';
var name  = 'redshop_products';

var baseTask   = 'plugins.' + group + '.' + name;
var extPath    = './plugins/' + group + '/' + name;

var wwwExtPath = config.wwwDir + '/plugins/' + group + '/' + name;

// Clean
gulp.task('clean:' + baseTask,
    gulp.series(
        'clean:' + baseTask + ':plugin',
        'clean:' + baseTask + ':language'
    ),
    function() {
    });

// Clean: plugin
gulp.task('clean:' + baseTask + ':plugin', function() {
    return del(wwwExtPath, {force : true});
});

// Clean: lang
gulp.task('clean:' + baseTask + ':language', function() {
    return del(config.wwwDir + '/language/**/*.plg_' + group + '_' + name + '.*', {force: true});
});


// Copy
gulp.task('copy:' + baseTask,
    gulp.series(
        'copy:' + baseTask + ':plugin',
        'copy:' + baseTask + ':language'
    ),
    function() {
    });

// Copy: plugin
gulp.task('copy:' + baseTask + ':plugin', gulp.series('clean:' + baseTask + ':plugin'), function() {
    return gulp.src([
            extPath + '/**',
            '!' + extPath + '/language',
            '!' + extPath + '/language/**'
        ])
        .pipe(gulp.dest(wwwExtPath));
});

// Copy: Language
gulp.task('copy:' + baseTask + ':language', gulp.series('clean:' + baseTask + ':language'), function() {
    return gulp.src(extPath + '/language/**')
        .pipe(gulp.dest(config.wwwDir + '/language'));
});

// Watch
gulp.task('watch:' + baseTask,
    gulp.series(
        'watch:' + baseTask + ':plugin',
        'watch:' + baseTask + ':language'
    ),
    function() {
    });

// Watch: plugin
gulp.task('watch:' + baseTask + ':plugin', function() {
    gulp.watch([
            extPath + '/**/*',
            '!' + extPath + '/language',
            '!' + extPath + '/language/**'
        ],
        ['copy:' + baseTask, browserSync.reload]
    );
});

// Watch: Language
gulp.task('watch:' + baseTask + ':language', function() {
    gulp.watch([
            extPath + '/language/**'
        ],
        ['copy:' + baseTask + ':language', browserSync.reload]);
});
