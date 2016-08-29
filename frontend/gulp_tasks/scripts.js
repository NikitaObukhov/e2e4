const gulp = require('gulp');
const eslint = require('gulp-eslint');
const replace = require('gulp-replace');
const gutil = require('gulp-util');

const conf = require('../conf/gulp.conf');

gulp.task('scripts', scripts);

function scripts() {
  gutil.log(gutil.colors.green('Switched to backend: '+process.env.E2E4_BACKEND_BASE_URL+process.env.E2E4_BACKEND_BASE_PATH));
  gulp.src(conf.path.src('index.js'))
    .pipe(replace(/\$\{ENV_BACKEND_BASE_URL\}/g, process.env.E2E4_BACKEND_BASE_URL))
    .pipe(replace(/\$\{ENV_BACKEND_BASE_PATH\}/g, process.env.E2E4_BACKEND_BASE_PATH))
    .pipe(gulp.dest(conf.path.tmp()));

  return gulp.src([conf.path.src('**/*.js'), '!'+conf.path.src('index.js')])
    .pipe(eslint())
    .pipe(eslint.format())

    .pipe(gulp.dest(conf.path.tmp()));
}
