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

/**
 * Function for release plugin
 * @param group
 * @param name
 * @returns {*}
 */
function pluginRelease(group, name) {
    var fileName = 'plg_' + group + '_' + name;
    var arraySrc = [
        './plugins/' + group + '/' + name + '/**',
        '!./plugins/' + group + '/' + name + '/**/composer.json',
        '!./plugins/' + group + '/' + name + '/**/composer.lock',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/*.md',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/*.txt',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/*.TXT',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/*.pdf',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/LICENSE',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/CHANGES',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/README',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/VERSION',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/composer.json',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/.gitignore',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/docs',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/docs/**',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/tests',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/tests/**',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/unitTests',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/unitTests/**',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/.git',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/.git/**',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/examples',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/examples/**',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/build.xml',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/phpunit.xml',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/phpunit.xml.dist',
        '!./plugins/' + group + '/' + name + '/**/vendor/**/phpcs.xml',
        '!./plugins/' + group + '/' + name + '/**/vendor/mpdf/mpdf/ttfonts/!(DejaVu*.ttf)',
        '!./plugins/' + group + '/' + name + '/**/vendor/setasign/fpdi',
        '!./plugins/' + group + '/' + name + '/**/vendor/setasign/fpdi/**',
        '!./plugins/' + group + '/' + name + '/**/vendor/tecnickcom/tcpdf/fonts/!(courier*.php|helvetica*.php|symbol*.php|times*.php|uni2cid_a*.php|zapfdingbats*.php)',
        '!./plugins/' + group + '/' + name + '/**/vendor/tecnickcom/tcpdf/fonts/ae_fonts*/**',
        '!./plugins/' + group + '/' + name + '/**/vendor/tecnickcom/tcpdf/fonts/dejavu-fonts-ttf*/**',
        '!./plugins/' + group + '/' + name + '/**/vendor/tecnickcom/tcpdf/fonts/freefont-*/**'
    ];

    if (!argv.skipVersion) {
        fs.readFile('./plugins/' + group + '/' + name + '/' + name + '.xml', function (err, data) {
            parser.parseString(data, function (err, result) {
                var version = result.extension.version[0];

                // Generate file name
                fileName += '-v' + version + '.zip';

                var count = 25 - group.length;
                var groupName = group;

                for (var i = 0; i < count; i++) {
                    groupName += ' ';
                }

                count = 35 - name.length;
                var nameFormat = name;

                for (i = 0; i < count; i++) {
                    nameFormat += ' ';
                }

                count = 11 - version.length;

                for (i = 0; i < count; i++) {
                    version += ' ';
                }

                // We will output where release package is going so it is easier to find
                gutil.log(
                    gutil.colors.green("Plugin"),
                    "  |  ",
                    gutil.colors.white(groupName),
                    "  |  ",
                    gutil.colors.blue(nameFormat),
                    "  |  ",
                    gutil.colors.yellow(version),
                    "|  ",
                    gutil.colors.grey(path.join(config.releaseDir + '/plugins', fileName))
                );

                return gulp.src(arraySrc)
                    .pipe(zip(fileName))
                    .pipe(gulp.dest(config.releaseDir + '/plugins'));
            });
        });
    }
    else {
        return gulp.src(arraySrc)
            .pipe(zip(fileName + '.zip'))
            .pipe(gulp.dest(config.releaseDir + '/plugins'));
    }
}

// Release: Plugins
gulp.task('release:plugin', function (cb) {
    var basePath = config.basePaths.plugins;
    var plgGroup = argv.group ? argv.group : false;
    var plgName = argv.name ? argv.name : false;
    var plugins = [];

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
    else {
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