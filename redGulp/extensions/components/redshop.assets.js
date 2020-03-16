/**
 * Gulp components for redSHOP,
 * This is for developer mode only.
 * Don't ever use this on live site.
 *
 * Short Tag:
 * - gulp watch
 * - gulp copy
 *
 * Every task begin with <task name>:components.redshop
 *
 * There are 3 task types:
 * - clean
 * - copy
 * - watch
 *
 * We handle scripts by gulp pipe and uglify.
 * We handle css by sass and minify
 *
 * For more details:
 * - https://www.npmjs.com/package/gulp-watch
 * - http://sass-lang.com
 * - https://github.com/mishoo/UglifyJS
 */

/// Define gulp and its config
var gulp = require('gulp');
var config = require('./../../../gulp-config.json');

/// Define dependencies
var del = require('del');
var sass = require('gulp-sass');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var path = require("path");
var fs = require('fs');
var changed = require('gulp-changed');
var gutil = require('gulp-util');

/// Define component tasks
var componentName = 'com_redshop';
var baseMediaTask = 'components.redshop';

/// Define paths of source and destination
var extPath = '.';
var mediaPath = extPath + '/media/' + componentName;
var assetsPath = extPath + '/src/assets/' + componentName;
