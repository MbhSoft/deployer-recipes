<?php

env('web_dir', '.');

set('reset_script_content', '
<?php

if (function_exists("opcache_reset")) {
    opcache_reset();
}
@unlink(__FILE__);
echo "success";
');

function getResetScriptFileName() {
    $factory = new \RandomLib\Factory;
    $generator = $factory->getMediumStrengthGenerator();
    $scriptIdentifier = $generator->generateString(32, \RandomLib\Generator::CHAR_ALNUM);
    // store the $scriptIdentifier
    env('opcache_reset_scriptIdentifier', $scriptIdentifier);

    $scriptFilename = 'surf-opcache-reset-' . $scriptIdentifier . '.php';
    return $scriptFilename;
}

task('opcache:reset:create_script', function() {
    run("file_put_contents({{web_dir}}/" . getResetScriptFileName() . ",". str_replace('"', "\"", get('reset_script_content')) . ")");
})->desc('create opcache reset script');

task('local:opcache:reset:create_script', function() {
    runLocally("file_put_contents({{web_dir}}/" . getResetScriptFileName() . ",". str_replace('"', "\"", get('reset_script_content')) . ")");
})->desc('create opcache reset script locally');

task('opcache:reset:execute', function() {
    run("curl -sS {{public_url}}/surf-opcache-reset-{{opcache_reset_scriptIdentifier}}.php");
})->desc('execute opcache reset script');