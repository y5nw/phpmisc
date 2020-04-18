<?php

include_once 'config.php';
include_once 'markdown.php';

if (defined('PHPMISC_XML')) return;
define('PHPMISC_XML', true);

// Make an opening XML tag
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
function mkXMLdata($data, $parsemd = false){
	if(gettype($data) == 'array'){
		if(empty($data[0])) return '';
		if(empty($data[1])) $data[1]='';
		if(empty($data[2])) $data[2]=[];
		if(gettype($data[1]) == 'array'){
			$ret = mkXMLopen($data[0], $data[2]);
			foreach($data[1] as $element){
				$ret .= mkXMLdata($element, $parsemd);
			}
			$ret .= "</{$data[0]}>";
			return $ret;
		}else{
			if ($parsemd) $data[1] = parseMD($data[1]);
			return mkXMLopen($data[0], $data[2]) . "{$data[1]}</{$data[0]}>";
		}
	}else{
		if ($parsemd) return parseMD($data);
		return $data;
	}
}

// Simplify mkXMLdata to reduce headache
function mkXMLtag($tagname, $innerXML = '', $attributes = [], $parsemd = false){
	return mkXMLdata([$tagname, $innerXML, $attributes], $parsemd);
}

// Create an XML array
function mkXMLarray($tags, $data, $attributes = [], $parsemd = false){
	if(count($tags)==0){
		if(gettype($data)=='array') return mkXMLdata($data[0], $parsemd);
		return mkXMLdata($data, $parsemd);
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
			$ret .= mkXMLopen($tag, array_merge($attr, $xattr)) . mkXMLarray($tags, $i, $attributes, $parsemd) . "</{$tag}>";
		}
		return $ret;
	}
}

?>
