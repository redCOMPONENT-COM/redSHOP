var gulp       = require("gulp");
var argv       = require("yargs").argv;
var requireDir = require("require-dir");
var zip        = require("gulp-zip");
var xml2js     = require("xml2js");
var fs         = require("fs");
var sass       = require("gulp-sass");
var path       = require("path");
var composer   = require('gulp-composer');
var gutil      = require('gulp-util');
var glob       = require('glob');

var config     = require("./gulp-config.json");
var extension  = require("./package.json");
var joomlaGulp = requireDir("./node_modules/joomla-gulp", {recurse: true});
var jgulp      = requireDir("./jgulp", {recurse: true});
var hashsum    = require("gulp-hashsum");
var clean      = require('gulp-clean');

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
    var plugins  = [];

    // No group specific, release all of them.
    if (!plgGroup) {
        var groups = getFolders(basePath);

        for (var i = 0; i < groups.length; i++) {
            plugins = getFolders(basePath + '/' + groups[i]);

            for (j = 0; j < plugins.length; j++) {
                pluginRelease(groups[i], plugins[j]);
            }
        }
    }
    else if (plgGroup && !plgName) {
        try {
            fs.statSync('./plugins/' + plgGroup);
        }
        catch (e) {
            console.error("Folder not exist: " + basePath + '/' + plgGroup);
            return;
        }

        plugins = getFolders(basePath + '/' + plgGroup);

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
    var modules   = [];

    // No group specific, release all of them.
    if (!modSource) {
        var groups = getFolders(basePath);

        for (var i = 0; i < groups.length; i++) {
            modules = getFolders(basePath + '/' + groups[i]);

            for (j = 0; j < modules.length; j++) {
                moduleRelease(groups[i], modules[j]);
            }
        }
    }
    else if (modSource && !modName) {
        try {
            fs.statSync('./modules/' + modSource);
        }
        catch (e) {
            console.error("Folder not exist: " + basePath + '/' + plgGroup);
            return;
        }

        modules = getFolders(basePath + '/' + modSource);

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

gulp.task("composer", function(){
    var composers = ['./libraries/redshop', './plugins/redshop_payment/quickbook/library', './plugins/redshop_payment/rs_payment_braintree/library'];

    for (var i = 0; i < composers.length; i++) {
        // gutil.log(gutil.colors.blue(composerPath));
        composer({cwd: composers[i], bin: 'php ./composer.phar'});
    }

    /* @TODO: Enable auto-get composer.json files instead of use composers array
    glob("**!/composer.json", [], function (er, files) {
        for (var i = 0; i < files.length; i++) {
            var composerPath = path.dirname(files[i]);

            // Make sure this is not composer.json inside vendor library
            if (composerPath.indexOf("vendor") == -1 && composerPath != '.') {
                gutil.log(gutil.colors.blue(composerPath));
                composer({cwd: composerPath, bin: 'php ./composer.phar'});
            }
        }
    });*/
});

gulp.task("release:md5:generate", function(){

    gutil.log(gutil.colors.yellow("Create checksum.md5 file in: checksum.md5"));

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
        "./plugins/finder/redshop/**",
        "./plugins/redshop_alert/alert/**",
        "./plugins/redshop_shipping/default_shipping/**",
        "./plugins/sh404sefextplugins/sh404sefextplugincom_redshop/**",
        "./plugins/redshop_pdf/tcpdf/**",
        "./plugins/redshop_export/attribute/**",
        "./plugins/redshop_export/category/**",
        "./plugins/redshop_export/field/**",
        "./plugins/redshop_export/manufacturer/**",
        "./plugins/redshop_export/product/**",
        "./plugins/redshop_export/product_stockroom_data/**",
        "./plugins/redshop_export/related_product/**",
        "./plugins/redshop_export/shipping_address/**",
        "./plugins/redshop_export/shopper_group_attribute_price/**",
        "./plugins/redshop_export/shopper_group_product_price/**",
        "./plugins/redshop_export/user/**",
        "./plugins/redshop_import/attribute/**",
        "./plugins/redshop_import/category/**",
        "./plugins/redshop_import/field/**",
        "./plugins/redshop_import/manufacturer/**",
        "./plugins/redshop_import/product/**",
        "./plugins/redshop_import/product_stockroom_data/**",
        "./plugins/redshop_import/shipping_address/**",
        "./plugins/redshop_import/shopper_group_product_price/**",
        "./plugins/redshop_import/shopper_group_attribute_price/**",
        "./plugins/redshop_import/user/**",
        "./plugins/redshop_import/related_product/**"
    ],{ base: "./" })
        .pipe(hashsum({dest: "./", filename: "checksum.md5", hash: "md5"}));
});

gulp.task("release:md5:json", ["release:md5:generate"], function(cb){
    var fileContent = fs.readFileSync(path.join("./checksum.md5"), "utf8");
    var temp = fileContent.split('\n');
    var result = [];
    var t1;

    for (var i = 0; i < temp.length; i++) {
        t1 = temp[i].split(' ');

        if (t1[0].trim().length)
        {
            var item = {'md5': t1[0], 'path': t1[2]};
            result.push(item);
        }
    }

    console.log("Create checksum.md5.json file in: component/admin/assets/checksum.md5.json");

    rs = JSON.stringify(result);

    fs.writeFile("./component/admin/assets/checksum.md5.json", rs);

    return cb();
});

gulp.task("release:md5",
    [
        "release:md5:generate",
        "release:md5:json",
        'release:md5:clean'
    ]
);

gulp.task('release:md5:clean', ["release:md5:json"], function () {
    return gulp.src('./checksum.md5')
        .pipe(clean({force: true}));
});

// Temporary remove release:md5 since it not ready for use yet.
// // gulp.task("release:redshop", ["composer:libraries", "release:md5"], function (cb) {
gulp.task("release:redshop", function (cb) {
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
                "./plugins/finder/redshop/**",
                "./plugins/redshop_alert/alert/**",
                "./plugins/redshop_shipping/default_shipping/**",
                "./plugins/sh404sefextplugins/sh404sefextplugincom_redshop/**",
                "./plugins/redshop_pdf/tcpdf/**",
                "./plugins/redshop_export/attribute/**",
                "./plugins/redshop_export/category/**",
                "./plugins/redshop_export/field/**",
                "./plugins/redshop_export/manufacturer/**",
                "./plugins/redshop_export/product/**",
                "./plugins/redshop_export/product_stockroom_data/**",
                "./plugins/redshop_export/related_product/**",
                "./plugins/redshop_export/shipping_address/**",
                "./plugins/redshop_export/shopper_group_attribute_price/**",
                "./plugins/redshop_export/shopper_group_product_price/**",
                "./plugins/redshop_export/user/**",
                "./plugins/redshop_import/attribute/**",
                "./plugins/redshop_import/category/**",
                "./plugins/redshop_import/field/**",
                "./plugins/redshop_import/manufacturer/**",
                "./plugins/redshop_import/product/**",
                "./plugins/redshop_import/product_stockroom_data/**",
                "./plugins/redshop_import/shipping_address/**",
                "./plugins/redshop_import/shopper_group_product_price/**",
                "./plugins/redshop_import/shopper_group_attribute_price/**",
                "./plugins/redshop_import/user/**",
                "./plugins/redshop_import/related_product/**"
            ],{ base: "./" })
            .pipe(zip(fileName))
            .pipe(gulp.dest(config.releaseDir))
            .on("end", cb);
        });
    });
});
