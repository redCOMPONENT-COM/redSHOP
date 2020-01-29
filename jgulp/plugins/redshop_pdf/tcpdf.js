require('../../common');
var gulp        = require("gulp");
var composer    = require("gulp-composer");
var baseTask   = 'plugins.' + group + '.' + name;
var extPath    = './plugins/' + group + '/' + name;
var wwwExtPath = config.wwwDir + '/plugins/' + group + '/' + name;

var group = "redshop_pdf";
var name  = "tcpdf";

releasePlugin(group, name);

// Composer
gulp.task("composer:" + baseTask, function () {
    composer({cwd: extPath + "/helper", bin: "php ./composer.phar"});
});
