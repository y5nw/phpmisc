<?php

include 'config.php';

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

?>
