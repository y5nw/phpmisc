<?php

// Since this function is called by mkXML*, it is a bad idea to use them.
function parseMD($string){
	$ret = $string;
	/* <code> */
	$ret = preg_replace('/`([^`]+)`/ms', '<code>${1}</code>', $ret);
	return $ret;
}

?>
