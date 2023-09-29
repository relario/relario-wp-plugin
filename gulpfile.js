'use strict';

const files = {
	sass: {
		src: ['./assets/**/css/*.css', '!./assets/**/css/**/*.min.css'],
		watch: ['./assets/**/css/*.css', '!./assets/**/css/**/*.min.css'],
	},
	js: {
		src: ['assets/**/js/*.js', '!assets/**/js/*.min.js'],
		watch: ['assets/**/js/*.js', '!assets/**/js/*.min.js'],
	}
}

const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const uglify = require('gulp-uglify');
const rename = require("gulp-rename");
const babel = require('gulp-babel');
const log  = require('fancy-log');
// CSS
gulp.task('sass', function () {
	return gulp.src(files.sass.src)
		// .pipe(sourcemaps.init())
		.pipe(sass({errLogToConsole: true, outputStyle: 'compressed'}))
		.pipe(rename({suffix: '.min'}))
		// .pipe(sourcemaps.write('./'))
		.pipe(gulp.dest('./assets'))
});

// JS
gulp.task('js', function () {
	return gulp.src(files.js.src)
		.pipe(babel({
			presets: ['@babel/env']
		}))
		.pipe(uglify())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest('./assets/'))
});


gulp.task('watch:sass', function () {
	gulp.watch(files.sass.watch, gulp.series('sass'));
});
gulp.task('watch:js', function () {
	gulp.watch(files.js.watch, gulp.series('js'));
});

gulp.task('watch', gulp.parallel('sass', 'js', 'watch:sass', 'watch:js'));
