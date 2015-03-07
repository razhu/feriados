var gulp    = require('gulp');
var connect = require('gulp-connect-php');
var minify  = require('gulp-minify-css');
var notify  = require('gulp-notify');
var rename  = require('gulp-rename');
var sync    = require('browser-sync');

gulp.task('styles', function() {
  return gulp.src('public/css/styles.css')
    .pipe(rename({ suffix: '.min' }))
    .pipe(minify())
    .pipe(gulp.dest('public/css'))
    .pipe(notify({ message: 'Styles task complete' }));
});

gulp.task('server', function() {

    connect.server({ base: './public' }, function() {
      sync({ proxy: 'localhost:8000' });
    });

    gulp.watch('public/css/styles.css', ['styles']);

    gulp.watch('**/*.php').on('change', function() {
      sync.reload();
    });
});
