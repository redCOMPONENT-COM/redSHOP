var gulp       = require("gulp");

// Copy to test site
gulp.task(
    'copy',
    gulp.series(
        'copy:components',
        'copy:libraries',
        'copy:modules',
        'copy:packages',
        'copy:plugins'
    ), function() {
        return true;
    });