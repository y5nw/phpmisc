<?php

include 'config.php';

if (defined('PHPMISC_UTILS')) return;
define('PHPMISC_UTILS', true);

function catfile($fn){
	try{
		$fh = fopen($fn);
		$s = fread($fh, filesize($fn), );
		fclose($fh);
		return $s;
	} catch(Exception $e){
		throw $e;
	};
}

?>
