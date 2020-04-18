<?php

include 'config.php';

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
		$octicons_data = [];
		$octicons_data = json_decode(catfile(__DIR__.'/octicons.json'), true);
		foreach ($octicons_data as $k => $v){
			$ret = preg_replace('/::o::'.$k.'::/', octicons($k), $ret);
		}
	}
	return $ret;
}

?>
