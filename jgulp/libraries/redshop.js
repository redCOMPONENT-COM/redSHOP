var gulp = require("gulp");

var config = require("../../gulp-config.json");

// Dependencies
var browserSync = require("browser-sync");
var concat      = require("gulp-concat");
var del         = require("del");
var fs          = require("fs");
var rename      = require("gulp-rename");
var xml2js      = require("xml2js");
var parser      = new xml2js.Parser({explicitArray: false});
var path        = require("path");
var composer    = require("gulp-composer");

var libraryName = "redshop";

var baseTask     = "libraries." + libraryName;
var extPath      = "./libraries/" + libraryName;
var manifestFile = libraryName + ".xml";
var wwwPath      = config.wwwDir + "/libraries/" + libraryName;
var libraryFiles = [];

// Clean
gulp.task("clean:" + baseTask, ["clean:" + baseTask + ":library", "clean:" + baseTask + ":manifest"], function () {
});

// Clean: library
gulp.task("clean:" + baseTask + ":library", function () {
    return del(wwwPath, {force: true});
});

// Clean: manifest
gulp.task("clean:" + baseTask + ":manifest", function () {
    return del(config.wwwDir + "/administrator/manifests/libraries/" + manifestFile, {force: true});
});

// Copy
gulp.task("copy:" + baseTask,
    [
        "copy:" + baseTask + ":library",
        "copy:" + baseTask + ":manifest"
    ],
    function () {
    }
);

// Copy: manifest
gulp.task("copy:" + baseTask + ":manifest", ["clean:" + baseTask + ":manifest"], function () {
    return gulp.src(extPath + "/" + manifestFile)
        .pipe(gulp.dest(config.wwwDir + "/administrator/manifests/libraries"));
});

gulp.task("copy:" + baseTask + ":vendor", function () {
    return gulp.src([
        extPath + "/vendor/**",
        "!" + extPath + "/vendor/**/docs",
        "!" + extPath + "/vendor/**/docs/**",
        "!" + extPath + "/vendor/**/sample",
        "!" + extPath + "/vendor/**/sample/**",
        "!" + extPath + "/vendor/**/tests",
        "!" + extPath + "/vendor/**/tests/**",
        "!" + extPath + "/vendor/**/Tests",
        "!" + extPath + "/vendor/**/Tests/**",
        "!" + extPath + "/vendor/**/doc",
        "!" + extPath + "/vendor/**/doc/**",
        "!" + extPath + "/vendor/**/docs",
        "!" + extPath + "/vendor/**/docs/**",
        "!" + extPath + "/vendor/**/composer.*",
        "!" + extPath + "/vendor/**/*.sh",
        "!" + extPath + "/vendor/**/build.xml",
        "!" + extPath + "/vendor/**/phpunit*",
        "!" + extPath + "/vendor/**/Vagrant*",
        "!" + extPath + "/vendor/**/.*.yml",
        "!" + extPath + "/vendor/**/.editorconfig"
    ], {base: extPath})
        .pipe(gulp.dest(wwwPath));
});

/**
 * Retrieve folders + files from the library manifest (except vendor folder) ready to be used by gulp.src
 *
 * @param   {Function}  callback  Callback to be executed when the file list is available
 *
 * @return  {mixed}
 */
function getLibraryFiles (callback) {
    // Already cached
    if (libraryFiles.length > 0) {
        return callback(libraryFiles);
    }

    fs.readFile(extPath + "/" + libraryName + ".xml", function (err, data) {
        parser.parseString(data, function (err, result) {
            var folders = result.extension.files.folder;
            var files   = result.extension.files.filename;

            for (var i = folders.length - 1; i >= 0; i--) {
                if (folders[i] !== "vendor") {
                    libraryFiles.push(extPath + "/" + folders[i] + "/**");
                }
            }

            for (var i = files.length - 1; i >= 0; i--) {
                libraryFiles.push(extPath + "/" + files[i]);
            }

            return callback(libraryFiles);
        });
    });
}

// Copy: library
gulp.task("copy:" + baseTask + ":library", function (cb) {
    getLibraryFiles(function (src) {
        return gulp.src(src, {base: extPath})
            .pipe(gulp.dest(wwwPath))
            .on("end", cb);
    });
});

// Watch
gulp.task("watch:" + baseTask,
    [
        "watch:" + baseTask + ":library",
        "watch:" + baseTask + ":manifest"
    ],
    function () {
    });

// Watch: library
gulp.task("watch:" + baseTask + ":library", function () {
    gulp.watch(
        [
            extPath,
            extPath + "/**",
            extPath + "/**!/*",
            "!" + extPath + "/vendor",
            "!" + extPath + "/vendor/!**!/!*",
            "!" + extPath + "/" + manifestFile
        ],
        function (event) {
            var folder     = "libraries/redshop";
            var deployFile = path.join(wwwPath, event.path.substring(event.path.indexOf("libraries") + folder.length, event.path.length));

            if (event.type == "changed") {
                // Copy files
                gulp.src(event.path)
                    .pipe(gulp.dest(path.dirname(deployFile)));
            }
            else if (event.type == "deleted") {
                // Delete files
                del(deployFile, {force: true});
            }

            browserSync.reload();
        }
    );
});

// Watch: manifest
gulp.task("watch:" + baseTask + ":manifest", function () {
    gulp.watch(extPath + "/" + manifestFile, ["copy:" + baseTask + ":manifest", browserSync.reload]);
});

// Composer
gulp.task("composer:" + baseTask, function () {
    executeComposer(extPath);
});
