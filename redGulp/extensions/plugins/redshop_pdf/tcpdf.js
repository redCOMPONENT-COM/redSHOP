var group = "redshop_pdf";
var name = "tcpdf";

var helper = require('./../../helpers/plugin.js');
releasePlugin(group, name);

var gulp = require('gulp');
var baseTask = "plugins." + group + "." + name;
var extPath = "./plugins/" + group + "/" + name;
var composer = require("gulp-composer");

// Composer
gulp.task("composer:" + baseTask, function () {
    composer({ cwd: extPath + "/helper", bin: "php ./composer.phar" });
});
