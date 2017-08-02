var gulp       = require("gulp");

// Watch for file changes
gulp.task(
    'watch',
    [
        'watch:components',
        'watch:libraries',
        'watch:modules',
        'watch:packages',
        'watch:plugins'
    ], function() {
        return true;
    });
