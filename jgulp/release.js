const gulp   = require("gulp");
const zip    = require("gulp-zip");
const path   = require("path");
const fs     = require("fs");
const xml2js = require("xml2js");
const merge  = require("merge-stream");

var parser = new xml2js.Parser();

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

// Overwrite "release" method
gulp.task("release",
    [
        "release:plugin",
        "release:module",
        "release:redshop"
    ]
);

gulp.task("release:md5:generate", function () {

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
    ], {base: "./"}).pipe(hashsum({dest: "./", filename: "checksum.md5", hash: "md5"}));
});

gulp.task("release:md5:json", ["release:md5:generate"], function (cb) {
    var fileContent = fs.readFileSync(path.join("./checksum.md5"), "utf8");
    var temp        = fileContent.split("\n");
    var result      = [];
    var t1;

    for (var i = 0; i < temp.length; i++) {
        t1 = temp[i].split(" ");

        if (t1[0].trim().length) {
            var item = {"md5": t1[0], "path": t1[2]};
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
        "release:md5:clean"
    ]
);

gulp.task("release:md5:clean", ["release:md5:json"], function () {
    return gulp.src("./checksum.md5").pipe(clean({force: true}));
});

// Temporary remove release:md5 since it not ready for use yet.
// // gulp.task("release:redshop", ["composer:libraries", "release:md5"], function (cb) {

gulp.task("release:languages", function () {
    const langPath   = "./src/lang";
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
                    console.log(err);

                    return;
                }

                const lang     = path.basename(directory);
                const version  = result.extension.version[0];
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
