var gulp = require('gulp'),

// ZIP compress files
zip = require('gulp-zip'),

// Utility functions for gulp plugins
gutil = require('gulp-util')
//notify = require("gulp-notify"),

// File systems
fs          = require('fs'),
path        = require('path'),
merge       = require('merge-stream'),
parseString = require('xml2js').parseString,

// Gulp Configuration
config = require('./gulp-config.json')

// Extension Configuration file
extensionConfig = require('./package.json')

// Init manifest file JSON Object
manifest = {},

// Init component version - Set default as config version
redshop = {"version":'',"compatibility":'',"pluginVersion":''};

// Reading manifest file
readManifest('./redshop.xml');

// Update version from manifest file
redshop.version = manifest.extension.version[0];

function getFolders(dir) {
	return fs.readdirSync(dir)
		.filter(function(file) {
			return fs.statSync(path.join(dir, file)).isDirectory();
	});
}

function showHelp(){
	gutil.log(
		gutil.colors.white.bold.bgMagenta(
			'\n\n\nFollowing tasks and switches are available:\n\n\t 1. gulp release:component \n\t\t Use this command to release component. Version and other information can be set in gulp-config.json file. \n\n\t 2. gulp release:extensions \n\t\t This command is to release the extensions.\n\t\t This command will read the base directory and create zip files for each of the folder. \n\t\t === Switches === \n\t --folder {source direcory}  Default: "./plugins" \n\t --suffix {text of suffix}   Default: "plg_"\n\n\t Example Usage: \n\t\t gulp release:extensions --folder ./modules --suffix ext_ \n\n\n'
		)
	);
}

function readManifest(xml){
	return parseString(fs.readFileSync(xml, 'ascii'), function (err, result) {
		manifest = result;
	});
}

// Creating zip files for  Extensions
gulp.task('release:extensions', function() {

	// Source directory for read and prepare for zip
	var srcFolder = gutil.env.folder || './plugins',

	// Read all the folders in given source directory
	folders = getFolders(srcFolder),

	// Extension package name suffix
	extSuffix = gutil.env.suffix || '';

	// Display log
	gutil.log(gutil.colors.white.bgBlue(folders.length) + gutil.colors.blue.bold(' extensions are ready for release'));

	// Loop through the folders and create zip files for each of them.
	var tasks;

	folders.map(function(folder) {

		var plugins = getFolders(path.join(srcFolder, folder));

		// Display name of the folder
		gutil.log(gutil.colors.blue.bold.italic(folder + ' (' + plugins.length + ')'));

		tasks = plugins.map(function(plugin) {

			var pluginBasePath = path.join(srcFolder, folder, plugin);

			// Reading manifest file
			readManifest(path.join(pluginBasePath, plugin + '.xml'));

			// Update version from manifest file
			redshop.pluginVersion = manifest.extension.version[0];
			redshop.compatibility = (manifest.extension.redshop) ? '_for_redSHOP_' + manifest.extension.redshop[0] : '';

			// Strip group name for modules
			var extGroupName = ('site' == folder) ? '' : folder + '_',
			destFolderName = 'redshop-' + redshop.version + '-' + srcFolder.split(path.sep)[1];

			// Print current extension name
			gutil.log(gutil.colors.red.bold.italic('-' + plugin + ' v' + redshop.pluginVersion));

			return gulp.src(
					path.join(pluginBasePath, '**')
				)
				.pipe(
					zip(
						extSuffix + extGroupName + plugin + '_' + redshop.pluginVersion + redshop.compatibility + '.zip'
					)
				)
				.pipe(
					gulp.dest(
						path.join(config.releasesDir, destFolderName, folder)
					)
				);
		});
	});

	return merge(tasks);
});

// Creating zip files for Component
gulp.task('release:component', function() {

	if (!config.packageFiles || (config.packageFiles && config.packageFiles.length <= 0))
	{
		gutil.log(
			gutil.colors.white.bgRed(
				'ERROR: Please specify `packageFiles` in gulp-config.json or make sure you have added files list'
			)
		);

		return false;
	}

	// Start up log
	gutil.log(gutil.colors.white.bgGreen('Preparing release for version' + redshop.version));

	gulp.src(config.packageFiles, {base: '.'})
		.pipe(zip(config.name + 'v' + redshop.version + '_' + config.joomlaVersion + '.zip'))
		.pipe(gulp.dest(config.releasesDir));

	gutil.log(gutil.colors.white.bgGreen('Component packages are ready at ' + config.releasesDir));
});

gulp.task(
	'release',
	[
		'release:component',
		'release:extensions'
	],
	function() {

});

gulp.task('default', function() {
	showHelp();
});
