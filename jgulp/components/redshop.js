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
var gulp = require('gulp');
var config = require('../../gulp-config.json');

/// Define dependencies
var browserSync = require('browser-sync');
var del = require('del');
var sass = require('gulp-sass');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var gutil = require('gulp-util');

/// Define component tasks
var componentName = 'com_redshop';
var baseTask = 'components.redshop';

/// Define paths of source and destination
var extPath = '.';
var mediaPath = extPath + '/media/' + componentName;
var assetsPath = extPath + '/src/assets/' + componentName;
var wwwMediaPath = config.wwwDir + '/media/' + componentName;

/// Minified and deploy from Src to Media.
gulp.task('scripts:' + baseTask, function () {
    return gulp.src([
        assetsPath + '/js/*.js',
        assetsPath + '/js/**/*.js'
    ])
        .pipe(rename(function (path) {
            path.basename += '-uncompressed';
        }))
        .pipe(gulp.dest(mediaPath + '/js'))
        .pipe(uglify())
        .pipe(rename(function (path) {
            path.basename = path.basename.replace('-uncompressed', '');
        }))
        .pipe(gulp.dest(mediaPath + '/js'));
});

/// Sass Compiler
gulp.task('sass:' + baseTask, function () {
    return gulp.src([
        assetsPath + "/scss/*.scss",
        assetsPath + "/scss/**/*.scss"
    ])
        .pipe(rename(function (path) {
            path.basename += '-uncompressed';
        }))
        .pipe(sass())
        .pipe(gulp.dest(mediaPath + "/css"))
        .pipe(sass({
            outputStyle: "compressed",
            errLogToConsole: true
        }))
        .pipe(rename(function (path) {
            path.basename = path.basename.replace('-uncompressed', '');
        }))
        .pipe(gulp.dest(mediaPath + "/css"));
});

/// Main clean task
/// gulp clean:components.redshop
gulp.task('clean:' + baseTask,
    [
        'clean:' + baseTask + ':frontend',
        'clean:' + baseTask + ':backend',
        'clean:' + baseTask + ':media'
    ],
    function () {
        return true;
    }
);

/// Clean: frontend, will clean components from Sites
/// gulp clean:components.redshop:frontend
gulp.task('clean:' + baseTask + ':frontend', function (cb) {
    del(config.wwwDir + '/language/**/*.' + componentName + '.*', {force: true});

    return del([
        config.wwwDir + '/components/com_redshop/controllers',
        config.wwwDir + '/components/com_redshop/helpers',
        config.wwwDir + '/components/com_redshop/language',
        config.wwwDir + '/components/com_redshop/layouts',
        config.wwwDir + '/components/com_redshop/models',
        config.wwwDir + '/components/com_redshop/templates',
        config.wwwDir + '/components/com_redshop/views/**/*.html.php',
        config.wwwDir + '/components/com_redshop/views/**/tmpl/*.php'
    ], {force: true});
});

/// Clean: backend, will clean components from Administrator
/// gulp clean:components.redshop:backend
gulp.task('clean:' + baseTask + ':backend', function (cb) {
    return del([
        config.wwwDir + '/administrator/language/**/*.' + componentName + '.*',
        config.wwwDir + '/administrator/components/' + componentName + '/**',
        '!' + config.wwwDir + '/administrator/components/' + componentName,
        '!' + config.wwwDir + '/administrator/components/' + componentName + '/config',
        '!' + config.wwwDir + '/administrator/components/' + componentName + '/config/*.php'
    ], {force: true});
});

/// Clean: media, will clean files from Media
/// gulp clean:components.redshop:media
gulp.task('clean:' + baseTask + ':media', function (cb) {
    return del(
        [
            config.wwwDir + '/media/' + componentName + '/**',
            '!' + config.wwwDir + '/media/' + componentName,
            '!' + config.wwwDir + '/media/' + componentName + '/images',
            '!' + config.wwwDir + '/media/' + componentName + '/images/**'
        ],
        {force: true}
    );
});

/// Main copy task
/// gulp copy:components.redshop
gulp.task('copy:' + baseTask,
    [
        'clean:' + baseTask,
        'copy:' + baseTask + ':frontend',
        'copy:' + baseTask + ':backend',
        'copy:' + baseTask + ':media',
    ],
    function () {
    });

/// Copy: frontend
gulp.task('copy:' + baseTask + ':frontend', ['clean:' + baseTask + ':frontend'], function () {
    gulp.src(extPath + '/component/site/language/**')
        .pipe(gulp.dest(config.wwwDir + '/language'));

    return gulp.src([
        extPath + '/component/site/**',
        '!' + extPath + '/component/site/language',
        '!' + extPath + '/component/site/language/**'
    ])
        .pipe(gulp.dest(config.wwwDir + '/components/' + componentName));
});

/// Copy: backend
gulp.task('copy:' + baseTask + ':backend', function (cb) {
    return (
        gulp.src([
            extPath + '/component/admin/**',
            "!" + extPath + '/component/admin/config',
            "!" + extPath + '/component/admin/config/**'
        ])
            .pipe(gulp.dest(config.wwwDir + '/administrator/components/' + componentName)) &&
        gulp.src(extPath + '/redshop.xml')
            .pipe(gulp.dest(config.wwwDir + '/administrator/components/' + componentName)) &&
        gulp.src(extPath + '/install.php')
            .pipe(gulp.dest(config.wwwDir + '/administrator/components/' + componentName))
    );
});

// Copy: media
gulp.task('copy:' + baseTask + ':media', ['clean:' + baseTask + ':media'], function () {
    return gulp.src(mediaPath + '/**')
        .pipe(gulp.dest(config.wwwDir + '/media/' + componentName));
});

/// Call another watcher
gulp.task('watch:' + baseTask,
    [
        'watch:' + baseTask + ':frontend',
        'watch:' + baseTask + ':backend',
        ///
        'watch:' + baseTask + ':scripts',
        'watch:' + baseTask + ':sass',
        'watch:' + baseTask + ':media'
    ],
    function () {
        gutil.log('Start watching...');
        return true;
    }
);

/// Watcher for Assets only
gulp.task('watch:' + baseTask + ':assets',
    [
        'watch:' + baseTask + ':scripts',
        'watch:' + baseTask + ':sass',
        'watch:' + baseTask + ':media'
    ],
    function () {
        gutil.log('Start watching assets...');
        return true;
    }
);


function reload() {
    setTimeout(browserSync.reload, 1000);
}

// Watch: frontend
gulp.task('watch:' + baseTask + ':frontend', function () {
    gulp.watch(
        [extPath + '/component/site/**/*'],
        function () {
            gulp.src(extPath + '/component/site/language/**')
                .pipe(gulp.dest(config.wwwDir + '/language'));

            return gulp.src([
                extPath + '/component/site/**',
                '!' + extPath + '/component/site/language',
                '!' + extPath + '/component/site/language/**'
            ])
                .pipe(gulp.dest(config.wwwDir + '/components/' + componentName));
        }
    )
});

// Watch: backend
gulp.task('watch:' + baseTask + ':backend',
    function () {
        gulp.watch([
                extPath + '/component/admin/**/*',
                extPath + '/redshop.xml',
                extPath + '/install.php'
            ],
            ['copy:' + baseTask + ':backend']
        );
    }
);

/// Watcher will watching for scss changes in Src/assets,
/// then minify its and copy to Media
gulp.task('watch:' + baseTask + ':sass',
    function () {
        gulp.watch([assetsPath + '/scss/*.scss'], ['sass:' + baseTask]);
    }
);

/// Watcher will watching for js changes in Src/assets,
/// then minify its and copy to Media
gulp.task('watch:' + baseTask + ':scripts', function () {
    gulp.watch([assetsPath + '/js/*.js', assetsPath + 'js/**/*.js'], ['scripts:' + baseTask]);
});

/// Watcher will watching for changes in Media,
/// then copy to destintaion Media
gulp.task('watch:' + baseTask + ':media', function () {
    gulp.watch([
            mediaPath + '/**/*.js',
            mediaPath + '/**/*.css',
            // Do not handle redCORE stuff
            '!' + mediaPath + '/translations/**/*'
        ],
        function () {
            return gulp.src(mediaPath + '/**')
                .pipe(gulp.dest(config.wwwDir + '/media/' + componentName));
        }
    );
});
