var gulp = require("gulp");
var composer = require('gulp-composer');
var requireDir = require("require-dir");
var fs = require("fs");
const log = require('fancy-log');
const colors = require('colors');
var path = require("path");
var glob = require('glob');
global.config = require("./gulp-config.json");

/**
 * Function for read list folder
 *
 * @param  string dir Path of folder
 *
 * @return array      Subfolder list.
 */
global.getFolders = function getFolders(dir) {
    return fs.readdirSync(dir)
        .filter(function (file) {
                return fs.statSync(path.join(dir, file)).isDirectory();
            }
        );
}

/**
 * Output log
 *
 * @param extension
 * @param group
 * @param extName
 * @param version
 * @param releasePath
 */
global.renderLog = function renderLog(extension, group, extName, version, releasePath) {
    // We will output where release package is going so it is easier to find
    log(
        colors.green(extension),
        "  |  ",
        colors.white(group),
        "  |  ",
        colors.blue(extName),
        "  |  ",
        colors.yellow(version),
        "|  ",
        colors.grey(releasePath)
    );
}

/**
 * Get glob of an extension
 *
 * @param extensionType
 * @param group
 * @param extName
 * @returns {[*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*]}
 */
global.getGlobExtensionPattern = function getGlobExtensionPattern(extensionType, group, extName) {
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
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/tecnickcom/tcpdf/fonts/!(dejavusans*.php|courier*.php|helvetica*.php|symbol*.php|times*.php|uni2cid_a*.php|zapfdingbats*.php)',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/tecnickcom/tcpdf/fonts/ae_fonts*/**',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/tecnickcom/tcpdf/fonts/dejavu-fonts-ttf*/**',
        '!./' + extensionType + '/' + group + '/' + extName + '/**/vendor/tecnickcom/tcpdf/fonts/freefont-*/**'
    ]
}

/**
 * Execute composer to get vendor
 *
 * @param composerPath
 */
global.executeComposer = function executeComposer(composerPath) {
    log("Composer found: ", colors.blue(composerPath));
    composer({cwd: composerPath});
}

gulp.task("composer", function (cb) {
    glob("**/composer.json", [], function (er, files) {
        for (var i = 0; i < files.length; i++) {
            var composerPath = path.dirname(files[i]);

            // Make sure this is not composer.json inside vendor library
            if (composerPath.indexOf("vendor") == -1 && composerPath != '.') {
                log("Composer found: ", colors.blue(composerPath));
                composer({cwd: composerPath}).on('end', cb);
            }
        }
    });
});

var jgulp = requireDir("./redGulp/extensions", {recurse: true});
var gulpSrc = requireDir("./redGulp/tasks", {recurse: true});
var gulpSrc = requireDir("./redGulp/src", {recurse: true});