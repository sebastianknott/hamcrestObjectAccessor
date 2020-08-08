<?php

declare(strict_types=1);

namespace Deployer;

require_once __DIR__ . '/vendor/sebastianknott/dev-utils/deploy.php';

// Project name
set('application', 'sebastianknot/hamrestObjectAccessor');
set('release_path', __DIR__);
set('allow_anonymous_stats', false);

desc('Check for source compatibility to PHP 7.2');
task('sca:phpcs:compat7.2', 'vendor/bin/phpcs --standard=PHPCompatibility --runtime-set testVersion 7.2 src');

desc('Check for messy code with vimeos psalm');
task(
    'sca:psalm',
    static function () {
        run('vendor/bin/psalm --no-progress -c config/psalm/psalm.xml ');
    }
);

desc('Check for messy code with phpstan');
task(
    'sca:phpstan',
    static function () {
        run(
            'vendor/bin/phpstan analyse --no-progress --configuration=config/phpstan/phpstan.neon src test'
        );
    }
);

task(
    'sca',
    ['sca:lint', 'sca:phpcs', 'sca:phpcs:compat7.2', 'sca:phpstan', 'sca:psalm', 'sca:phpmd', 'sca:phpcpd']
)->once();
