'use strict';

let gulp = require('gulp');
let sass = require('gulp-sass');

sass.compiler = require('node-sass');

gulp.task('sass', function () {
	return gulp.src('./sass/style.scss')
		.pipe(sass.sync().on('error', sass.logError))
		.pipe(gulp.dest('./'));
});

// exports.default = gulp.series(sass);