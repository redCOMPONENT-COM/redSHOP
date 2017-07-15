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

var extension  = require("./package.json");
var joomlaGulp = requireDir("./node_modules/joomla-gulp", {recurse: true});
var jgulp      = requireDir("./jgulp", {recurse: true});
var hashsum    = require("gulp-hashsum");
var clean      = require('gulp-clean');

global.config = require("./gulp-config.json");
/**
 * Function for read list folder
 *
 * @param  string dir Path of folder
 *
 * @return array      Subfolder list.
 */
global.getFolders = function getFolders(dir){
    return fs.readdirSync(dir)
        .filter(function(file){
                return fs.statSync(path.join(dir, file)).isDirectory();
            }
        );
}

global.renderLog = function renderLog(extension, group, extName, version, releasePath){
    // We will output where release package is going so it is easier to find
    gutil.log(
        gutil.colors.green(extension),
        "  |  ",
        gutil.colors.white(group),
        "  |  ",
        gutil.colors.blue(extName),
        "  |  ",
        gutil.colors.yellow(version),
        "|  ",
        gutil.colors.grey(releasePath)
    );
}

global.getGlobPattern = function getGlobPattern(extensionType, group, extName)
{
    return [
        './' + extensionType + '/' + group + '/' + extName + '/**',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/composer.json',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/composer.lock',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/*.md',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/*.txt',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/*.TXT',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/*.pdf',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/LICENSE',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/CHANGES',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/README',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/VERSION',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/composer.json',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/.gitignore',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/docs',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/docs/**',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/tests',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/tests/**',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/unitTests',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/unitTests/**',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/.git',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/.git/**',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/examples',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/examples/**',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/build.xml',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/phpunit.xml',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/phpunit.xml.dist',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/**/phpcs.xml',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/mpdf/mpdf/ttfonts/!(DejaVu*.ttf)',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/setasign/fpdi',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/setasign/fpdi/**',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/tecnickcom/tcpdf/fonts/!(courier*.php|helvetica*.php|symbol*.php|times*.php|uni2cid_a*.php|zapfdingbats*.php)',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/tecnickcom/tcpdf/fonts/ae_fonts*/**',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/tecnickcom/tcpdf/fonts/dejavu-fonts-ttf*/**',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/tecnickcom/tcpdf/fonts/freefont-*/**'
    ]
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


// Overwrite "release" method
gulp.task("release",
    [
        "release:plugin",
        "release:module",
        "release:redshop"
    ]
);

gulp.task("composer", function(){
    glob("**/composer.json", [], function  (er, files) {
        for (var i = 0; i < files.length; i++) {
            var composerPath = path.dirname(files[i]);

            // Make sure this is not composer.json inside vendor library
            if (composerPath.indexOf("vendor") == -1 && composerPath != '.') {
                gutil.log("Composer found: ", gutil.colors.blue(composerPath));
                composer({cwd: composerPath, bin: 'php ./composer.phar'});
            }
        }
    });
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
        "./' + extensionType + '/system/redshop/**",
        "./' + extensionType + '/redshop_payment/rs_payment_banktransfer/**",
        "./' + extensionType + '/redshop_payment/rs_payment_paypal/**",
        "./' + extensionType + '/finder/redshop/**",
        "./' + extensionType + '/redshop_alert/alert/**",
        "./' + extensionType + '/redshop_shipping/default_shipping/**",
        "./' + extensionType + '/sh404sefextplugins/sh404sefextplugincom_redshop/**",
        "./' + extensionType + '/redshop_pdf/tcpdf/**",
        "./' + extensionType + '/redshop_export/attribute/**",
        "./' + extensionType + '/redshop_export/category/**",
        "./' + extensionType + '/redshop_export/field/**",
        "./' + extensionType + '/redshop_export/manufacturer/**",
        "./' + extensionType + '/redshop_export/product/**",
        "./' + extensionType + '/redshop_export/product_stockroom_data/**",
        "./' + extensionType + '/redshop_export/related_product/**",
        "./' + extensionType + '/redshop_export/shipping_address/**",
        "./' + extensionType + '/redshop_export/shopper_group_attribute_price/**",
        "./' + extensionType + '/redshop_export/shopper_group_product_price/**",
        "./' + extensionType + '/redshop_export/user/**",
        "./' + extensionType + '/redshop_import/attribute/**",
        "./' + extensionType + '/redshop_import/category/**",
        "./' + extensionType + '/redshop_import/field/**",
        "./' + extensionType + '/redshop_import/manufacturer/**",
        "./' + extensionType + '/redshop_import/product/**",
        "./' + extensionType + '/redshop_import/product_stockroom_data/**",
        "./' + extensionType + '/redshop_import/shipping_address/**",
        "./' + extensionType + '/redshop_import/shopper_group_product_price/**",
        "./' + extensionType + '/redshop_import/shopper_group_attribute_price/**",
        "./' + extensionType + '/redshop_import/user/**",
        "./' + extensionType + '/redshop_import/related_product/**"
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

    gutil.log(gutil.colors.yellow("checksum.md5.json file: "), "component/admin/assets/checksum.md5.json");

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
gulp.task("release:redshop", ["composer:libraries.redshop", "composer:plugins.redshop_pdf.tcpdf"], function (cb) {
    fs.readFile( "./redshop.xml", function(err, data) {
        parser.parseString(data, function (err, result) {
            var version  = result.extension.version[0];
            var fileName = argv.skipVersion ? "redshop.zip" : "redshop-v" + version + ".zip";

            gutil.log(gutil.colors.grey("===================================================================="));
            gutil.log(gutil.colors.cyan.bold("redSHOP"), "  |  ", gutil.colors.yellow.bold(version), "  |  ", gutil.colors.white.bold(path.join(config.releaseDir + '/', fileName)));
            gutil.log(gutil.colors.grey("===================================================================="));

            return gulp.src([
                "./component/**/*",
                "./component/**/.gitkeep",
                "./libraries/redshop/**/*",
                "./libraries/redshop/vendor/**/*",
                "./libraries/redshop/.gitkeep",
                '!./**/composer.json',
                '!./**/composer.lock',
                '!./**/vendor/**/*.md',
                '!./**/vendor/**/*.txt',
                '!./**/vendor/**/*.TXT',
                '!./**/vendor/**/*.pdf',
                '!./**/vendor/**/LICENSE',
                '!./**/vendor/**/CHANGES',
                '!./**/vendor/**/README',
                '!./**/vendor/**/VERSION',
                '!./**/vendor/**/composer.json',
                '!./**/vendor/**/.gitignore',
                '!./**/vendor/**/docs',
                '!./**/vendor/**/docs/**',
                '!./**/vendor/**/tests',
                '!./**/vendor/**/tests/**',
                '!./**/vendor/**/unitTests',
                '!./**/vendor/**/unitTests/**',
                '!./**/vendor/**/.git',
                '!./**/vendor/**/.git/**',
                '!./**/vendor/**/examples',
                '!./**/vendor/**/examples/**',
                '!./**/vendor/**/build.xml',
                '!./**/vendor/**/phpunit.xml',
                '!./**/vendor/**/phpunit.xml.dist',
                '!./**/vendor/**/phpcs.xml',
                "!./**/vendor/**/Vagrantfile",
                "./media/**/*",
                "./media/**/.gitkeep",
                "!./media/com_redshop/scss",
                "!./media/com_redshop/scss/**",
                "./*(install.php|LICENSE.txt|redshop.xml)",
                "./modules/site/mod_redshop_cart/**",
                "./' + extensionType + '/system/redshop/**",
                "./' + extensionType + '/redshop_payment/rs_payment_banktransfer/**",
                "./' + extensionType + '/redshop_payment/rs_payment_paypal/**",
                "./' + extensionType + '/finder/redshop/**",
                "./' + extensionType + '/redshop_alert/alert/**",
                "./' + extensionType + '/redshop_shipping/default_shipping/**",
                "./' + extensionType + '/sh404sefextplugins/sh404sefextplugincom_redshop/**",
                "./' + extensionType + '/redshop_pdf/tcpdf/**",
                '!./' + extensionType + '/redshop_pdf/tcpdf/helper/vendor/tecnickcom/tcpdf/fonts/!(courier*.php|helvetica*.php|symbol*.php|times*.php|uni2cid_a*.php|zapfdingbats*.php)',
                '!./' + extensionType + '/redshop_pdf/tcpdf/helper/vendor/tecnickcom/tcpdf/fonts/ae_fonts*/**',
                '!./' + extensionType + '/redshop_pdf/tcpdf/helper/vendor/tecnickcom/tcpdf/fonts/dejavu-fonts-ttf*/**',
                '!./' + extensionType + '/redshop_pdf/tcpdf/helper/vendor/tecnickcom/tcpdf/fonts/freefont-*/**',
                "./' + extensionType + '/redshop_export/attribute/**",
                "./' + extensionType + '/redshop_export/category/**",
                "./' + extensionType + '/redshop_export/field/**",
                "./' + extensionType + '/redshop_export/manufacturer/**",
                "./' + extensionType + '/redshop_export/product/**",
                "./' + extensionType + '/redshop_export/product_stockroom_data/**",
                "./' + extensionType + '/redshop_export/related_product/**",
                "./' + extensionType + '/redshop_export/shipping_address/**",
                "./' + extensionType + '/redshop_export/shopper_group_attribute_price/**",
                "./' + extensionType + '/redshop_export/shopper_group_product_price/**",
                "./' + extensionType + '/redshop_export/user/**",
                "./' + extensionType + '/redshop_import/attribute/**",
                "./' + extensionType + '/redshop_import/category/**",
                "./' + extensionType + '/redshop_import/field/**",
                "./' + extensionType + '/redshop_import/manufacturer/**",
                "./' + extensionType + '/redshop_import/product/**",
                "./' + extensionType + '/redshop_import/product_stockroom_data/**",
                "./' + extensionType + '/redshop_import/shipping_address/**",
                "./' + extensionType + '/redshop_import/shopper_group_product_price/**",
                "./' + extensionType + '/redshop_import/shopper_group_attribute_price/**",
                "./' + extensionType + '/redshop_import/user/**",
                "./' + extensionType + '/redshop_import/related_product/**"
            ],{ base: "./" })
            .pipe(zip(fileName))
            .pipe(gulp.dest(config.releaseDir))
            .on("end", cb);
        });
    });
});
