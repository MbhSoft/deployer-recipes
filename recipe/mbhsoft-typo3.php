<?php
namespace Deployer;

// default path
set('bin/typo3cms', 'bin/typo3cms');

task('typo3cms:cache:flush', function() {
    run("cd {{current_path}} && {{bin/typo3cms}} cache:flush");
})->desc('flush caches');

task('typo3cms:cache:flush_forced', function() {
    run("cd {{current_path}} && {{bin/typo3cms}} cache:flush --force");
})->desc('flush caches forced');

task('typo3cms:database:updateschema', function() {
    run("cd {{current_path}} && {{bin/typo3cms}} database:updateschema \"*.add,*.change\"");
})->desc('update database schema');

task('typo3cms:install:generatepackagestates', function() {
    run("cd {{current_path}} && {{bin/typo3cms}} install:generatepackagestates");
})->desc('generate packagestates file');
