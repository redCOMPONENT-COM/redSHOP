var gulp       = require("gulp");
var argv       = require("yargs").argv;
var requireDir = require("require-dir");
var zip        = require("gulp-zip");
var xml2js     = require("xml2js");
var fs         = require("fs");
var sass       = require("gulp-sass");
var path       = require("path");

var config     = require("./gulp-config.json");
var extension  = require("./package.json");
var joomlaGulp = requireDir("./node_modules/joomla-gulp", {recurse: true});
var jgulp      = requireDir("./jgulp", {recurse: true});

var parser     = new xml2js.Parser();

/**
 * Function for read list folder
 *
 * @param  string dir Path of folder
 *
 * @return array      Subfolder list.
 */
function getFolders(dir){
    return fs.readdirSync(dir)
        .filter(function(file){
                return fs.statSync(path.join(dir, file)).isDirectory();
            }
        );
}

/**
 * Function for release plugin
 * @param group
 * @param name
 * @returns {*}
 */
function pluginRelease(group, name) {
    var fileName = 'plg_' + group + '_' + name;

    if (!argv.skipVersion) {
        fs.readFile('./plugins/' + group + '/' + name + '/' + name + '.xml', function(err, data) {
            parser.parseString(data, function (err, result) {
                fileName += '-v' + result.extension.version[0] + '.zip';

                // We will output where release package is going so it is easier to find
                console.log('Plugin release file in: ' + path.join(config.releaseDir + '/plugins', fileName));

                return gulp.src('./plugins/' + group + '/' + name + '/**')
                    .pipe(zip(fileName))
                    .pipe(gulp.dest(config.releaseDir + '/plugins'));
            });
        });
    }
    else {
        return gulp.src('./plugins/' + group + '/' + name + '/**')
            .pipe(zip(fileName + '.zip'))
            .pipe(gulp.dest(config.releaseDir + '/plugins'));
    }
}

/**
 * Function for release module
 * @param group
 * @param name
 * @returns {*}
 */
function moduleRelease(group, name) {
    var fileName = name;

    if (!argv.skipVersion) {
        fs.readFile('./modules/' + group + '/' + name + '/' + name + '.xml', function(err, data) {
            parser.parseString(data, function (err, result) {
                fileName += '-v' + result.extension.version[0] + '.zip';

                // We will output where release package is going so it is easier to find
                console.log('Module release file in: ' + path.join(config.releaseDir + '/modules/' + group, fileName));

                return gulp.src('./modules/' + group + '/' + name + '/**')
                    .pipe(zip(fileName))
                    .pipe(gulp.dest(config.releaseDir + '/modules/' + group));
            });
        });
    }
    else {
        return gulp.src('./plugins/' + group + '/' + name + '/**')
            .pipe(zip(fileName + '.zip'))
            .pipe(gulp.dest(config.releaseDir + '/plugins'));
    }
}

// Clean test site
/*gulp.task(
    'clean',
    [
        'clean:components',
        'clean:libraries',
        'clean:modules',
        'clean:packages',
        'clean:plugins'
    ], function() {
        return true;
    });*/

// Copy to test site
gulp.task(
    'copy',
    [
        'copy:components',
        'copy:libraries',
        'copy:modules',
        'copy:packages',
        'copy:plugins'
    ], function() {
        return true;
    });

// Watch for file changes
gulp.task(
    'watch',
    [
        'watch:components',
        'watch:libraries',
        'watch:modules',
        'watch:packages',
        'watch:plugins'
    ], function() {
        return true;
    });

// Release: Plugins
gulp.task('release:plugin', function(cb) {
    var basePath = './plugins';
    var plgGroup = argv.group ? argv.group : false;
    var plgName  = argv.name ? argv.name : false;

    // No group specific, release all of them.
    if (!plgGroup) {
        var groups = getFolders(basePath);

        for (var i = 0; i < groups.length; i++) {
            var plugins = getFolders(basePath + '/' + groups[i]);

            for (j = 0; j < plugins.length; j++) {
                pluginRelease(groups[i], plugins[j]);
            }
        };
    }
    else if (plgGroup && !plgName) {
        try {
            fs.statSync('./plugins/' + plgGroup);
        }
        catch (e) {
            console.error("Folder not exist: " + basePath + '/' + plgGroup);
            return;
        }

        var plugins = getFolders(basePath + '/' + plgGroup);

        for (i = 0; i < plugins.length; i++) {
            pluginRelease(plgGroup, plugins[i]);
        }
    }
    else
    {
        try {
            fs.statSync('../extensions/plugins/' + plgGroup + '/' + plgName);
        }
        catch (e) {
            console.error("Folder not exist: " + basePath + '/' + plgGroup + '/' + plgName);
            return;
        }

        pluginRelease(plgGroup, plgName);
    }
});

// Release: Modules
gulp.task('release:module', function(cb) {
    var basePath  = './modules';
    var modSource = argv.group ? argv.group : false;
    var modName   = argv.name ? argv.name : false;

    // No group specific, release all of them.
    if (!modSource) {
        var groups = getFolders(basePath);

        for (var i = 0; i < groups.length; i++) {
            var modules = getFolders(basePath + '/' + groups[i]);

            for (j = 0; j < modules.length; j++) {
                moduleRelease(groups[i], modules[j]);
            }
        };
    }
    else if (modSource && !modName) {
        try {
            fs.statSync('./modules/' + plgGroup);
        }
        catch (e) {
            console.error("Folder not exist: " + basePath + '/' + plgGroup);
            return;
        }

        var modules = getFolders(basePath + '/' + modSource);

        for (i = 0; i < modules.length; i++) {
            moduleRelease(modSource, modules[i]);
        }
    }
    else
    {
        try {
            fs.statSync('./modules/' + modSource + '/' + modName);
        }
        catch (e) {
            console.error("Folder not exist: " + basePath + '/' + modSource + '/' + modName);
            return;
        }

        moduleRelease(modSource, modName);
    }
});

// Overwrite "release" method
gulp.task("release",
    [
        "release:plugin",
        "release:module",
        "release:redshop"
    ]
);

gulp.task("release:redshop", ["composer:libraries.redshop"], function (cb) {
    fs.readFile( "./redshop.xml", function(err, data) {
        parser.parseString(data, function (err, result) {
            var version  = result.extension.version[0];
            var fileName = argv.skipVersion ? "redshop.zip" : "redshop-v" + version + ".zip";

            console.log('Create redSHOP release file in: ' + path.join(config.releaseDir + '/', fileName));

            return gulp.src([
                "./component/**/*",
                "./component/**/.gitkeep",
                "./libraries/redshop/**/*",
                "./libraries/redshop/vendor/**/*",
                "./libraries/redshop/.gitkeep",
                "!./libraries/redshop/composer.*",
                "!./libraries/redshop/vendor/**/tests/**/*",
                "!./libraries/redshop/vendor/**/tests",
                "!./libraries/redshop/vendor/**/Tests/**/*",
                "!./libraries/redshop/vendor/**/Tests",
                "!./libraries/redshop/vendor/**/docs/**/*",
                "!./libraries/redshop/vendor/**/docs",
                "!./libraries/redshop/vendor/**/doc/**/*",
                "!./libraries/redshop/vendor/**/doc",
                "!./libraries/redshop/vendor/**/composer.*",
                "!./libraries/redshop/vendor/**/phpunit*",
                "!./libraries/redshop/vendor/**/Vagrantfile",
                "./media/**/*",
                "./media/**/.gitkeep",
                "!./media/com_redshop/scss",
                "!./media/com_redshop/scss/**",
                "./*(install.php|LICENSE.txt|redshop.xml)",
                "./modules/site/mod_redshop_cart/**",
                "./plugins/system/redshop/**",
                "./plugins/redshop_payment/rs_payment_banktransfer/**",
                "./plugins/redshop_payment/rs_payment_paypal/**",
                "./plugins/redshop_payment/klarna/**",
                "./plugins/finder/redshop/**",
                "./plugins/redshop_alert/alert/**",
                "./plugins/redshop_shipping/default_shipping/**",
                "./plugins/sh404sefextplugins/sh404sefextplugincom_redshop/**",
                "./plugins/redshop_pdf/tcpdf/**"
            ],{ base: "./" })
                .pipe(zip(fileName))
                .pipe(gulp.dest(config.releaseDir))
                .on("end", cb);
        });
    });
});
