<?php
/*
This file will automatically be included before EACH test if -bf/--bootstrap-file argument is not used.
Use it to initialize the tested code, add autoloader, require mandatory file, or anything that needs to be done before EACH test.
More information on documentation:
[en] http://docs.atoum.org/en/chapter3.html#Bootstrap-file
[fr] http://docs.atoum.org/fr/chapter3.html#Fichier-de-bootstrap
*/
set_include_path(
    get_include_path()
    . PATH_SEPARATOR . realpath(__DIR__ . '/../../')
);
require 'vendor/autoload.php';
// Temp dir for tests
define('TEST_TMP_DIR', __DIR__ . '/tmp');
if (!is_dir(TEST_TMP_DIR)) {
    mkdir(TEST_TMP_DIR);
}
