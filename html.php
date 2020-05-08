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
	return ['a',[ $name.' &lt;', ['code',$email], '&gt;'], ['href' => "mailto:{$email}"]];
}

// Make the <head>...</head> data
function mkHTMLhead($title){
	return ['head', [
		['title',$title.' - '.PHPMISC_CONFIG['content']['title']],
		['style', shell_exec('sed -z "s/[\r\n\t]//g;s/\([:,]\) \\+/\\1/g" '.__DIR__.'/style.css')],
	]];
}

// Generate header
function mkHTMLheader(){
	$ret = ['Navigation '];
	foreach(PHPMISC_CONFIG['content']['navigation'] as $t => $l){
		$ret[count($ret)] = ' | ';
		$ret[count($ret)] = ['a', $t, ['href' => $l]];
	}
	$ret[count($ret)] = '<hr/>';
	return ['header',$ret];
}

// Generate footer
function mkHTMLfooter(){
	return ['footer',[
		'<hr/>',
		(PHPMISC_CONFIG['content']['octicons']?'This site uses icons from <a href="https://octicons.github.com/">Octicons</a>.<br/>':''),
		'This page is generated using '.mkXMLtag('a', PHPMISC_CONFIG['source']['name'], ['href' => PHPMISC_CONFIG['source']['repo']]).'.',
	]];
}

// Generate the whole page
function mkHTMLpage($title = '', $main = [], $parsemd = false){
	return '<!DOCTYPE html>'.mkXMLtag('html',[
		mkHTMLhead($title),
		['body',[['div',[
			mkHTMLheader(),
			['main', $main, []],
			mkHTMLfooter(),
		], ['id'=>'main_container']]]]
	],[], $parsemd);
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
		return mkXMLtag('svg', $dat['path'],[
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
