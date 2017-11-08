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
var del = require('del');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var path = require("path");
var fs = require('fs');
var changed = require('gulp-changed');

/// Define component tasks
var componentName = 'com_redshop';
var baseTask = 'components.redshop';

/// Define paths of source and destination
var extPath = '.';
var mediaPath = extPath + '/media/' + componentName;

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

/// Clean: media, will clean files from Media
/// gulp clean:components.redshop:media
gulp.task('clean:' + baseTask + ':media', function () {
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
        'copy:' + baseTask + ':frontend',
        'copy:' + baseTask + ':backend',
        'copy:' + baseTask + ':media'
    ],
    function () {
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
        'watch:' + baseTask + ':asset:script',
        'watch:' + baseTask + ':media'
    ]
);

/**
 * Media Part
 */
/// Watcher will watching for changes in Media,
/// then copy to destination Media
gulp.task('watch:' + baseTask + ':media',
    ['watch:' + baseTask + ':media:js', 'watch:' + baseTask + ':media:css']
);
// Watch: Media JS
gulp.task('watch:' + baseTask + ':media:js', function () {
    gulp.watch([mediaPath + '/js/**/*.js', mediaPath + '/js/*.js'])
        .on("change", function (file) {
            var destinationPath = config.wwwDir + '/media/' + componentName;
            var deployFile = path.join(destinationPath, file.path.substring(file.path.indexOf("com_redshop") + 11, file.path.length));

            // Delete files
            del(deployFile, {force: true});

            // Copy files
            return gulp.src(file.path)
                .pipe(gulp.dest(path.dirname(deployFile)));
        });
});
gulp.task('watch:' + baseTask + ':media:css', function () {
    gulp.watch([mediaPath + '/css/**/*.css', mediaPath + '/css/*.css'])
        .on("change", function (file) {
            var destinationPath = config.wwwDir + '/media/' + componentName;
            var deployFile = path.join(destinationPath, file.path.substring(file.path.indexOf("com_redshop") + 11, file.path.length));

            // Delete files
            del(deployFile, {force: true});

            // Copy files
            return gulp.src(file.path)
                .pipe(gulp.dest(path.dirname(deployFile)));
        });
});


/**
 * Front-end part
 */
// Clean: frontend, will clean components from Sites
// gulp clean:components.redshop:frontend
gulp.task('clean:' + baseTask + ':frontend',
    ['clean:' + baseTask + ':frontend:lang', 'clean:' + baseTask + ':frontend:files']
);
// Copy: frontend
gulp.task('copy:' + baseTask + ':frontend',
    ['copy:' + baseTask + ':frontend:lang', 'copy:' + baseTask + ':frontend:files']
);
// Watch: Front-end
gulp.task('watch:' + baseTask + ':frontend',
    ['watch:' + baseTask + ':frontend:lang', 'watch:' + baseTask + ':frontend:files']
);

// Copy: Front-end language
gulp.task('copy:' + baseTask + ':frontend:lang', ['clean:' + baseTask + ':frontend:lang'], function () {
    return gulp.src(extPath + '/component/site/language/**')
        .pipe(gulp.dest(config.wwwDir + '/language'));
});
// Clean: Front-end language
gulp.task('clean:' + baseTask + ':frontend:lang', function () {
    return del(config.wwwDir + '/language/**/*.' + componentName + '.*', {force: true});
});
// Watch: Front-end language
gulp.task('watch:' + baseTask + ':frontend:lang', function () {
    gulp.watch(extPath + '/component/site/language/**', ['copy:' + baseTask + ':frontend:lang']);
});

// Copy: Front-end files
gulp.task('copy:' + baseTask + ':frontend:files', ['clean:' + baseTask + ':frontend:files'], function () {
    return gulp.src([
        extPath + '/component/site/**',
        '!' + extPath + '/component/site/language',
        '!' + extPath + '/component/site/language/**'
    ])
        .pipe(gulp.dest(config.wwwDir + '/components/' + componentName));
});
// clean: Front-end files
gulp.task('clean:' + baseTask + ':frontend:files', function () {
    return del([
        config.wwwDir + '/components/com_redshop/controllers',
        config.wwwDir + '/components/com_redshop/helpers',
        config.wwwDir + '/components/com_redshop/language',
        config.wwwDir + '/components/com_redshop/layouts',
        config.wwwDir + '/components/com_redshop/models',
        config.wwwDir + '/components/com_redshop/templates',
        config.wwwDir + '/components/com_redshop/views/**/*.html.php',
        config.wwwDir + '/components/com_redshop/views/**/tmpl/*.php',
        config.wwwDir + '/components/com_redshop/*.php'
    ], {force: true});
});
// Watch: Front-end files
gulp.task('watch:' + baseTask + ':frontend:files', function (cb) {
    gulp.watch(
        [extPath + '/component/site/**/*', '!' + extPath + '/component/site/language/**'])
        .on("change", function (file) {
            var destinationPath = path.join(config.wwwDir, "components", componentName);
            var deployFile = path.join(destinationPath, file.path.substring(file.path.indexOf("site") + 4, file.path.length));

            // Delete files
            del(deployFile, {force: true});

            // Copy files
            return gulp.src(file.path)
                .pipe(gulp.dest(path.dirname(deployFile)));
        })
        .on("end", cb);
});


/**
 * Admin part
 */
// Admin: COPY
gulp.task('copy:' + baseTask + ':backend',
    [
        'copy:' + baseTask + ':backend:redshop.xml',
        'copy:' + baseTask + ':backend:install.php',
        'copy:' + baseTask + ':backend:files',
        'copy:' + baseTask + ':backend:lang'
    ]
);
// Admin: Clean backend, will clean components from Administrator
gulp.task('clean:' + baseTask + ':backend', function () {
    return del([
        config.wwwDir + '/administrator/language/**/*.' + componentName + '.*',
        config.wwwDir + '/administrator/components/' + componentName + '/**',
        '!' + config.wwwDir + '/administrator/components/' + componentName,
        '!' + config.wwwDir + '/administrator/components/' + componentName + '/config',
        '!' + config.wwwDir + '/administrator/components/' + componentName + '/config/*.php'
    ], {force: true});
});
// Admin: Watch
gulp.task('watch:' + baseTask + ':backend',
    [
        'watch:' + baseTask + ':backend:redshop.xml',
        'watch:' + baseTask + ':backend:install.php',
        'watch:' + baseTask + ':backend:files',
        'watch:' + baseTask + ':backend:lang'
    ]
);

// Copy: Admin language
gulp.task('copy:' + baseTask + ':backend:lang', ['clean:' + baseTask + ':backend:lang'], function () {
    return gulp.src(extPath + '/component/admin/language/**')
        .pipe(gulp.dest(config.wwwDir + '/administrator/language'));
});
// Clean: Admin language
gulp.task('clean:' + baseTask + ':backend:lang', function () {
    return del(config.wwwDir + '/administrator/language/**/*.' + componentName + '.*', {force: true});
});
// Watch: Admin language
gulp.task('watch:' + baseTask + ':backend:lang', function () {
    gulp.watch(extPath + '/component/admin/language/**', ['copy:' + baseTask + ':backend:lang']);
});

// Copy: redSHOP.xml file
gulp.task('copy:' + baseTask + ':backend:redshop.xml', ['clean:' + baseTask + ':backend:redshop.xml'], function () {
    return gulp.src(extPath + '/redshop.xml')
        .pipe(gulp.dest(config.wwwDir + '/administrator/components/' + componentName));
});
// Watch: redSHOP.xml file
gulp.task('watch:' + baseTask + ':backend:redshop.xml', function () {
    gulp.watch(extPath + '/redshop.xml', ['copy:' + baseTask + ':backend:redshop.xml']);
});
// Clean: redSHOP.xml file
gulp.task('clean:' + baseTask + ':backend:redshop.xml', function () {
    return del(config.wwwDir + '/administrator/components/' + componentName + '/redshop.xml', {force: true});
});


// Copy: install.php file
gulp.task('copy:' + baseTask + ':backend:install.php', ['clean:' + baseTask + ':backend:install.php'], function () {
    return gulp.src(extPath + '/install.php')
        .pipe(gulp.dest(config.wwwDir + '/administrator/components/' + componentName));
});
// Watch: install.php file
gulp.task('watch:' + baseTask + ':backend:install.php', function () {
    gulp.watch(extPath + '/install.php', ['copy:' + baseTask + ':backend:install.php']);
});
// Clean: install.php file
gulp.task('clean:' + baseTask + ':backend:install.php', function () {
    return del(config.wwwDir + '/administrator/components/' + componentName + '/install.php', {force: true});
});


// Copy: copy redSHOP Backend files
gulp.task('copy:' + baseTask + ':backend:files', function () {
    return gulp.src(
        [
            extPath + '/component/admin/**/*',
            '!' + extPath + '/component/admin/language',
            '!' + extPath + '/component/admin/language/**'
        ]
    )
        .pipe(gulp.dest(config.wwwDir + '/administrator/components/' + componentName));
});
// Watch: redSHOP Backend files
gulp.task('watch:' + baseTask + ':backend:files', function (cb) {
    gulp.watch(extPath + '/component/admin/**/*')
        .on("change", function (file) {
            var destinationPath = path.join(config.wwwDir, "administrator", "components", componentName);
            var deployFile = path.join(destinationPath, file.path.substring(file.path.indexOf("admin") + 5, file.path.length));

            // Delete files
            del(deployFile, {force: true});

            // Copy files
            return gulp.src(file.path)
                .pipe(gulp.dest(path.dirname(deployFile)));
        })
        .on("end", cb);
});
