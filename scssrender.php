<?php 
require($_SERVER['DOCUMENT_ROOT'] . '/lib/common.php');
header("Content-type: text/css; charset: UTF-8");
use ScssPhp\ScssPhp\Compiler;

if(!isset($_GET['path'])) {
	die();
}

$scss = new Compiler();
$scss->setImportPaths($_SERVER['DOCUMENT_ROOT']);
echo $scss->compile('@import "'.$_GET['path'].'"'); //it would be cool if this was cached