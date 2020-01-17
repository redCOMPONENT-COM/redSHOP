var gulp = require("gulp");

// Load config
var config = require("../../../gulp-config.json");

// Dependencies
var browserSync = require("browser-sync");
var del         = require("del");
var composer    = require("gulp-composer");

var group = "redshop_pdf";
var name  = "tcpdf";

var baseTask = "plugins." + group + "." + name;
var extPath  = "./plugins/" + group + "/" + name;

var wwwExtPath = config.wwwDir + "/plugins/" + group + "/" + name;

// Clean
gulp.task("clean:" + baseTask,
    gulp.series(
        "clean:" + baseTask + ":plugin"
    ),
    function () {
    });

// Clean: plugin
gulp.task("clean:" + baseTask + ":plugin", function () {
    return del(wwwExtPath, {force: true});
});

// Copy
gulp.task("copy:" + baseTask,
    gulp.series(
        "copy:" + baseTask + ":plugin"
    ,
    function () {
    }));

// Copy: plugin
gulp.task("copy:" + baseTask + ":plugin", gulp.series("clean:" + baseTask + ":plugin"), function () {
    return gulp.src([
        extPath + "/**"
    ])
        .pipe(gulp.dest(wwwExtPath));
});

// Watch
gulp.task("watch:" + baseTask,
    gulp.series(
        "watch:" + baseTask + ":plugin"
    ),
    function () {
    });

// Watch: plugin
gulp.task("watch:" + baseTask + ":plugin", function () {
    gulp.watch([
            extPath + "/**/*"
        ],
        ["copy:" + baseTask, browserSync.reload]
    );
});

// Composer
gulp.task("composer:" + baseTask, function () {
    composer({cwd: extPath + "/helper", bin: "php ./composer.phar"});
});
