var gulp      = require('gulp');
var config    = require('../../../gulp-config.json');

// Dependencies
var browserSync = require('browser-sync');
var concat      = require('gulp-concat');
var del         = require('del');
var path        = require('path');

var modName   = "redshop_filter";
var modFolder = "mod_" + modName;
var modBase   = "site";

var baseTask = 'modules.frontend.' + modName;
var extPath  = './modules/' + modBase + '/' + modFolder;
var wwwPath  = config.wwwDir + '/modules/' + modFolder

// Clean
gulp.task('clean:' + baseTask,
    [
        'clean:' + baseTask + ':module',
        'clean:' + baseTask + ':language'
    ],
    function() {
    });

// Clean: Module
gulp.task('clean:' + baseTask + ':module', function() {
    return del(wwwPath, {force: true});
});

// Clean: Language
gulp.task('clean:' + baseTask + ':language', function() {
    return del(config.wwwDir + '/language/**/*.mod_' + modName + '.*', {force: true});
});

// Copy: Module
gulp.task('copy:' + baseTask,
    [
        'copy:' + baseTask + ':module',
        'copy:' + baseTask + ':language'
    ],
    function() {
    });

// Copy: Module
gulp.task('copy:' + baseTask + ':module', ['clean:' + baseTask + ':module'], function() {
    return gulp.src([
        extPath + '/**',
        '!' + extPath + '/language',
        '!' + extPath + '/language/**'
    ])
        .pipe(gulp.dest(wwwPath));
});

// Copy: Language
gulp.task('copy:' + baseTask + ':language', ['clean:' + baseTask + ':language'], function() {
    return gulp.src(extPath + '/language/**')
        .pipe(gulp.dest(config.wwwDir + '/language'));
});

// Watch
gulp.task('watch:' + baseTask,
    [
        'watch:' + baseTask + ':module',
        'watch:' + baseTask + ':language'
    ],
    function() {
    });

// Watch: Module
gulp.task('watch:' + baseTask + ':module', function() {
    gulp.watch([
            extPath + '/**/*',
            '!' + extPath + 'language',
            '!' + extPath + 'language/**'
        ],
        ['copy:' + baseTask + ':module', browserSync.reload]);
});

// Watch: Language
gulp.task('watch:' + baseTask + ':language', function() {
    gulp.watch([
            extPath + '/language/**'
        ],
        ['copy:' + baseTask + ':language', browserSync.reload]);
});
