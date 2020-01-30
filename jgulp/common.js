var gulp = require('gulp');

// Load config
var config = require('../gulp-config');

// Dependencies
var browserSync = require('browser-sync');
var del         = require('del');
var concat      = require('gulp-concat');
var path        = require('path');

/**
 * Release Plugin
 * @param group
 * @param name
 */
global.releasePlugin = function releasePlugin(group, name)
{

    var baseTask   = 'plugins.' + group + '.' + name;
    var extPath    = './plugins/' + group + '/' + name;

    var wwwExtPath = config.wwwDir + '/plugins/' + group + '/' + name;

// Clean: plugin
    gulp.task('clean:components.redshop:plugin', function (cb) {
        return del(wwwExtPath, {force : true});
        cb();
    });

// Clean: lang
    gulp.task('clean:components.redshop:language', function (cb) {
        return del(config.wwwDir + '/language/**/*.plg_' + group + '_' + name + '.*', {force: true});
        cb();
    });


// Clean
    gulp.task('clean:' + baseTask,
        gulp.series(
            'clean:components.redshop:plugin',
            'clean:components.redshop:language'
        ),
        function (cb) {
            cb();
        });

// Copy: plugin
    gulp.task('copy:components.redshop:plugin', gulp.series('clean:components.redshop:plugin'), function (cb) {
        return gulp.src([
            extPath + '/**',
            '!' + extPath + '/language',
            '!' + extPath + '/language/**'
        ])
            .pipe(gulp.dest(wwwExtPath));
        cb();
    });

// Copy: Language
    gulp.task('copy:components.redshop:language', gulp.series('clean:components.redshop:language'), function (cb) {
        return gulp.src(extPath + '/language/**')
            .pipe(gulp.dest(config.wwwDir + '/language'));
        cb();
    });


// Copy
    gulp.task('copy:' + baseTask,
        gulp.series(
            'copy:components.redshop:plugin',
            'copy:components.redshop:language'
        ),
        function (cb) {
            cb();
        });

// Watch: plugin
    gulp.task('watch:components.redshop:plugin', function (cb) {
        gulp.watch([
                extPath + '/**/*',
                '!' + extPath + '/language',
                '!' + extPath + '/language/**'
            ],
            gulp.series('copy:' + baseTask, browserSync.reload)
        );
        cb();
    });

// Watch: Language
    gulp.task('watch:components.redshop:language', function (cb) {
        gulp.watch([
                extPath + '/language/**'
            ],
            gulp.series('copy:components.redshop:language', browserSync.reload));
        cb();
    });


// Watch
    gulp.task('watch:' + baseTask,
        gulp.series(
            'watch:components.redshop:plugin',
            'watch:components.redshop:language'
        ),
        function (cb) {
            cb();
        });

}

/**
 * Release Module
 * @param modBase
 * @param modFolder
 * @param modName
 */
global.releaseModule = function releaseModule(modBase, modFolder, modName)
{

    var baseTask = 'modules.frontend.' + modName;
    var extPath  = './modules/' + modBase + '/' + modFolder;
    var wwwPath  = config.wwwDir + '/modules/' + modFolder

// Clean: Module
    gulp.task('clean:components.redshop:module', function (cb) {
        return del(wwwPath, {force: true});
        cb();
    });

// Clean: Language
    gulp.task('clean:components.redshop:language', function (cb) {
        return del(config.wwwDir + '/language/**/*.mod_' + modName + '.*', {force: true});
        cb();
    });

// Clean
    gulp.task('clean:' + baseTask,
        gulp.series(
            'clean:components.redshop:module',
            'clean:components.redshop:language'
        ),
        function (cb) {
            cb();
        });

// Copy: Module
    gulp.task('copy:components.redshop:module', gulp.series('clean:components.redshop:module'), function (cb) {
        return gulp.src([
            extPath + '/**',
            '!' + extPath + '/language',
            '!' + extPath + '/language/**'
        ])
            .pipe(gulp.dest(wwwPath));
        cb();
    });

// Copy: Language
    gulp.task('copy:components.redshop:language', gulp.series('clean:components.redshop:language'), function (cb) {
        return gulp.src(extPath + '/language/**')
            .pipe(gulp.dest(config.wwwDir + '/language'));
        cb();
    });

// Copy: Module
    gulp.task('copy:' + baseTask,
        gulp.series(
            'copy:components.redshop:module',
            'copy:components.redshop:language'
        ),
        function (cb) {
            cb();
        });

// Watch: Module
    gulp.task('watch:components.redshop:module', function (cb) {
        gulp.watch([
                extPath + '/**/*',
                '!' + extPath + 'language',
                '!' + extPath + 'language/**'
            ],
            gulp.series('copy:components.redshop:module', browserSync.reload));
        cb();
    });

// Watch: Language
    gulp.task('watch:components.redshop:language', function (cb) {
        gulp.watch([
                extPath + '/language/**'
            ],
            gulp.series('copy:components.redshop:language', browserSync.reload));
        cb();
    });

// Watch
    gulp.task('watch:' + baseTask,
        gulp.series(
            'watch:components.redshop:module',
            'watch:components.redshop:language'
        ),
        function (cb) {
            cb();
        });
}