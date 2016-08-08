var jgulp = require('joomla-gulp-release');

var jgulp = require('gulp');
var sass = require('gulp-sass');
var del = require("del");

jgulp.task('sass', function() {
	jgulp.src('./media/com_redshop/scss/style.scss')
		.pipe(sass({
			outputStyle: 'compressed',
			errLogToConsole: true
		}))
		.pipe(jgulp.dest('./media/com_redshop/css'));
});

jgulp.task("clean", function () {
	return del("build");
});

jgulp.task("build", ["clean", "sass"], function () {
	jgulp.src(
	[
		'./media/**/*',
		'./libraries/**/*',
		'./plugins/**/*'
	],{ base: './' })
	.pipe(jgulp.dest(config.buildDir));

	// Copy module
	jgulp.src(
	[
		'./modules/site/**/*',
	])
	.pipe(jgulp.dest(config.buildDir + '/modules'));

	// Copy site
	jgulp.src(
	[
		'./component/site/**/*',
	])
	.pipe(jgulp.dest(config.buildDir + '/components/com_redshop'));

	// Copy admin
	jgulp.src(
	[
		'./component/admin/**/*',
	])
	.pipe(jgulp.dest(config.buildDir + '/administrator/components/com_redshop'));

	jgulp.src(
	[
		'./install.php',
		'./redshop.xml'
	])
	.pipe(jgulp.dest(config.buildDir + '/administrator/components/com_redshop'));
});

jgulp.task(
	'release',
	[
		'sass',
		'release:component',
		'release:modules',
		'release:plugins',
		'release:packages'
	],
	function() {
});

