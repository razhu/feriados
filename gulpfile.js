var gulp    = require('gulp');
var connect = require('gulp-connect-php');
var minify  = require('gulp-minify-css');
var rename  = require('gulp-rename');
var notify  = require('gulp-notify');

gulp.task('styles', function() {
  return gulp.src('src/css/main.scss')
    .pipe(sass({ style: 'expanded', }))
    .pipe(gulp.dest('public/css'))
    .pipe(rename({ suffix: '.min' }))
    .pipe(minifycss())
    .pipe(gulp.dest('public/css'))
    .pipe(notify({ message: 'Styles task complete' }));
});

gulp.task('server', function() {

    gulp.watch('public/css/*.scss', ['styles']);

    connect.server();
});
