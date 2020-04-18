<?php

#include 'xml.php';

function parseMD($string){
	$ret = $string;
	/* <code> */
	$ret = preg_replace('/`([^`]+)`/ms', '<code>${1}</code>', $ret);
	return $ret;
}

?>
