var gulp = require('gulp');

// Load config
var config = require('./../../../gulp-config.json');

// Dependencies
var browserSync = require('browser-sync');
var del = require('del');

/**
 *
 * @param {*} group
 * @param {*} name
 */
function releasePlugin(group, name) {
    var baseTask = 'plugins.' + group + '.' + name;
    var extPath = './plugins/' + group + '/' + name;

    var wwwExtPath = config.wwwDir + '/plugins/' + group + '/' + name;


    // Clean: plugin
    gulp.task('clean:' + baseTask + ':plugin', function (cb) {
        return del(wwwExtPath, {force: true});
    });

    // Clean: lang
    gulp.task('clean:' + baseTask + ':language', function (cb) {
        return del(config.wwwDir + '/language/**/*.plg_' + group + '_' + name + '.*', {force: true});
    });

    // Clean
    gulp.task('clean:' + baseTask,
        gulp.series(
            'clean:' + baseTask + ':plugin',
            'clean:' + baseTask + ':language'
        ),
        function (cb) {
            cb();
        });

    // Copy: plugin
    gulp.task('copy:' + baseTask + ':plugin',
        function (cb) {
            return gulp.src([
                extPath + '/**',
                '!' + extPath + '/language',
                '!' + extPath + '/language/**'
            ])
                .pipe(gulp.dest(wwwExtPath)).on('end', cb);
        });

    // Copy: Language
    gulp.task('copy:' + baseTask + ':language', function (cb) {
            return gulp.src(extPath + '/language/**')
                .pipe(gulp.dest(config.wwwDir + '/language')).on('end', cb);
        });

    // Copy
    gulp.task('copy:' + baseTask,
        gulp.series(
            'copy:' + baseTask + ':plugin',
            'copy:' + baseTask + ':language'
        ),
        function (cb) {
            cb();
        });

    // Watch: plugin
    gulp.task('watch:' + baseTask + ':plugin', function (cb) {
        gulp.watch([
                extPath + '/**/*',
                '!' + extPath + '/language',
                '!' + extPath + '/language/**'
            ],
            gulp.series('copy:' + baseTask, browserSync.reload)
        ).on('end', cb);
    });

    // Watch: Language
    gulp.task('watch:' + baseTask + ':language', function (cb) {
        gulp.watch([
                extPath + '/language/**'
            ],
            gulp.series('copy:' + baseTask + ':language', browserSync.reload)).on('end', cb);
    });

    // Watch
    gulp.task('watch:' + baseTask,
        gulp.series(
            'watch:' + baseTask + ':plugin',
            'watch:' + baseTask + ':language'
        ),
        function () {
        });
}

global.releasePlugin = releasePlugin;
