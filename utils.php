<?php

include 'config.php';

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
