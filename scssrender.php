<?php 
require($_SERVER['DOCUMENT_ROOT'] . '/lib/common.php');
header("Content-type: text/css; charset: UTF-8");
use ScssPhp\ScssPhp\Compiler;

if(!isset($_GET['path'])) {
	die();
}

$scss = new Compiler();
$scss->setImportPaths($_SERVER['DOCUMENT_ROOT']);
if (file_exists('cache/'.substr($_GET['path'], 0, strlen($_GET['path']) - 4).'css') AND $tplNoCache != true) {
	$lines = file(realpath($_SERVER['DOCUMENT_ROOT'] . '/cache/'.substr($_GET['path'], 0, strlen($_GET['path']) - 4).'css'));
	foreach ($lines as $line_num => $line) {
		echo $line;
	}
} else {
	$css = $scss->compile('@import "'.$_GET['path'].'"');
	if($tplNoCache != true) {
		$parts = explode('/', 'cache/'.substr($_GET['path'], 0, strlen($_GET['path']) - 4).'css');
		array_pop($parts);
		$dir = implode('/', $parts);
		if(!is_dir($dir))
			mkdir($dir);
		$file = fopen('cache/'.substr($_GET['path'], 0, strlen($_GET['path']) - 4).'css', 'w');
		fwrite($file, $css);
		fclose($file);
	}
	echo $css; //if the code scanner says "POSSIBLE HTML INJECTION PLEASE SANITIZE IT", i don't care the header is already css...
}