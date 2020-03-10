var gulp = require('gulp');
var config = require('../../gulp-config');

// Dependencies
var browserSync = require('browser-sync');
var concat = require('gulp-concat');
var del = require('del');
var path = require('path');

// Do we have a specifc extensions file?
try {
	var extensions = require('../../gulp-extensions.json');
} catch (err) {
	var extensions = config.extensions;
}

/**
 * 
 * @param {string} modName 
 * @param {string} modFolder 
 * @param {string} modBase 
 */
function releaseModule(modName, modFolder, modBase) {

	var baseTask = 'modules.frontend.' + modName;
	var extPath = './modules/' + modBase + '/' + modFolder;
	var wwwPath = config.wwwDir + '/modules/' + modFolder

	// Clean
	gulp.task('clean:' + baseTask,
		gulp.series(
			'clean:' + baseTask + ':module',
			'clean:' + baseTask + ':language'
		),
		function () {
		});

	// Clean: Module
	gulp.task('clean:' + baseTask + ':module', function () {
		return del(wwwPath, { force: true });
	});

	// Clean: Language
	gulp.task('clean:' + baseTask + ':language', function () {
		return del(config.wwwDir + '/language/**/*.mod_' + modName + '.*', { force: true });
	});

	// Copy: Module
	gulp.task('copy:' + baseTask,
		gulp.series(
			'copy:' + baseTask + ':module',
			'copy:' + baseTask + ':language'
		),
		function () {
		});

	// Copy: Module
	gulp.task('copy:' + baseTask + ':module', gulp.series('clean:' + baseTask + ':module'), function () {
		return gulp.src([
			extPath + '/**',
			'!' + extPath + '/language',
			'!' + extPath + '/language/**'
		])
			.pipe(gulp.dest(wwwPath));
	});

	// Copy: Language
	gulp.task('copy:' + baseTask + ':language', gulp.series('clean:' + baseTask + ':language'), function () {
		return gulp.src(extPath + '/language/**')
			.pipe(gulp.dest(config.wwwDir + '/language'));
	});

	// Watch
	gulp.task('watch:' + baseTask,
		gulp.series(
			'watch:' + baseTask + ':module',
			'watch:' + baseTask + ':language'
		),
		function () {
		});

	// Watch: Module
	gulp.task('watch:' + baseTask + ':module', function () {
		gulp.watch([
			extPath + '/**/*',
			'!' + extPath + 'language',
			'!' + extPath + 'language/**'
		],
			gulp.series('copy:' + baseTask + ':module', browserSync.reload));
	});

	// Watch: Language
	gulp.task('watch:' + baseTask + ':language', function () {
		gulp.watch([
			extPath + '/language/**'
		],
			gulp.series('copy:' + baseTask + ':language', browserSync.reload));
	});
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
		return gulp.series(tasks);
	}
}

// Clean
gulp.task('clean:modules.frontend',
	getModulesTasks('clean:modules', 'frontend'),
	function () {
		return true;
	});
gulp.task('clean:modules.backend',
	getModulesTasks('clean:modules', 'backend'),
	function () {
		return true;
	});

// Copy
gulp.task('copy:modules.frontend',
	getModulesTasks('copy:modules', 'frontend'),
	function () {
		return true;
	});
gulp.task('copy:modules.backend',
	getModulesTasks('copy:modules', 'backend'),
	function () {
		return true;
	});

// Watch
gulp.task('watch:modules.frontend',
	getModulesTasks('watch:modules', 'frontend'),
	function () {
		return true;
	});
gulp.task('watch:modules.backend',
	getModulesTasks('watch:modules', 'backend'),
	function () {
		return true;
	});
gulp.task('clean:modules',
	gulp.series('clean:modules.frontend', 'clean:modules.backend'),
	function () {
		return true
	});
gulp.task('copy:modules',
	gulp.series('copy:modules.frontend', 'copy:modules.backend'),
	function () {
		return true;
	});
gulp.task('watch:modules',
	gulp.series('watch:modules.frontend', 'watch:modules.backend'),
	function () {
		return true;
	});

exports.getModules = getModules;
exports.getModulesTasks = getModulesTasks;
exports.releaseModule = releaseModule;
