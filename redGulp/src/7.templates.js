var gulp = require('gulp');
var config = require('../../gulp-config');

// Do we have a specifc extensions file?
try {
	var extensions = require('../../gulp-extensions.json');
} catch (err) {
	var extensions = config.extensions;
}

/**
 * Get the available templates
 *
 * @param   string  app  'frontend' | 'backend'
 *
 * @return  array
 */
function getTemplates(app) {
	var results = [];

	if (extensions && extensions.hasOwnProperty('templates')
		&& extensions.templates.hasOwnProperty(app)
	) {
		var sourceArray = extensions.templates[app];

		for (index = 0; index < sourceArray.length; ++index) {
			results.push(app + '.' + sourceArray[index]);
		}
	}

	return results;
}

/**
 * Function to get the tasks to execute
 *
 * @param   string  baseTask  Task to use as root. Example: 'clean:templates'
 *
 * @return  array
 */
function getTemplatesTasks(baseTask, app) {
	var tasks = [];
	var templates = getTemplates(app);

	if (templates) {
		for (index = 0; index < templates.length; ++index) {
			tasks.push(baseTask + '.' + templates[index]);
		}
	}

	if (tasks.length > 0) {
		console.log('[DEBUG] :==========>' + baseTask + '.' + app);
		return gulp.series.apply(gulp, tasks);
	}
}

// Clean
var frontend = getTemplates('frontend');
var backend = getTemplates('backend');
var clean = [];
var copy = [];
var watch = [];

if (frontend.length > 0) {
	gulp.task('clean:templates.frontend',
		gettemplatesTasks('clean:templates', 'frontend'),
		function () {
			return true;
		});

	// Copy
	gulp.task('copy:templates.frontend',
		gettemplatesTasks('copy:templates', 'frontend'),
		function () {
			return true;
		});

	// Watch
	gulp.task('watch:templates.frontend',
		gettemplatesTasks('watch:templates', 'frontend'),
		function () {
			return true;
		});

	clean.push('clean:templates.frontend');
	copy.push('copy:templates.frontend');
	watch.push('watch:templates.frontend');
}

if (backend.length > 0) {
	gulp.task('clean:templates.backend',
		gettemplatesTasks('clean:templates', 'backend'),
		function () {
			return true;
		});


	gulp.task('copy:templates.backend',
		gettemplatesTasks('copy:templates', 'backend'),
		function () {
			return true;
		});


	gulp.task('watch:templates.backend',
		gettemplatesTasks('watch:templates', 'backend'),
		function () {
			return true;
		});

	clean.push('clean:templates.backend');
	copy.push('copy:templates.backend');
	watch.push('watch:templates.backend');
}

if (clean.length > 0) {
	gulp.task('clean:templates',
		gulp.series.apply(gulp, clean),
		function () {
			return true
		});
}

if (copy.length > 0) {
	gulp.task('copy:templates',
		gulp.series.apply(gulp, copy),
		function () {
			return true;
		});
}

if (watch.length > 0) {
	gulp.task('watch:templates',
		gulp.series.apply(gulp, watch),
		function () {
			return true;
		});
}
exports.getTemplates = getTemplates;
exports.getTemplatesTasks = getTemplatesTasks;
