var gulp = require('gulp');
var phpcs = require('gulp-phpcs');
var phplint = require('gulp-phplint');
var phpmd = require('gulp-phpmd');
// var phpmd = require('gulp-phpmd-plugin');

gulp.task('phpcs', function () {
  return gulp.src([
    'public/index.php',
    'src/app/**/*.php',
    'src/config/*.php',
    'src/routes/*.php',
    '!src/database/*.php',
    '!src/index.php',
    '!vendor/**/*.*',
    '!node_modules/**/*.*'
  ])

  // Validate files using PHP Code Sniffer
    .pipe(phpcs({
       bin: 'vendor/bin/phpcs',
      // standard: 'phpcs.xml',
      standard: 'PSR2',
      encoding: 'utf-8',
      warningSeverity: 0,
      // showSniffCode: 1,
      colors: 1
  }))

  // Log all problems that was found
    .pipe(phpcs.reporter('log'))
    .pipe(phpcs.reporter('file', { path: ".logs/code_sniffer_error.txt" }));
  });




gulp.task('phplint', function () {
  return gulp.src(['src/**/*.php'])
    .pipe(phplint('', { /*opts*/ }))
    .pipe(phplint.reporter(function(file){
      var report = file.phplintReport || {};
      if (report.error) {
        console.error(report.message+' on line '+report.line+' of '+report.filename);
      }
    }));
});


gulp.task('phpmd', function () {
  return gulp.src(['src/**/*.php', '!src/vendor/**/*.*'])
    // Validate code using PHP Mess Detector
    .pipe(phpmd({
      bin: 'vendor/bin/phpmd',
      // format: 'json',
      ruleset: 'unset',
    }))

    // Log all problems that was found
    // .pipe(phpmd.reporter('log'))
    // Fail if there is an error
    // .pipe(phpmd.reporter('fail'))
    .on('error', console.error)
});

// Sass CSS e JS: Executa tudo em tempo real quando há alteração nos arquivos  em development
gulp.task('watch', gulp.series( function() {
  gulp.watch([
    'public/index.php',
    'src/**/*.php',
    'src/config/*.php',
    'src/routes/*.php'
  ], gulp.parallel( ['phpcs', 'phplint']));
}));


/**
* Defautl:
*
* 1 - Executar sempre quando há alterações em arquivos .php
*
*/
gulp.task('default', gulp.series( ['watch'] ));


// https://javascript.hotexamples.com/examples/gulp-phpcs/-/default/javascript-default-function-examples.html
