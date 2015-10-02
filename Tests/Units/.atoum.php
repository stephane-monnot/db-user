<?php

/*
This file will automatically be included before EACH run.

Use it to configure atoum or anything that needs to be done before EACH run.

More information on documentation:
[en] http://docs.atoum.org/en/chapter3.html#Configuration-files
[fr] http://docs.atoum.org/fr/chapter3.html#Fichier-de-configuration
*/

use \mageekguy\atoum;

$report = $script->addDefaultReport();

// This will add the atoum logo before each run.
//$report->addField(new atoum\report\fields\runner\atoum\logo());

// This will add a green or red logo after each run depending on its status.
$report->addField(new atoum\report\fields\runner\result\logo());


// Please replace in next line "Project Name" by your project name and "/path/to/destination/directory" by your destination directory path for html files.
if (!is_dir(__DIR__ . '/coverage')) {
    mkdir(__DIR__ . '/coverage');
}

/*
 * Html coverage
 */
$html = new atoum\report\fields\runner\coverage\html('DbUser', __DIR__ . '/coverage');
$report->addField($html);

/*
 * Clover coverage
 */
$cloverWriter = new \mageekguy\atoum\writers\file( __DIR__ . '/coverage/clover.xml');
$cloverReport = new atoum\reports\asynchronous\clover();
$cloverReport->addWriter($cloverWriter);

$runner->addReport($cloverReport);

// Chargement du fichier bootstrap
$runner->setBootstrapFile(dirname(__FILE__) . '/.bootstrap.atoum.php');
