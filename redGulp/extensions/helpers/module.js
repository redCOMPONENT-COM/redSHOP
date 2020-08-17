var gulp = require('gulp');
// Dependencies
var browserSync = require('browser-sync');
var concat = require('gulp-concat');
var del = require('del');
var path = require('path');

/**
 *
 * @param {string} modName
 * @param {string} modFolder
 * @param {string} modBase
 */
function releaseModule(modName, modFolder, modBase) {

    var baseTask = 'modules.frontend.' + modName;
    var extPath = './modules/' + modBase + '/' + modFolder;
    var wwwPath = config.wwwDir + '/modules/' + modFolder

    // Clean: Module
    gulp.task('clean:' + baseTask + ':module', function (cb) {
        del(wwwPath, {force: true});
        cb();
    });

    // Clean: Language
    gulp.task('clean:' + baseTask + ':language', function (cb) {
        del(config.wwwDir + '/language/**/*.mod_' + modName + '.*', {force: true});
        cb();
    });

    // Clean
    gulp.task('clean:' + baseTask,
        gulp.series(
            'clean:' + baseTask + ':module',
            'clean:' + baseTask + ':language'
        ),
        function () {
        });

    // Copy: Module
    gulp.task('copy:' + baseTask + ':module', function (cb) {
        gulp.src([
            extPath + '/*.*',
            extPath + '/**/*.*',
            '!' + extPath + '/language',
            '!' + extPath + '/language/**'
        ]).pipe(gulp.dest(wwwPath));
        cb();
    });

    // Copy: Language
    gulp.task('copy:' + baseTask + ':language', function (cb) {
        gulp.src(extPath + '/language/**')
            .pipe(gulp.dest(config.wwwDir + '/language'));
        cb();
    });

    // Copy: Module
    gulp.task('copy:' + baseTask,
        gulp.series(
            'copy:' + baseTask + ':module',
            'copy:' + baseTask + ':language'
        ),
        function (cb) {
            cb();
        });

    // Watch: Module
    gulp.task('watch:' + baseTask + ':module', function (cb) {
        gulp.watch([
                extPath + '/**/*',
                '!' + extPath + 'language',
                '!' + extPath + 'language/**'
            ],
            gulp.series('copy:' + baseTask + ':module', browserSync.reload));
    });

    // Watch: Language
    gulp.task('watch:' + baseTask + ':language', function (cb) {
        gulp.watch([
                extPath + '/language/**'
            ],
            gulp.series('copy:' + baseTask + ':language', browserSync.reload));
    });

    // Watch
    gulp.task('watch:' + baseTask,
        gulp.series(
            'watch:' + baseTask + ':module',
            'watch:' + baseTask + ':language'
        ),
        function () {
        });

}

global.releaseModule = releaseModule;