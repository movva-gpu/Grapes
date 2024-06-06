import fs from 'node:fs';
import { dest, parallel, series, src, task } from 'gulp';
import rename from 'gulp-rename';
import header from 'gulp-header';

import uglify from 'gulp-uglify';
import babel from 'gulp-babel';

import postcss from 'gulp-postcss';
import cssnano from 'cssnano';
import autoprefixer from 'autoprefixer';
import postcssNested from 'postcss-nested';

const postcss_plugins = [postcssNested(), autoprefixer(), cssnano()];

task('clean', cb => {
    fs.accessSync('.');
    if (!fs.existsSync('./dist')) cb();
    else {
        try {
            fs.rmdirSync('./dist');
            cb();
        } catch (err) {
            cb(err);
        }
    }
});

task('minifyCSS', () => {
    return src('./assets/css/**/*.css')
        .pipe(postcss(postcss_plugins))
        .pipe(header('/* minified with gulp.js\r\n' + ' * all rights reserved.\r\n' + ' */'))
        .pipe(rename({ extname: 'min.css' }))
        .pipe(dest('./dist/css'));
});

task('minifyJS', () => {
    return src('./assets/js/**/*.js')
        .pipe(babel())
        .pipe(uglify())
        .pipe(header('/* minified with gulp.js\r\n' + ' * all rights reserved.\r\n' + ' */'))
        .pipe(rename({ extname: 'min.js' }))
        .pipe(dest('./dist/js'));
});

task('default', series('clean', parallel('minifyJS', 'minifyCSS')));
