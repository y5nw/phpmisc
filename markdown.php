<?php

include 'config.php';
include 'html.php';

// Since this function is called by mkXML*, it is a bad idea to use them.
function parseMD($string){
	$mdconfig = PHPMISC_CONFIG['content']['markdown'];
	if (!$mdconfig['enabled']) return $string;
	$ret = $string;
	/* <code> */
	if ($mdconfig['code']) $ret = preg_replace('/`([^`]+)`/ms', '<code>${1}</code>', $ret);
	/* <h1> .. <h6> */
	if ($mdconfig['headings']) for ($i = 1; $i < 7; $i++){
		$ret = preg_replace('/^'.str_repeat('#', $i).' +(.*)$/', '<h'.$i.'>${1}</h'.$i.'>', $ret);
	}
	/* <img>  and <a> */
	if ($mdconfig['images']){
		$ret = preg_replace('/!\[([^\]]*)\]\(([^\)]*)\)/', '<img src="${2}" alt="${1}"></img>', $ret);
		$ret = preg_replace('/\[([^\]]*)\]\(([^\)]*)\)/', '<a href="${2}">$1</a>', $ret);
	}
	/* Extra: octicons */
	if ($mdconfig['octicons']){
		$trdata = [];
		foreach (PHPMISC_OCTICONS_DATA as $k => $v){
			$trdata['::o::'.$k.'::'] = octicons($k);
		}
		$ret = strtr($ret, $trdata);
	}
	return $ret;
}

?>
