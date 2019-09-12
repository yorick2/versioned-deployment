<?php
/**
 * useful for debugging phpunit with xdebug, as using command line xdebug over docker is not easy and often requires changing your editor settings. Note: view source when running this
 */

echo <<<EOF
------------------------------------------
Clearing caches
------------------------------------------
Running command:
cd /var/www/html && php artisan config:clear && php artisan cache:clear && composer dump-autoload
------------------------------------------

EOF;
echo strval(`cd /var/www/html &&
php artisan config:clear &&
php artisan cache:clear &&
composer dump-autoload`);

chdir('/var/www/html');
$argv[] = 'vendor/bin/phpunit';
$argv[] = '--stop-on-error';
$argv[] = '/var/www/html/tests/phpunit/Unit/DeploymentTest.php';
$argv[] = '--filter=testGitDiff';
$argc = count($argv);
$_SERVER['argv'] = $argv;
$_SERVER['argc'] = $argc;
$_ENV['APP_RUNNING_IN_CONSOLE'] = 'true'; // laravel throws 'CSRF token mismatch' errors if we dont have this set and we send a post request

$command = implode($argv,' ');
echo <<<EOF

------------------------------------------
Phpunit
------------------------------------------
Running command:
$command
------------------------------------------

EOF;

require '/var/www/html/vendor/bin/phpunit';
