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
 * Function for release module
 * @param group
 * @param name
 * @returns {*}
 */
function moduleRelease(group, name) {
    var fileName = name;
    var arraySrc = getGlobExtensionPattern('modules', group, name);
    var destDir = config.releaseDir + '/modules/' + group;

    if (!argv.skipVersion) {
        fs.readFile('./modules/' + group + '/' + name + '/' + name + '.xml', function(err, data) {
            parser.parseString(data, function (err, result) {
                var version = result.extension.version[0];

                fileName += '-v' + version + '.zip';

                var count = 35 - name.length;
                var nameFormat = name;

                for (var i = 0; i < count; i++)
                {
                    nameFormat += ' ';
                }

                count = 8 - version.length;

                for (i = 0; i < count; i++)
                {
                    version += ' ';
                }

                // We will output where release package is going so it is easier to find
                renderLog("Module", group, nameFormat, version, path.join(config.releaseDir + '/modules/' + group, fileName));

                return releaseExt(arraySrc, fileName, destDir);
            });
        });
    }
    else {
        return releaseExt(arraySrc, fileName + '.zip', destDir);
    }
}

// Release: Modules
gulp.task('release:module', function(cb) {
    var basePath  = config.basePaths.modules;
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