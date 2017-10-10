const gulp       = require("gulp");
const requireDir = require("require-dir");
const fs         = require("fs");
const path       = require("path");
const through    = require("through2");
const pd         = require("pretty-data").pd;
const upath      = require("upath");

var iniJsons = [];

var stripPrefix = function (name) {
    return name.substr(6);
};

gulp.task("crowdin-conf", ["getAdminFiles", "getSiteFiles"], function () {
    var content = "\"preserve_hierarchy\": true\n";
    content += "commit_message: \"New localization strings available\"\n";
    content += "\"files\": " + pd.json(JSON.stringify(iniJsons));
    fs.writeFileSync("./crowdin.yml", content);
});

gulp.task("getAdminFiles", function () {
    return gulp.src(
        [
            "component/admin/**/*.ini",
            "plugins/**/en-GB.*.ini",
            "modules/admin/**/*.ini"
        ],
        {base: "./"}
    ).pipe(through.obj(function (file, enc, cb) {
        iniJsons.push({
            "source"     : "/" + upath.toUnix(file.relative),
            "translation": "/src/lang/%locale%/admin/%locale%/%locale%." + stripPrefix(path.basename(file.path))
        });
        cb(null, file);
    }));
});

gulp.task("getSiteFiles", function () {
    return gulp.src(
        [
            "component/site/**/*.ini",
            "libraries/redshop/language/**/*.ini",
            "modules/site/**/*.ini"
        ],
        {base: "./"}
    ).pipe(through.obj(function (file, enc, cb) {
        iniJsons.push({
            "source"     : "/" + upath.toUnix(file.relative),
            "translation": "/src/lang/%locale%/site/%locale%/%locale%." + stripPrefix(path.basename(file.path))
        });
        cb(null, file);
    }));
});

