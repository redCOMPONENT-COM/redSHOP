var gulp       = require("gulp");
var path       = require("path");
var gutil      = require('gulp-util');
var zip        = require("gulp-zip");
var fs         = require("fs");
// Get console args
var argv       = require("yargs").argv;
// XML parser
var xml2js     = require("xml2js");
var parser     = new xml2js.Parser();

function getIncludedExtensions() {
    var included = [];
    var includedPlugins = [
        "./plugins/system/redshop/**",
        "./plugins/system/redgoogleanalytics/**",
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
    ];
    var excluded = [
        '!./plugins/redshop_pdf/tcpdf/helper/vendor/tecnickcom/tcpdf/fonts/!(courier*.php|helvetica*.php|symbol*.php|times*.php|uni2cid_a*.php|zapfdingbats*.php)',
        '!./plugins/redshop_pdf/tcpdf/helper/vendor/tecnickcom/tcpdf/fonts/ae_fonts*/**',
        '!./plugins/redshop_pdf/tcpdf/helper/vendor/tecnickcom/tcpdf/fonts/dejavu-fonts-ttf*/**',
        '!./plugins/redshop_pdf/tcpdf/helper/vendor/tecnickcom/tcpdf/fonts/freefont-*/**',
    ];
    var includedModules = [
        "./modules/site/mod_redshop_cart/**"
    ];

    return included.concat(includedPlugins, includedModules, excluded);
}

gulp.task("release:redshop", ["scripts:components.redshop", "sass:components.redshop", "composer:libraries.redshop", "composer:plugins.redshop_pdf.tcpdf"], function (cb) {
    fs.readFile("./redshop.xml", function (err, data) {
        parser.parseString(data, function (err, result) {
            var version = result.extension.version[0];
            var fileName = argv.skipVersion ? "redshop.zip" : "redshop-v" + version + ".zip";
            var dest = config.releaseDir;

            gutil.log(gutil.colors.grey("===================================================================="));
            gutil.log(gutil.colors.cyan.bold("redSHOP"), "  |  ", gutil.colors.yellow.bold(version), "  |  ", gutil.colors.white.bold(path.join(config.releaseDir + '/', fileName)));
            gutil.log(gutil.colors.grey("===================================================================="));
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
            return gulp.src(src, {base: "./"})
                .pipe(zip(fileName))
                .pipe(gulp.dest(dest))
                .on("end", cb);
        });
    });
});