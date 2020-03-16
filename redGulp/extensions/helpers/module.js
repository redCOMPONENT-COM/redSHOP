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
    gulp.task('clean:' + baseTask + ':module', function () {
        return del(wwwPath, { force: true });
    });

    // Clean: Language
    gulp.task('clean:' + baseTask + ':language', function () {
        return del(config.wwwDir + '/language/**/*.mod_' + modName + '.*', { force: true });
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
    gulp.task('copy:' + baseTask + ':module', gulp.series('clean:' + baseTask + ':module'), function () {
        return gulp.src([
            extPath + '/**',
            '!' + extPath + '/language',
            '!' + extPath + '/language/**'
        ])
            .pipe(gulp.dest(wwwPath));
    });

    // Copy: Language
    gulp.task('copy:' + baseTask + ':language', gulp.series('clean:' + baseTask + ':language'), function () {
        return gulp.src(extPath + '/language/**')
            .pipe(gulp.dest(config.wwwDir + '/language'));
    });

    // Copy: Module
    gulp.task('copy:' + baseTask,
        gulp.series(
            'copy:' + baseTask + ':module',
            'copy:' + baseTask + ':language'
        ),
        function () {
        });

    // Watch: Module
    gulp.task('watch:' + baseTask + ':module', function () {
        gulp.watch([
            extPath + '/**/*',
            '!' + extPath + 'language',
            '!' + extPath + 'language/**'
        ],
            gulp.series('copy:' + baseTask + ':module', browserSync.reload));
    });

    // Watch: Language
    gulp.task('watch:' + baseTask + ':language', function () {
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