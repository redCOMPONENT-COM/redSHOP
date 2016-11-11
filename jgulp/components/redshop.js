var gulp = require('gulp');
var config = require('../../gulp-config.json');

// Dependencies
var browserSync = require('browser-sync');
var del         = require('del');
var sass        = require('gulp-sass');
var rename      = require('gulp-rename');
var concat      = require('gulp-concat');
var uglify      = require('gulp-uglify');

var componentName = 'com_redshop';
var baseTask  = 'components.redshop';

var extPath      = '.';
var mediaPath    = extPath + '/media/com_redshop';
var assetsPath   = extPath + '/src/assets/com_redshop';
var wwwMediaPath = config.wwwDir + '/media/' + componentName;

// Minified and deploy from Assets to Media.
gulp.task('scripts:' + baseTask, function() {
    return gulp.src([
        assetsPath + '/js/*.js',
        assetsPath + '/js/**/*.js'
    ])
        .pipe(rename(function (path) {
            path.basename += '-uncompressed';
        }))
        .pipe(gulp.dest(mediaPath + '/js'))
        // .pipe(uglify())
        .pipe(rename(function (path) {
            path.basename = path.basename.replace('-uncompressed', '');
        }))
        .pipe(gulp.dest(mediaPath + '/js'));
});

// Sass Compiler
gulp.task('sass:' + baseTask, function(){
    return gulp.src([
        assetsPath + "/scss/bootstrap-grid.scss",
        assetsPath + "/scss/style.scss",
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

/**
 * Main clean task
 */
gulp.task('clean:' + baseTask,
    [
        'clean:' + baseTask + ':frontend',
        'clean:' + baseTask + ':backend',
        'clean:' + baseTask + ':media'
    ],
    function() {
        return true;
    }
);

// Clean: frontend
gulp.task('clean:' + baseTask + ':frontend', function(cb) {
    del(config.wwwDir + '/language/**/*.com_redshop.*', {force: true});

    return del(config.wwwDir + '/components/com_redshop', {force : true});
});

// Clean: backend
gulp.task('clean:' + baseTask + ':backend', function(cb) {
    del(config.wwwDir + '/administrator/language/**/*.com_redshop.*', {force: true});

    return del([
        config.wwwDir + '/administrator/components/com_redshop/**',
        '!' + config.wwwDir + '/administrator/components/com_redshop',
        '!' + config.wwwDir + '/administrator/components/com_redshop/config',
        '!' + config.wwwDir + '/administrator/components/com_redshop/config/*.php'

        ], {force : true});
});

// Clean: media
gulp.task('clean:' + baseTask + ':media', function(cb) {
    return del(
        [
            config.wwwDir + '/media/com_redshop/**',
            '!' + config.wwwDir + '/media/com_redshop',
            '!' + config.wwwDir + '/media/com_redshop/images',
            '!' + config.wwwDir + '/media/com_redshop/images/**'
        ],
        {force : true}
    );
});

// Copy
gulp.task('copy:' + baseTask,
    [
        'clean:' + baseTask,
        'copy:' + baseTask + ':frontend',
        'copy:' + baseTask + ':backend',
        'copy:' + baseTask + ':media',
    ],
    function() {
    });

// Copy: frontend
gulp.task('copy:' + baseTask + ':frontend', ['clean:' + baseTask + ':frontend'], function() {
    gulp.src(extPath + '/component/site/language/**')
        .pipe(gulp.dest(config.wwwDir + '/language'));

    return gulp.src([
            extPath + '/component/site/**',
            '!' + extPath + '/component/site/language',
            '!' + extPath + '/component/site/language/**'
        ])
        .pipe(gulp.dest(config.wwwDir + '/components/com_redshop'));
});

// Copy: backend
gulp.task('copy:' + baseTask + ':backend', ['clean:' + baseTask + ':backend'], function(cb) {
    gulp.src(extPath + '/component/admin/language/**')
        .pipe(gulp.dest(config.wwwDir + '/administrator/language'));

    return (
        gulp.src([
            extPath + '/component/admin/**',
            '!' + extPath + '/component/admin/language',
            '!' + extPath + '/component/admin/language/**'
        ])
            .pipe(gulp.dest(config.wwwDir + '/administrator/components/com_redshop')) &&
        gulp.src(extPath + '/redshop.xml')
            .pipe(gulp.dest(config.wwwDir + '/administrator/components/com_redshop')) &&
        gulp.src(extPath + '/install.php')
            .pipe(gulp.dest(config.wwwDir + '/administrator/components/com_redshop'))
    );
});

// Copy: media
gulp.task('copy:' + baseTask + ':media', ['clean:' + baseTask + ':media'], function() {
    return gulp.src(mediaPath + '/**')
        .pipe(gulp.dest(config.wwwDir + '/media/com_redshop'));
});

// Watch
gulp.task('watch:' + baseTask,
    [
        'watch:' + baseTask + ':frontend',
        'watch:' + baseTask + ':backend',
        'watch:' + baseTask + ':scripts',
        'watch:' + baseTask + ':sass',
        'watch:' + baseTask + ':media'
    ],
    function() {
        return true;
    }
);

function reload()
{
    setTimeout(browserSync.reload, 1000);
}

// Watch: frontend
gulp.task('watch:' + baseTask + ':frontend', function() {
    gulp.watch([extPath + '/component/site/**/*'],
        ['copy:' + baseTask + ':frontend', reload]);
});

// Watch: backend
gulp.task('watch:' + baseTask + ':backend', function() {
    gulp.watch([
            extPath + '/component/admin/**/*',
            extPath + '/redshop.xml',
            extPath + '/install.php'
        ],
        ['copy:' + baseTask + ':backend', reload]);
});

// Watch: SASS
gulp.task('watch:' + baseTask + ':sass',
    function() {
        gulp.watch([
            assetsPath + '/scss/*.scss'
            ],
            ['sass:' + baseTask,
                //browserSync.reload
            ]
        );
    });

// Watch: Scripts
gulp.task('watch:' + baseTask + ':scripts', function() {
    gulp.watch([
        assetsPath + '/js/*.js'
    ], ['scripts:' + baseTask,
        //browserSync.reload
    ]);
});

// Watch: media
gulp.task('watch:' + baseTask + ':media', function() {
    gulp.watch([
            mediaPath + '/**/*.js',
            mediaPath + '/**/*.css',
            // Do not handle redCORE stuff
            '!' + mediaPath + '/translations/**/*'
        ],
        [
        'copy:' + baseTask + ':media'
        //, reload
        ]
    );
});
