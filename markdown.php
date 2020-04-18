<?php

#include 'xml.php';

function parseMD($string){
	$ret = $string;
	/* <code> */
	$ret = preg_replace('/`([^`]*)`/ms', mkXMLtag('code','${1}'), $ret);
}

?>
