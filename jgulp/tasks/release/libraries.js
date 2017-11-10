var gulp   = require("gulp");
var path   = require("path");
var gutil  = require("gulp-util");
var zip    = require("gulp-zip");
var fs     = require("fs");
// Get console args
var argv   = require("yargs").argv;
// XML parser
var xml2js = require("xml2js");
var parser = new xml2js.Parser();

var basePath = './libraries';

/**
 * Function for release library
 * @param name
 * @returns {*}
 */
function libRelease(name) {
    var fileName = "lib-" + name;
    var destDir  = config.releaseDir;

    var src = [
        basePath + '/' + name + '/**',
        '!' + basePath + '/' + name + '/**/composer.json',
        '!' + basePath + '/' + name + '/**/composer.lock',
        '!' + basePath + '/' + name + '/**/vendor/**/*.md',
        '!' + basePath + '/' + name + '/**/vendor/**/*.txt',
        '!' + basePath + '/' + name + '/**/vendor/**/*.TXT',
        '!' + basePath + '/' + name + '/**/vendor/**/*.pdf',
        '!' + basePath + '/' + name + '/**/vendor/**/LICENSE',
        '!' + basePath + '/' + name + '/**/vendor/**/CHANGES',
        '!' + basePath + '/' + name + '/**/vendor/**/README',
        '!' + basePath + '/' + name + '/**/vendor/**/VERSION',
        '!' + basePath + '/' + name + '/**/vendor/**/composer.json',
        '!' + basePath + '/' + name + '/**/vendor/**/.gitignore',
        '!' + basePath + '/' + name + '/**/vendor/**/docs',
        '!' + basePath + '/' + name + '/**/vendor/**/docs/**',
        '!' + basePath + '/' + name + '/**/vendor/**/tests',
        '!' + basePath + '/' + name + '/**/vendor/**/tests/**',
        '!' + basePath + '/' + name + '/**/vendor/**/unitTests',
        '!' + basePath + '/' + name + '/**/vendor/**/unitTests/**',
        '!' + basePath + '/' + name + '/**/vendor/**/.git',
        '!' + basePath + '/' + name + '/**/vendor/**/.git/**',
        '!' + basePath + '/' + name + '/**/vendor/**/examples',
        '!' + basePath + '/' + name + '/**/vendor/**/examples/**',
        '!' + basePath + '/' + name + '/**/vendor/**/build.xml',
        '!' + basePath + '/' + name + '/**/vendor/**/phpunit.xml',
        '!' + basePath + '/' + name + '/**/vendor/**/phpunit.xml.dist',
        '!' + basePath + '/' + name + '/**/vendor/**/phpcs.xml',
        '!' + basePath + '/' + name + '/**/vendor/mpdf/mpdf/ttfonts/!(DejaVu*.ttf)',
        '!' + basePath + '/' + name + '/**/vendor/setasign/fpdi',
        '!' + basePath + '/' + name + '/**/vendor/setasign/fpdi/**',
        '!' + basePath + '/' + name + '/**/vendor/tecnickcom/tcpdf/fonts/!(courier*.php|helvetica*.php|symbol*.php|times*.php|uni2cid_a*.php|zapfdingbats*.php)',
        '!' + basePath + '/' + name + '/**/vendor/tecnickcom/tcpdf/fonts/ae_fonts*/**',
        '!' + basePath + '/' + name + '/**/vendor/tecnickcom/tcpdf/fonts/dejavu-fonts-ttf*/**',
        '!' + basePath + '/' + name + '/**/vendor/tecnickcom/tcpdf/fonts/freefont-*/**'
    ];

    if (!argv.skipVersion) {
        fs.readFile(basePath + "/" + name + "/" + name + ".xml", function (err, data) {
            parser.parseString(data, function (err, result) {
                var version = result.extension.version[0];

                fileName += "-v" + version + ".zip";

                var count      = 35 - name.length;
                var nameFormat = name;

                for (var i = 0; i < count; i++) {
                    nameFormat += " ";
                }

                count = 8 - version.length;

                for (i = 0; i < count; i++) {
                    version += " ";
                }

                // We will output where release package is going so it is easier to find
                renderLog("Library", '', nameFormat, version, path.join(config.releaseDir + "/", fileName));

                return releaseExt(src, fileName, destDir);
            });
        });
    }
    else {
        return releaseExt(src, fileName + ".zip", destDir);
    }
}

// Release: Modules
gulp.task("release:library", function (cb) {
    var libName = argv.name ? argv.name : false;

    if (libName) {
        try {
            fs.statSync(basePath + "/" + libName);
        }
        catch (e) {
            console.error("Folder not exist: " + basePath + "/" + libName);
            return;
        }

        libRelease(libName);
    }

    var libraries = getFolders(basePath);

    for (var i = 0; i < libraries.length; i++) {
        if (libraries[i] !== "redshop") {
            libRelease(libraries[i]);
        }
    }
});