<?php

// Since this function is called by mkXML*, it is a bad idea to use them.
function parseMD($string){
	$ret = $string;
	/* <code> */
	$ret = preg_replace('/`([^`]+)`/ms', '<code>${1}</code>', $ret);
	/* <h1> .. <h6> */
	for ($i = 1; $i < 7; $i++){
		$ret = preg_replace('/^'.str_repeat('#', $i).' +(.*)$', '<h'.$i.'>${1}</h'.$i.'>');
	}
	return $ret;
}

?>
