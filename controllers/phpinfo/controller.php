<?php /** For debugging purposes only. */

if (PHP_OS !== 'Linux') $index = phpinfo();
else $index = function() { echo 'Not today pal.'; };
