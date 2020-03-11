var gulp = require('gulp');
var config = require('../../gulp-config');

// Do we have a specifc extensions file?
try {
	var extensions = require('../../gulp-extensions.json');
} catch (err) {
	var extensions = config.extensions;
}

/**
 * Get the available modules from paths
 *
 * @param   string  app  'frontend' | 'backend'
 *
 * @return  array
 */
function getModules(app) {
	var results = [];

	if (extensions && extensions.hasOwnProperty('modules')
		&& extensions.modules.hasOwnProperty(app)
	) {
		var sourceArray = extensions.modules[app];

		for (index = 0; index < sourceArray.length; ++index) {
			results.push(app + '.' + sourceArray[index]);
		}
	}

	return results;
}

/**
 * Function to ease the modules management
 *
 * @param   string  baseTask  Task to use as root. Example: 'clean:modules'
 * @param   string  app       'frontend', 'backend'
 *
 * @return  array
 */
function getModulesTasks(baseTask, app) {
	var tasks = [];
	var modules = getModules(app);

	if (modules) {
		for (index = 0; index < modules.length; ++index) {
			tasks.push(baseTask + '.' + modules[index]);
		}
	}

	if (tasks.length > 0) {
		return gulp.series.apply(gulp, tasks);
	}
}

// Clean
var frontend = getModules('frontend');
var backend = getModules('backend');
var clean = [];
var copy = [];
var watch = [];

if (frontend.length > 0) {
	gulp.task('clean:modules.frontend',
		getModulesTasks('clean:modules', 'frontend'),
		function () {
			return true;
		});

	// Copy
	gulp.task('copy:modules.frontend',
		getModulesTasks('copy:modules', 'frontend'),
		function () {
			return true;
		});

	// Watch
	gulp.task('watch:modules.frontend',
		getModulesTasks('watch:modules', 'frontend'),
		function () {
			return true;
		});

	clean.push('clean:modules.frontend');
	copy.push('copy:modules.frontend');
	watch.push('watch:modules.frontend');
}

if (backend.length > 0) {
	gulp.task('clean:modules.backend',
		getModulesTasks('clean:modules', 'backend'),
		function () {
			return true;
		});


	gulp.task('copy:modules.backend',
		getModulesTasks('copy:modules', 'backend'),
		function () {
			return true;
		});


	gulp.task('watch:modules.backend',
		getModulesTasks('watch:modules', 'backend'),
		function () {
			return true;
		});

	clean.push('clean:modules.backend');
	copy.push('copy:modules.backend');
	watch.push('watch:modules.backend');
}

if (clean.length > 0) {
	gulp.task('clean:modules',
		gulp.series.apply(gulp, clean),
		function () {
			return true
		});
}

if (copy.length > 0) {
	gulp.task('copy:modules',
		gulp.series.apply(gulp, copy),
		function () {
			return true;
		});
}

if (watch.length > 0) {
	gulp.task('watch:modules',
		gulp.series.apply(gulp, watch),
		function () {
			return true;
		});
}

exports.getModules = getModules;
exports.getModulesTasks = getModulesTasks;
