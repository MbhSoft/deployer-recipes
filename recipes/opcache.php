<?php

env('web_dir', '.');
env('public_url', '');

set(
    'reset_script_content',
    '<?php if (function_exists(\'opcache_reset\')) { opcache_reset(); @unlink(__FILE__); echo \'success\'; }'
);

function getResetScriptFileName()
{
    $factory = new \RandomLib\Factory;
    $generator = $factory->getMediumStrengthGenerator();
    $scriptIdentifier = $generator->generateString(32, \RandomLib\Generator::CHAR_ALNUM);
    // store the $scriptIdentifier
    env('opcache_reset_scriptIdentifier', $scriptIdentifier);

    $scriptFilename = 'deployer-opcache-reset-' . $scriptIdentifier . '.php';
    return $scriptFilename;
}

task('opcache:reset:create_script', function() {
    run("echo \"" . get('reset_script_content') . "\" > {{current}}/{{web_dir}}/" . getResetScriptFileName());
})->desc('create opcache reset script');

task('local:opcache:reset:create_script', function() {
    runLocally("echo \"" . get('reset_script_content') . "\" > {{web_dir}}/" . getResetScriptFileName());
})->desc('create opcache reset script locally');

task('opcache:reset:execute', function() {
    run("curl -sS --fail {{public_url}}/deployer-opcache-reset-{{opcache_reset_scriptIdentifier}}.php");
})->desc('execute opcache reset script');

task('local:opcache:reset:execute', function() {
    runLocally("curl --fail -sS {{public_url}}/deployer-opcache-reset-{{opcache_reset_scriptIdentifier}}.php");
})->desc('execute opcache reset script locally');

task('opcache:reset:remove_script', function() {
    run("rm {{current}}/{{web_dir}}/deployer-opcache-reset-{{opcache_reset_scriptIdentifier}}.php");
})->desc('remove opcache reset script');

task('local:opcache:reset:remove_script', function() {
    runLocally("rm {{web_dir}}/deployer-opcache-reset-{{opcache_reset_scriptIdentifier}}.php");
})->desc('remove opcache reset script locally');
