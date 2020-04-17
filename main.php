<?php

include 'config.php';

// Make a opening XML tag
function mkXMLopen($tag, $attrs = []){
	$ret = "<{$tag}";
	if(gettype($attrs) == 'array'){
		foreach($attrs as $attr => $val){
			$ret .= " {$attr}=\"{$val}\"";
		}
	}else{
		$ret .= $attrs;
	}
	return $ret . '>';
}

// Basic function to generate XML
function mkXMLdata($data){
	if(gettype($data) == 'array'){
		if(empty($data[0])) return '';
		if(empty($data[1])) $data[1]='';
		if(empty($data[2])) $data[2]=[];
		if(gettype($data[1]) == 'array'){
			$ret = mkXMLopen($data[0], $data[2]);
			foreach($data[1] as $element){
				$ret .= mkXMLdata($element);
			}
			$ret .= "</{$data[0]}>";
			return $ret;
		}else{
			return mkXMLopen($data[0], $data[2]) . "{$data[1]}</{$data[0]}>";
		}
	}else{
		return $data;
	}
}

// Simplify mkXMLdata to reduce headache
function mkXMLtag($tagname, $innerXML = '', $attributes = []){
	return mkXMLdata([$tagname, $innerXML, $attributes]);
}

// Create an XML array
function mkXMLarray($tags, $data, $attributes = []){
	if(count($tags)==0){
		if(gettype($data)=='array'){ return $data[0]; }
		return $data;
	}else{
		$tag = $tags[0];
		unset($tags[0]);
		$tags = array_values($tags);
		$attr=[];
		if(!empty($attributes)){
			$attr = $attributes[0];
			unset($attributes[0]);
			$attributes=array_values($attributes);
		}
		$ret = "";
		foreach($data as $i){
			$xattr=[];
			if(count($tags)==0){
				if(gettype($i)=='array'){
					if(!empty($i[1])){
						$xattr=$i[1];
					}
				}
			}
			$ret .= mkXMLopen($tag, array_merge($attr, $xattr)) . mkXMLarray($tags, $i, $attributes) . "</{$tag}>";
		}
		return $ret;
	}
}

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

// Generate header (not useful at the moment)
function mkheader(){
	echo mkXMLtag('header'); // Placeholder
}

// Generate footer
function mkfooter(){
	echo mkXMLtag('footer',[
		['hr'],
		(PHPMISC_CONFIG['content']['octicons']?'This site uses icons from <a href="https://octicons.github.com/">Octicons</a>.<br/>':''),
		'This page is generated using '.mkXMLtag('a', PHPMISC_CONFIG['source']['name'], ['href' => PHPMISC_CONFIG['source']['repo']]).'.',
	]);
}

// Generate the whole page
function mkHTMLpage($title, $main){
	echo '<!DOCTYPE html><html>';
	mkhead($title);
	echo '<body>';
	mkheader();
	echo mkXMLtag('main',$main);
	mkfooter();
	echo '</body></html>';
}

function octicons($name, $cls='octicons'){
	if (!PHPMISC_CONFIG['content']['octicons']) return '';
	$octicons_data = [];
	$fh = fopen(__DIR__.'/octicons.json','r') or die('Internal error');
	if (isset($fh)) $octicons_data=json_decode(fread($fh, filesize(__DIR__.'/octicons.json')), true);
	if (isset($fh)) fclose($fh);
	if (array_key_exists($name, $octicons_data)){
		$dat = $octicons_data[$name];
		return "<svg class='{$cls}' xmlns='http://www.w3.org/2000/svg' width='{$dat['width']}' height='{$dat['height']}' viewBox='0 0 {$dat['width']} {$dat['height']}' preserveAspectRatio='none'>{$dat['path']}</svg> ";
	}else{
		return '';
	};
}

?>
