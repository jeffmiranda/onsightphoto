<?php

/* Performance testing of GraphicMagick library
 * Objective is to determine how fast thumbnail generation 
 * is on 2000 images.
 */

ini_set('max_execution_time', 0);

try {
	
	$numberOfImages = 10;
		
	error_log('Begin processing ' . $numberOfImages . ' thumbnail images.');
	$thumbDir = dirname(__FILE__) . '/thumbs';
	if (!is_dir($thumbDir)) {
		mkdir($thumbDir);         
	}
	
	// get all the JPEG files in a directory
	$fileList = glob(dirname(__FILE__) . '/images/' . $numberOfImages . '/*.jpg');
	$time_start = microtime(true);
	foreach ($fileList as $file) {
		
		// initialize object
		$image = new Gmagick();
		
		// for each file
		// generate thumbnail
		// write thumbnail to disk
		$image->readImage($file);
		//$image->stripImage();
		//$image->setImageResolution(72, 72);
		//$image->setSize(175, 175);		
		$image->thumbnailImage(175, 175);
		$thumbFile = $thumbDir . '/' . basename($file, '.jpg') . '.thumb.jpg';
		$image->writeImage($thumbFile);
		// free resource handle
		$image->destroy();
	}
	
	$time_end = microtime(true);
	$time = $time_end - $time_start;
	error_log('End processing ' . $numberOfImages . ' thumbnail images. (' . $time . ' seconds)');

} catch (Exception $e) {
	die ($e->getMessage());
}

?>