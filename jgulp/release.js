var gulp       = require("gulp");
var zip        = require("gulp-zip");

global.releaseExt = function releaseExt(arraySrc, fileName, dest) {
    return gulp.src(arraySrc)
        .pipe(zip(fileName))
        .pipe(gulp.dest(dest));
}