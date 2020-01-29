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
var gulp   = require('gulp');
var config = require('../../gulp-config.json');

/// Define dependencies
var del     = require('del');
var rename  = require('gulp-rename');
var concat  = require('gulp-concat');
var uglify  = require('gulp-uglify');
var path    = require("path");
var fs      = require('fs');
var changed = require('gulp-changed');


var jgulp = require('./../../node_modules/joomla-gulp/src/components.js');
var jgulpMedia = require('./../../node_modules/joomla-gulp/src/main.js');
/// Define component tasks
var componentName = 'com_redshop';

/// Define paths of source and destination
var extPath   = '.';
var mediaPath = extPath + '/media/' + componentName;

/**
 * Front-end part
 */
// Clean: frontend, will clean components from Sites
// clean: Front-end files
gulp.task('clean:components.redshop:frontend:files', function () {
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
// Copy: Front-end files
gulp.task('copy:components.redshop:frontend:files', gulp.series('clean:components.redshop:frontend:files'), function () {
    return gulp.src([
        extPath + '/component/site/**',
        '!' + extPath + '/component/site/language',
        '!' + extPath + '/component/site/language/**'
    ])
        .pipe(gulp.dest(config.wwwDir + '/components/' + componentName));
});
// Watch: Front-end files
gulp.task('watch:components.redshop:frontend:files', function (cb) {
    gulp.watch(
        [extPath + '/component/site/**/*', '!' + extPath + '/component/site/language/**'])
        .on("change", function (file) {
            var destinationPath = path.join(config.wwwDir, "components", componentName);
            var deployFile      = path.join(destinationPath,
                file.path.substring(file.path.indexOf("site") + 4, file.path.length));

            // Delete files
            del(deployFile, {force: true});

            // Copy files
            return gulp.src(file.path)
                .pipe(gulp.dest(path.dirname(deployFile)));
        })
        .on("end", cb);
});

// Clean: Front-end language
gulp.task('clean:components.redshop:frontend:lang', function () {
    return del(config.wwwDir + '/language/**/*.' + componentName + '.*', {force: true});
});

// Copy: Front-end language
gulp.task('copy:components.redshop:frontend:lang', gulp.series('clean:components.redshop:frontend:lang'), function () {
    return gulp.src(extPath + '/component/site/language/**')
        .pipe(gulp.dest(config.wwwDir + '/language'));
});

// Watch: Front-end language
gulp.task('watch:components.redshop:frontend:lang', function () {
    gulp.watch(extPath + '/component/site/language/**',
        gulp.series('copy:components.redshop:frontend:lang'));
});


// Clean: redSHOP.xml file
gulp.task('clean:components.redshop:backend:redshop.xml', function () {
    return del(config.wwwDir + '/administrator/components/' + componentName + '/redshop.xml', {force: true});
});

// Copy: redSHOP.xml file
gulp.task('copy:components.redshop:backend:redshop.xml', gulp.series('clean:components.redshop:backend:redshop.xml'), function () {
    return gulp.src(extPath + '/redshop.xml')
        .pipe(gulp.dest(config.wwwDir + '/administrator/components/' + componentName));
});
// Watch: redSHOP.xml file
gulp.task('watch:components.redshop:backend:redshop.xml', function () {
    gulp.watch(extPath + '/redshop.xml', gulp.series('copy:components.redshop:backend:redshop.xml'));
});

// Clean: install.php file
gulp.task('clean:components.redshop:backend:install.php', function () {
    return del(config.wwwDir + '/administrator/components/' + componentName + '/install.php', {force: true});
});

// Copy: install.php file
gulp.task('copy:components.redshop:backend:install.php', gulp.series('clean:components.redshop:backend:install.php'), function () {
    return gulp.src(extPath + '/install.php')
        .pipe(gulp.dest(config.wwwDir + '/administrator/components/' + componentName));
});
// Watch: install.php file
gulp.task('watch:components.redshop:backend:install.php', function () {
    gulp.watch(extPath + '/install.php', gulp.series('copy:components.redshop:backend:install.php'));
});


// Watch: Media JS
gulp.task('watch:components.redshop:media:js', function (cb) {
    gulp.watch([mediaPath + '/js/**/*.js', mediaPath + '/js/*.js'])
        .on("change", function (file) {
            var destinationPath = config.wwwDir + '/media/' + componentName;
            var deployFile      = path.join(destinationPath, file.path.substring(file.path.indexOf("com_redshop") + 11, file.path.length));

            // Delete files
            del(deployFile, {force: true});

            // Copy files
            return gulp.src(file.path)
                .pipe(gulp.dest(path.dirname(deployFile)));
        });
    cb();
});
gulp.task('watch:components.redshop:media:css', function (cb) {
    gulp.watch([mediaPath + '/css/**/*.css', mediaPath + '/css/*.css'])
        .on("change", function (file) {
            var destinationPath = config.wwwDir + '/media/' + componentName;
            var deployFile      = path.join(destinationPath,
                file.path.substring(file.path.indexOf("com_redshop") + 11, file.path.length));

            // Delete files
            del(deployFile, {force: true});

            // Copy files
            return gulp.src(file.path)
                .pipe(gulp.dest(path.dirname(deployFile)));
        });
    cb();
});

/// Clean: media, will clean files from Media
/// gulp clean:components.redshop:media
gulp.task('clean:components.redshop:media', function () {
    return del(
        [
            config.wwwDir + '/media/' + componentName + '/**',
            '!' + config.wwwDir + '/media/' + componentName,
            '!' + config.wwwDir + '/media/' + componentName + '/images',
            '!' + config.wwwDir + '/media/' + componentName + '/images/**',
            '!' + config.wwwDir + '/media/' + componentName + '/templates',
            '!' + config.wwwDir + '/media/' + componentName + '/templates/**'
        ],
        {force: true}
    );
});

// Copy: media
gulp.task('copy:components.redshop:media', gulp.series('clean:components.redshop:media'), function () {
    return gulp.src(mediaPath + '/**')
        .pipe(gulp.dest(config.wwwDir + '/media/' + componentName));
});

gulp.task('watch:components.redshop:media',
    gulp.series('watch:components.redshop:media:js', 'watch:components.redshop:media:css')
);


// Copy: copy redSHOP Backend files
gulp.task('copy:components.redshop:backend:files', function () {
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
gulp.task('watch:components.redshop:backend:files', function (cb) {
    gulp.watch(extPath + '/component/admin/**/*')
        .on("change", function (file) {
            var destinationPath = path.join(config.wwwDir, "administrator", "components", componentName);
            var deployFile      = path.join(destinationPath,
                file.path.substring(file.path.indexOf("admin") + 5, file.path.length));

            // Delete files
            del(deployFile, {force: true});

            // Copy files
            return gulp.src(file.path)
                .pipe(gulp.dest(path.dirname(deployFile)));
        })
        .on("end", cb);
});

// Clean: Admin language
gulp.task('clean:components.redshop:backend:lang', function () {
    return del(config.wwwDir + '/administrator/language/**/*.' + componentName + '.*', {force: true});
});
// Copy: Admin language
gulp.task('copy:components.redshop:backend:lang', gulp.series('clean:components.redshop:backend:lang'), function () {
    return gulp.src(extPath + '/component/admin/language/**')
        .pipe(gulp.dest(config.wwwDir + '/administrator/language'));
});
// Watch: Admin language
gulp.task('watch:components.redshop:backend:lang', function () {
    gulp.watch(extPath + '/component/admin/language/**', gulp.series('copy:components.redshop:backend:lang'));
});
// Admin: Clean backend, will clean components from Administrator
gulp.task('clean:components.redshop:backend', function () {
    return del([
        config.wwwDir + '/administrator/language/**/*.' + componentName + '.*',
        config.wwwDir + '/administrator/components/' + componentName + '/**',
        '!' + config.wwwDir + '/administrator/components/' + componentName,
        '!' + config.wwwDir + '/administrator/components/' + componentName + '/config',
        '!' + config.wwwDir + '/administrator/components/' + componentName + '/config/*.php'
    ], {force: true});
});

gulp.task('copy:components.redshop:backend',
    gulp.series(
        'copy:components.redshop:backend:redshop.xml',
        'copy:components.redshop:backend:install.php',
        'copy:components.redshop:backend:files',
        'copy:components.redshop:backend:lang'
    )
);
// Admin: Watch
gulp.task('watch:components.redshop:backend',
    gulp.series(
        'watch:components.redshop:backend:redshop.xml',
        'watch:components.redshop:backend:install.php',
        'watch:components.redshop:backend:files',
        'watch:components.redshop:backend:lang'
    )
);
// gulp clean:components.redshop:frontend
gulp.task('clean:components.redshop:frontend',
    gulp.series('clean:components.redshop:frontend:lang', 'clean:components.redshop:frontend:files')
);
// Copy: frontend
gulp.task('copy:components.redshop:frontend',
    gulp.series('copy:components.redshop:frontend:lang', 'copy:components.redshop:frontend:files')
);
// Watch: Front-end
gulp.task('watch:components.redshop:frontend',
    gulp.series('watch:components.redshop:frontend:lang', 'watch:components.redshop:frontend:files')
);

/// gulp clean component redshop
gulp.task('clean:' + baseTask,
    gulp.series(
        'clean:components.redshop:frontend',
        'clean:components.redshop:backend',
        'clean:components.redshop:media'
    ),
    function (cb) {
        cb();
    }
);
/// gulp copy:components.redshop
gulp.task('copy:' + baseTask,
    gulp.series(
        'copy:components.redshop:frontend',
        'copy:components.redshop:backend',
        'copy:components.redshop:media'
    ),
    function(cb){
        cb();
    });
/// Call another watcher
gulp.task('watch:' + baseTask,
    gulp.series(
        'watch:components.redshop:frontend',
        'watch:components.redshop:backend',
        'watch:components.redshop:asset:script',
        'watch:components.redshop:media'
    )
);


