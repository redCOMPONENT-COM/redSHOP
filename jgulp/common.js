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
    gulp.task('clean:components.redshop:plugin', function() {
        return del(wwwExtPath, {force : true});
    });

// Clean: lang
    gulp.task('clean:components.redshop:language', function() {
        return del(config.wwwDir + '/language/**/*.plg_' + group + '_' + name + '.*', {force: true});
    });


// Clean
    gulp.task('clean:' + baseTask,
        gulp.series(
            'clean:components.redshop:plugin',
            'clean:components.redshop:language'
        ),
        function() {
        });

// Copy: plugin
    gulp.task('copy:components.redshop:plugin', gulp.series('clean:components.redshop:plugin'), function() {
        return gulp.src([
            extPath + '/**',
            '!' + extPath + '/language',
            '!' + extPath + '/language/**'
        ])
            .pipe(gulp.dest(wwwExtPath));
    });

// Copy: Language
    gulp.task('copy:components.redshop:language', gulp.series('clean:components.redshop:language'), function() {
        return gulp.src(extPath + '/language/**')
            .pipe(gulp.dest(config.wwwDir + '/language'));
    });


// Copy
    gulp.task('copy:' + baseTask,
        gulp.series(
            'copy:components.redshop:plugin',
            'copy:components.redshop:language'
        ),
        function() {
        });

// Watch: plugin
    gulp.task('watch:components.redshop:plugin', function() {
        gulp.watch([
                extPath + '/**/*',
                '!' + extPath + '/language',
                '!' + extPath + '/language/**'
            ],
            gulp.series('copy:' + baseTask, browserSync.reload)
        );
    });

// Watch: Language
    gulp.task('watch:components.redshop:language', function() {
        gulp.watch([
                extPath + '/language/**'
            ],
            gulp.series('copy:components.redshop:language', browserSync.reload));
    });


// Watch
    gulp.task('watch:' + baseTask,
        gulp.series(
            'watch:components.redshop:plugin',
            'watch:components.redshop:language'
        ),
        function() {
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
    gulp.task('clean:components.redshop:module', function() {
        return del(wwwPath, {force: true});
    });

// Clean: Language
    gulp.task('clean:components.redshop:language', function() {
        return del(config.wwwDir + '/language/**/*.mod_' + modName + '.*', {force: true});
    });

// Clean
    gulp.task('clean:' + baseTask,
        gulp.series(
            'clean:components.redshop:module',
            'clean:components.redshop:language'
        ),
        function() {
        });

// Copy: Module
    gulp.task('copy:components.redshop:module', gulp.series('clean:components.redshop:module'), function() {
        return gulp.src([
            extPath + '/**',
            '!' + extPath + '/language',
            '!' + extPath + '/language/**'
        ])
            .pipe(gulp.dest(wwwPath));
    });

// Copy: Language
    gulp.task('copy:components.redshop:language', gulp.series('clean:components.redshop:language'), function() {
        return gulp.src(extPath + '/language/**')
            .pipe(gulp.dest(config.wwwDir + '/language'));
    });

// Copy: Module
    gulp.task('copy:' + baseTask,
        gulp.series(
            'copy:components.redshop:module',
            'copy:components.redshop:language'
        ),
        function() {
        });

// Watch: Module
    gulp.task('watch:components.redshop:module', function() {
        gulp.watch([
                extPath + '/**/*',
                '!' + extPath + 'language',
                '!' + extPath + 'language/**'
            ],
            gulp.series('copy:components.redshop:module', browserSync.reload));
    });

// Watch: Language
    gulp.task('watch:components.redshop:language', function() {
        gulp.watch([
                extPath + '/language/**'
            ],
            gulp.series('copy:components.redshop:language', browserSync.reload));
    });

// Watch
    gulp.task('watch:' + baseTask,
        gulp.series(
            'watch:components.redshop:module',
            'watch:components.redshop:language'
        ),
        function() {
        });
}