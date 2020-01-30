require('../../common');
var gulp        = require("gulp");
var composer    = require("gulp-composer");

var group = "redshop_pdf";
var name  = "tcpdf";

releasePlugin(group, name);

var baseTask   = 'plugins.' + group + '.' + name;
var extPath    = './plugins/' + group + '/' + name;

// Composer
gulp.task("composer:" + baseTask, function (cb) {
    composer({cwd: extPath + "/helper", bin: "php ./composer.phar"});
    cb();
});
