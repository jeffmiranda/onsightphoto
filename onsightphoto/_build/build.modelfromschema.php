<?php
/**
* @package = CreateXpdoClasses
*
* Create Xpdo Classes script
*
* This script creates xPDO-ready classes from an existing custom
* schema. It only needs to be run once. It will also make a backup
* of classes if they already exist.
*
*/

include_once 'build.config.php';
include_once MODX_CORE_PATH . '/model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');

/* set this */
$myPackage = 'popupstudio';

// A few variables used to track execution times.
$mtime = microtime();
$mtime = explode(' ', $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
$time = time();

// A few definitions of files/folders:
$package_dir = OSP_CORE_PATH . 'components/' . $myPackage . '/';
$model_dir = OSP_CORE_PATH . 'components/' . $myPackage . '/model/';
$class_dir = OSP_CORE_PATH . 'components/' . $myPackage . '/model/' . $myPackage;

// If the directories don't exist create them
if (! file_exists($package_dir)) {
    mkdir($package_dir,0777);
}
if (! file_exists($model_dir)) {
    mkdir($model_dir,0777);
}

// Set the log level to print to the screen
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

// Start the process of reading the schema
$manager= $modx->getManager();
$generator= $manager->getGenerator();

// Define the schema file source
$file = $model_dir . 'schema/' . $myPackage . '.mysql.schema.xml';

// If the classes already exist, rename the directory
if (is_dir($class_dir)) {
	$rename = $class_dir . '-' . $time;
	$modx->log(modX::LOG_LEVEL_INFO,
		'<br>The old class dir: <br><code>' . $class_dir . '</code> <br>has been renamed to <br><code>' . $rename . '</code>.');
	rename($class_dir, $rename);
}

// Parse the schema and generate the class files
if ($generator->parseSchema($file, $model_dir)) {
	$modx->log(modX::LOG_LEVEL_INFO,
		'Schema file parsed -- Files written to '. $model_dir);
} else {
	$modx->log(modX::LOG_LEVEL_INFO,
		'Error parsing schema file');
}

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tend = $mtime;
$totalTime = ($tend - $tstart);
$totalTime = sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO, '<br><br><strong>Finished!</strong> Execution time: ' . $totalTime . '<br>');

exit();