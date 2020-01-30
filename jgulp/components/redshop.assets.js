/**
 * Gulp components for redSHOP,
 * This is for developer mode only.
 * Don't ever use this on live site.
 *
 * Short Tag:
 * - gulp watch
 * - gulp copy
 *
 * Every task begin with <task name>:components.redshop
 *
 * There are 3 task types:
 * - clean
 * - copy
 * - watch
 *
 * We handle scripts by gulp pipe and uglify.
 * We handle css by sass and minify
 *
 * For more details:
 * - https://www.npmjs.com/package/gulp-watch
 * - http://sass-lang.com
 * - https://github.com/mishoo/UglifyJS
 */

/// Define gulp and its config
const gulp   = require('gulp');

require('./redshop.js');

/// Define component tasks
const componentName = 'com_redshop';

/// Define paths of source and destination
const extPath    = '.';

gulp.task('clean:components.redshop.assets', function () {
    return true;
});

gulp.task('copy:components.redshop.assets', function (cb) {
    cb();
});

/// Watcher for Assets only
gulp.task('watch:components.redshop.assets',
    gulp.series(
        'watch:components.redshop:asset:script',
        'watch:components.redshop:asset:sass',
        'watch:components.redshop:media'
    )
);