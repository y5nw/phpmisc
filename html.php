<?php

include_once 'config.php';
include_once 'xml.php';
include_once 'utils.php';
include_once 'octicons.php';
if (empty($PHPMISC_OCTICONS_DATA)) $PHPMISC_OCTICONS_DATA=[];

if (defined('PHPMISC_HTML')) return;
define('PHPMISC_HTML', true);

// Make a mailto: link
function mailto($name,$email){
	return mkXML('a', ["${name} ", mkXML('code', '&lt;'.$email.'&gt;')], ['href' => "mailto:{$email}"]);
}

// Make the <head>...</head> data
function mkHTMLhead($title){
	return mkXML('head')
		->addobj('title', $title.' - '.PHPMISC_CONFIG['content']['title'])
		->addobj('style', preg_replace('/\s+/ms', ' ', catfile(__DIR__.'style.css')));
}

// Generate header
function mkHTMLheader($parsemd = false){
	$ret = mkXML('header', 'Navigation', [], $parsemd);
	foreach(PHPMISC_CONFIG['content']['navigation'] as $t => $l) $ret->addcontent(' | ', mkXML('a', $t, ['href' => $l]));
	return $ret->addcontent('<hr/>');
}

// Generate footer
function mkHTMLfooter($parsemd = false){
	$ret = mkXML('footer', ['<hr/>'], [], $parsemd);
	if (PHPMISC_CONFIG['content']['octicons']) $ret->addcontent('This site uses icons from <a href="https://octicons.github.com/">Octicons</a>.<br/>');
	return $ret->addcontent('This page is generated using '.mkXML('a', PHPMISC_CONFIG['source']['name'], ['href' => PHPMISC_CONFIG['source']['repo']]).'.');
}

// Generate the whole page
function mkHTMLpage($title = '', $main = [], $parsemd = false){
	return '<!DOCTYPE html>'.mkXML('html', [
		mkHTMLhead($title),
		mkXML('body', [mkXML('div', [], [], $parsemd)
			->addcontent(mkHTMLheader($parsemd))
			->addobj('main', $main, [], $parsemd)
			->addfooter(mkHTMLfooter($parsemd))
		], [], $parsemd),
	], [], $parsemd);
}

// Display different pages based on the 'p' variable from the URL
function getPageByURL($pages, $parsemd){
	$p = $pages[$_GET['p']??'main'];
	return mkHTMLpage($p[0],$p[1],$parsemd);
}

// Octicons
function octicons($name, $cls='octicons'){
	if (!PHPMISC_CONFIG['content']['octicons']) return '';
	global $PHPMISC_OCTICONS_DATA;
	if (array_key_exists($name, $PHPMISC_OCTICONS_DATA)){
		$dat = $PHPMISC_OCTICONS_DATA[$name];
		return mkXML('svg', $dat['path'],[
			'xmlns' => 'http://www.w3.org/2000/svg',
			'class' => $cls,
			'width' => $dat['width'],
			'height' => $dat['height'],
			'viewBox' => "0 0 {$dat['width']} {$dat['height']}",
			'preserveAspectRatio' => 'none',
		]).' ';
	}else{
		return '';
	};
}

?>
