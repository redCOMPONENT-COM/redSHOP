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
    gulp.task('clean:' + baseTask + ':plugin', function() {
        return del(wwwExtPath, {force : true});
    });

// Clean: lang
    gulp.task('clean:' + baseTask + ':language', function() {
        return del(config.wwwDir + '/language/**/*.plg_' + group + '_' + name + '.*', {force: true});
    });


// Clean
    gulp.task('clean:' + baseTask,
        gulp.series(
            'clean:' + baseTask + ':plugin',
            'clean:' + baseTask + ':language'
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


// Copy
    gulp.task('copy:' + baseTask,
        gulp.series(
            'copy:' + baseTask + ':plugin',
            'copy:' + baseTask + ':language'
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
            gulp.series('copy:' + baseTask, browserSync.reload)
        );
    });

// Watch: Language
    gulp.task('watch:' + baseTask + ':language', function() {
        gulp.watch([
                extPath + '/language/**'
            ],
            gulp.series('copy:' + baseTask + ':language', browserSync.reload));
    });


// Watch
    gulp.task('watch:' + baseTask,
        gulp.series(
            'watch:' + baseTask + ':plugin',
            'watch:' + baseTask + ':language'
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
    gulp.task('clean:' + baseTask + ':module', function() {
        return del(wwwPath, {force: true});
    });

// Clean: Language
    gulp.task('clean:' + baseTask + ':language', function() {
        return del(config.wwwDir + '/language/**/*.mod_' + modName + '.*', {force: true});
    });

// Clean
    gulp.task('clean:' + baseTask,
        gulp.series(
            'clean:' + baseTask + ':module',
            'clean:' + baseTask + ':language'
        ),
        function() {
        });

// Copy: Module
    gulp.task('copy:' + baseTask + ':module', gulp.series('clean:' + baseTask + ':module'), function() {
        return gulp.src([
            extPath + '/**',
            '!' + extPath + '/language',
            '!' + extPath + '/language/**'
        ])
            .pipe(gulp.dest(wwwPath));
    });

// Copy: Language
    gulp.task('copy:' + baseTask + ':language', gulp.series('clean:' + baseTask + ':language'), function() {
        return gulp.src(extPath + '/language/**')
            .pipe(gulp.dest(config.wwwDir + '/language'));
    });

// Copy: Module
    gulp.task('copy:' + baseTask,
        gulp.series(
            'copy:' + baseTask + ':module',
            'copy:' + baseTask + ':language'
        ),
        function() {
        });

// Watch: Module
    gulp.task('watch:' + baseTask + ':module', function() {
        gulp.watch([
                extPath + '/**/*',
                '!' + extPath + 'language',
                '!' + extPath + 'language/**'
            ],
            gulp.series('copy:' + baseTask + ':module', browserSync.reload));
    });

// Watch: Language
    gulp.task('watch:' + baseTask + ':language', function() {
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
        function() {
        });
}