var gulp = require('gulp');
var log = require('fancy-log');
var colors = require('colors');
var zip = require("gulp-zip");
var fs = require("fs");
var del = require('del');
var sass = require('gulp-sass');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var path = require("path");
var merge = require('merge-stream');
var argv = require("yargs").argv;
var hashsum = require('gulp-hashsum');
// XML parser
var xml2js = require("xml2js");
var parser = new xml2js.Parser();
// Get console args

// Load config
var extension = require('../../package.json');
var config = require('../../gulp-config');

var defaultBrowserConfig = {
    proxy: "localhost"
}

// Keep B/C support for old browserSyncProxy setting
if (config.hasOwnProperty('browserSyncProxy')) {
    defaultBrowserConfig.proxy = config.browserSyncProxy;
}

var browserConfig = config.hasOwnProperty('browserConfig') ? config.browserConfig : defaultBrowserConfig;

// Tools / Dependencies
var browserSync = require('browser-sync');
var zip = require('gulp-zip');


function getIncludedExtensions() {
    var included = [];
    var includedPlugins = [
        "./plugins/content/rs_menuitem_sh404urls/**",
        "./plugins/system/redshop/**",
        "./plugins/system/redgoogleanalytics/**",
        "./plugins/system/redgoogletagmanager/**",
        "./plugins/redshop_payment/rs_payment_banktransfer/**",
        "./plugins/redshop_payment/rs_payment_paypal/**",
        "./plugins/redshop_product/sh404urls/**",
        "./plugins/finder/redshop/**",
        "./plugins/redshop_alert/alert/**",
        "./plugins/redshop_shipping/default_shipping/**",
        "./plugins/sh404sefextplugins/sh404sefextplugincom_redshop/**",
        "./plugins/redshop_pdf/tcpdf/**",
        "./plugins/redshop_export/attribute/**",
        "./plugins/redshop_export/category/**",
        "./plugins/redshop_export/field/**",
        "./plugins/redshop_export/manufacturer/**",
        "./plugins/redshop_export/newsletter_subscriber/**",
        "./plugins/redshop_export/product/**",
        "./plugins/redshop_export/product_stockroom_data/**",
        "./plugins/redshop_export/related_product/**",
        "./plugins/redshop_export/shipping_address/**",
        "./plugins/redshop_export/shopper_group_attribute_price/**",
        "./plugins/redshop_export/shopper_group_product_price/**",
        "./plugins/redshop_export/user/**",
        "./plugins/redshop_export/order/**",
        "./plugins/redshop_import/attribute/**",
        "./plugins/redshop_import/category/**",
        "./plugins/redshop_import/field/**",
        "./plugins/redshop_import/manufacturer/**",
        "./plugins/redshop_import/newsletter_subscriber/**",
        "./plugins/redshop_import/product/**",
        "./plugins/redshop_import/product_stockroom_data/**",
        "./plugins/redshop_import/shipping_address/**",
        "./plugins/redshop_import/shopper_group_product_price/**",
        "./plugins/redshop_import/shopper_group_attribute_price/**",
        "./plugins/redshop_import/user/**",
        "./plugins/redshop_import/related_product/**",
        "./plugins/quickicon/redshop/**",
        "./plugins/search/redshop_categories/**",
        "./plugins/search/redshop_products/**",
        "./plugins/twig/cache/**",
        "./plugins/twig/debug/**",
        "./plugins/twig/japp/**",
        "./plugins/twig/jarray/**",
        "./plugins/twig/jdoc/**",
        "./plugins/twig/jhtml/**",
        "./plugins/twig/jlang/**",
        "./plugins/twig/jlayout/**",
        "./plugins/twig/jmodule/**",
        "./plugins/twig/jposition/**",
        "./plugins/twig/jprofiler/**",
        "./plugins/twig/jregistry/**",
        "./plugins/twig/jroute/**",
        "./plugins/twig/jsession/**",
        "./plugins/twig/jtext/**",
        "./plugins/twig/juri/**",
        "./plugins/twig/juser/**",
        "./plugins/twig/unseriallize/**",
        "./plugins/twig/redshop/**",
        "./plugins/redshop_promotion/promotion/**",
    ];
    var excluded = [
        '!./plugins/redshop_pdf/tcpdf/helper/vendor/tecnickcom/tcpdf/fonts/!(courier*.php|helvetica*.php|symbol*.php|times*.php|uni2cid_a*.php|zapfdingbats*.php)',
        '!./plugins/redshop_pdf/tcpdf/helper/vendor/tecnickcom/tcpdf/fonts/*/*'
    ];
    var includedModules = [
        "./modules/site/mod_redshop_cart/**",
        "./modules/site/mod_redshop_search/**"
    ];

    return included.concat(includedPlugins, includedModules, excluded);
}

// Browser sync
gulp.task('browser-sync', function () {
    return browserSync(browserConfig);
});

// Clean test site
gulp.task(
    'clean',
    gulp.series(
        'clean:components',
        'clean:libraries',
        'clean:modules',
        'clean:plugins',
    ), function () {
        return true;
    });

// Copy to test site
gulp.task('copy', gulp.series(
    'composer',
    'copy:modules',
    'copy:plugins',
    'copy:libraries',
    'copy:components.redshop'
), function (cb) {
    cb();
    return true;
});

// Watch for file changes
gulp.task('watch', gulp.series(
    'watch:components',
    'watch:libraries',
    //'watch:media',
    'watch:modules',
    //'watch:packages',
    'watch:plugins',
    //'watch:templates'
), function () {
    return true;
});


// Check if config has defaultTasks defined
var defaultTasks = config.hasOwnProperty('defaultTasks') ? config.defaultTasks : gulp.series('copy', 'watch', 'browser-sync');
// Default task
gulp.task('default', defaultTasks, function () {
});


/**
 * Execute gulp to release an extension
 *
 * @param arraySrc
 * @param fileName
 * @param dest
 * @returns {*}
 */
global.releaseExt = function releaseExt(arraySrc, fileName, dest) {
    return gulp.src(arraySrc).pipe(zip(fileName)).pipe(gulp.dest(dest));
};


// Temporary remove release:md5 since it not ready for use yet.
// // gulp.task("release:redshop", ["composer:libraries", "release:md5"], function (cb) {

gulp.task("release:languages", function () {
    const langPath = "./src/lang";
    const releaseDir = path.join(config.releaseDir, "language");

    const folders = fs.readdirSync(langPath).map(function (file) {
        return path.join(langPath, file);
    }).filter(function (file) {
        return fs.existsSync(path.join(file, "install.xml"));
    });

    // We need to combine streams so we can know when this task is actually done
    return merge(folders.map(function (directory) {
            const data = fs.readFileSync(path.join(directory, "install.xml"));

            // xml2js parseString is sync, but must be called using callbacks... hence this awkwards vars
            // see https://github.com/Leonidas-from-XIV/node-xml2js/issues/159
            var task;
            var error;

            parser.parseString(data, function (err, result) {
                if (err) {
                    error = err;

                    return;
                }

                const lang = path.basename(directory);
                const version = result.extension.version[0];
                const fileName = config.skipVersion ? result.extension.name + ".zip" : result.extension.name + "-v" + version + ".zip";

                task = gulp.src([directory + "/**"]).pipe(zip(fileName)).pipe(gulp.dest(releaseDir));
            });

            if (error) {
                throw error;
            }

            if (!error && !task) {
                throw new Error("xml2js callback became suddenly async or something.");
            }

            return task;
        })
    );
});

gulp.task("release:md5:generate", function () {

    log(colors.yellow("Create checksum.md5 file in: checksum.md5"));

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
        "./plugins/content/rs_menuitem_sh404urls/**",
        "./plugins/system/redshop/**",
        "./plugins/system/redgoogleanalytics/**",
        "./plugins/system/redgoogletagmanager/**",
        "./plugins/redshop_payment/rs_payment_banktransfer/**",
        "./plugins/redshop_product/sh404urls/**",
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
        "./plugins/redshop_export/newsletter_subscriber/**",
        "./plugins/redshop_export/product/**",
        "./plugins/redshop_export/product_stockroom_data/**",
        "./plugins/redshop_export/related_product/**",
        "./plugins/redshop_export/shipping_address/**",
        "./plugins/redshop_export/shopper_group_attribute_price/**",
        "./plugins/redshop_export/shopper_group_product_price/**",
        "./plugins/redshop_export/user/**",
        "./plugins/redshop_export/order/**",
        "./plugins/redshop_import/attribute/**",
        "./plugins/redshop_import/category/**",
        "./plugins/redshop_import/field/**",
        "./plugins/redshop_import/manufacturer/**",
        "./plugins/redshop_import/newsletter_subscriber/**",
        "./plugins/redshop_import/product/**",
        "./plugins/redshop_import/product_stockroom_data/**",
        "./plugins/redshop_import/shipping_address/**",
        "./plugins/redshop_import/shopper_group_product_price/**",
        "./plugins/redshop_import/shopper_group_attribute_price/**",
        "./plugins/redshop_import/user/**",
        "./plugins/redshop_import/related_product/**",
        "./plugins/twig/cache/**",
        "./plugins/twig/debug/**",
        "./plugins/twig/japp/**",
        "./plugins/twig/jarray/**",
        "./plugins/twig/jdoc/**",
        "./plugins/twig/jhtml/**",
        "./plugins/twig/jlang/**",
        "./plugins/twig/jlayout/**",
        "./plugins/twig/jmodule/**",
        "./plugins/twig/jposition/**",
        "./plugins/twig/jprofiler/**",
        "./plugins/twig/jregistry/**",
        "./plugins/twig/jroute/**",
        "./plugins/twig/jsession/**",
        "./plugins/twig/jtext/**",
        "./plugins/twig/juri/**",
        "./plugins/twig/juser/**",
        "./plugins/twig/unseriallize/**",
        "./plugins/twig/redshop/**",
        "./plugins/redshop_promotion/promotion/**",
    ], {base: "./", allowEmpty: true}).pipe(hashsum({dest: "./", filename: "checksum.md5", hash: "md5"}));
});

gulp.task("release:md5:json", gulp.series("release:md5:generate"), function (cb) {
    var fileContent = fs.readFileSync(path.join("./checksum.md5"), "utf8");
    var temp = fileContent.split("\n");
    var result = [];
    var t1;

    for (var i = 0; i < temp.length; i++) {
        t1 = temp[i].split(" ");

        if (t1[0].trim().length) {
            var item = {"md5": t1[0], "path": t1[2]};
            result.push(item);
        }
    }

    log(colors.yellow(("checksum.md5.json file: "), "component/admin/assets/checksum.md5.json"));

    rs = JSON.stringify(result);

    fs.writeFile("./component/admin/assets/checksum.md5.json", rs);

    return cb();
});

gulp.task("release:md5:clean", gulp.series("release:md5:json"), function () {
    return gulp.src("./checksum.md5").pipe(clean({force: true}));
});

gulp.task("release:redshop", function (cb) {
    fs.readFile("./redshop.xml", function (err, data) {
        parser.parseString(data, function (err, result) {
            var version = result.extension.version[0];
            var fileName = argv.skipVersion ? "redshop.zip" : "redshop-v" + version + ".zip";
            var dest = config.releaseDir;

            log(colors.grey("===================================================================="));
            log(colors.cyan.bold("redSHOP"), "  |  ", colors.yellow.bold(version), "  |  ", colors.white.bold(path.join(config.releaseDir + '/', fileName)));
            log(colors.grey("===================================================================="));
            var src = getIncludedExtensions();
            src = src.concat([
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
                "./*(install.php|LICENSE.txt|redshop.xml)"
            ]);
            return gulp.src(src, {base: "./", allowEmpty: true})
                .pipe(zip(fileName))
                .pipe(gulp.dest(dest))
                .on("end", cb);
        });
    });

    cb();
});

gulp.task("release:md5",
    gulp.series(
        "release:md5:generate",
        "release:md5:json",
        "release:md5:clean"
    )
);

// Overwrite "release" method
gulp.task("release",
    gulp.series(
        "release:plugin",
        "release:module",
        "scripts:components.redshop",
        "sass:components.redshop",
        "composer",
        "release:redshop"
    )
);