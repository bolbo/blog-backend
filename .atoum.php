<?php
use mageekguy\atoum;
use mageekguy\atoum\report\fields\runner\failures\execute\unix;

define('CODE_COVERAGE_ROOT', __DIR__.DIRECTORY_SEPARATOR.'tests/coverage');

$report = $script->addDefaultReport();

if (!file_exists(CODE_COVERAGE_ROOT) && !@mkdir(CODE_COVERAGE_ROOT)) {
    die('Unable to create directory "'.CODE_COVERAGE_ROOT.'".');
}

$coverageField = new atoum\report\fields\runner\coverage\html(
    basename(__DIR__),
    CODE_COVERAGE_ROOT
);

$coverageField->setRootUrl('file://'.CODE_COVERAGE_ROOT);

$report->addField($coverageField);

$script->noCodeCoverageForNamespaces('atoum');

$runner->addTestsFromDirectory(__DIR__.'/tests');

$runner->setBootstrapFile(__DIR__.'/.bootstrap.php');


// Ouverture de phpstorm à la ligne qui a généré l'erreur
$stdOutWriter = new atoum\writers\std\out();
$cliReport    = new atoum\reports\realtime\cli();
$cliReport->addWriter($stdOutWriter);
$cliReport
    ->addField(
        new unix\phpstorm('/opt/phpstorm8/bin/phpstorm.sh')
    );
$runner->addReport($cliReport);

// http://www.jdecool.fr/blog/2014/08/19/visualiser-le-code-coverage-des-tests-atoum-dans-phpstorm.html
$cloverWriter = new atoum\writers\file(__DIR__.'/tests/atoum.clover.xml');
$cloverReport = new atoum\reports\asynchronous\clover();
$cloverReport->addWriter($cloverWriter);
$runner->addReport($cloverReport);