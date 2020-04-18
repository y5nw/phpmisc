<?php

include_once 'config.php';
include_once 'xml.php';
include_once 'utils.php';
include_once 'octicons.php';
if (!defined('PHPMISC_OCTICONS_DATA')) define('PHPMISC_OCTICONS_DATA', []);

if (defined('PHPMISC_HTML')) return;
define('PHPMISC_HTML', true);

// Make a mailto: link
function mailto($name,$email){
	return(mkXMLtag('a', $name . ' &lt;' . mkXMLtag('code',$email) . '&gt;', ['href' => "mailto:{$email}"]));
}

// Make the <head>...</head> data
function mkhead($title){
	echo mkXMLtag('head', [
		['title',$title.' - '.PHPMISC_CONFIG['content']['title']],
		['style', shell_exec('sed -z "s/[\r\n\t]//g;s/\([:,]\) \\+/\\1/g" '.__DIR__.'/style.css')],
	]);
}

// Generate header
function mkheader(){
	echo mkXMLtag('header',[
		'Navigation: ',
		['a', 'Home', ['href' => '/']],
	]);
}

// Generate footer
function mkfooter(){
	echo mkXMLtag('footer',[
		(PHPMISC_CONFIG['content']['octicons']?'This site uses icons from <a href="https://octicons.github.com/">Octicons</a>.<br/>':''),
		'This page is generated using '.mkXMLtag('a', PHPMISC_CONFIG['source']['name'], ['href' => PHPMISC_CONFIG['source']['repo']]).'.',
	]);
}

// Generate the whole page
function mkHTMLpage($title, $main, $parsemd = false){
	echo '<!DOCTYPE html><html>';
	mkhead($title);
	echo '<body>';
	mkheader();
	echo mkXMLtag('main',$main, array(), $parsemd);
	mkfooter();
	echo '</body></html>';
}

function octicons($name, $cls='octicons'){
	if (!PHPMISC_CONFIG['content']['octicons']) return '';
	if (array_key_exists($name, PHPMISC_OCTICONS_DATA)){
		$dat = PHPMISC_OCTICONS_DATA[$name];
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
