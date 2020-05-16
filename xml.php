<?php

include_once 'config.php';
include_once 'markdown.php';

if (defined('PHPMISC_XML')) return;
define('PHPMISC_XML', true);

// an XML object described in PHP
class XMLobject {
	public $tag;
	public $content;
	public $attrs = [];
	public $md = false;
	function opentag(){
		$ret = "<{$this->tag}";
		if(gettype($this->attrs) == 'array'){
			foreach($this->attrs as $attr => $val) $ret .= " {$attr}=\"{$val}\"";
		}else $ret .= ' '.$this->attrs;
		return $ret . '>';
	}
	public function __construct($tag = '', $content = [], $attrs = [], $md = false){
		$this->tag = $tag;
		$this->content = $content;
		$this->attrs = $attrs;
		$this->md = $md;
	}
	public function __toString(){
		if(empty($this->tag)) return '';
		$this->content ??= [];
		$ret = $this->opentag();
		switch(gettype($this->content)){
			case 'string':
				$ret .= $this->md?parseMD($this->content):$this->content;
				break;
			case 'array':
				foreach($this->content as $i){
					switch(gettype($i)){
						case 'string':
							$ret .= $this->md?parseMD($this->content):$this->content;
							break;
						case 'object':
							try{
								$ret .= $this->content->__toString();
							}catch (Exception $e){
								throw $e;
							}
						break;
						default: throw new Exception('Unknown content in tag '.$this->tag);
					}
				}
				break;
			case 'object':
				try{
					$ret .= $this->content->__toString();
				}catch (Exception $e){
					throw $e;
				}
				break;
			default: throw new Exception('Unknown content in tag '.$this->tag);
		}
		return $ret."</{$this->tag}>";
	}
	public function addcontent(...$content){
		if (gettype($this->content)!='array') $this->content = [$this->content];
		foreach ($content as $i){
			if (gettype($i)=='array') foreach ($i as $j) $this->addcontent($j);
			else if (!empty($i)) $this->content[count($this->content)] = $i;
		}
		return $this;
	}
	public function addobj($tag = '', $content = [], $attrs = [], $md = false){
		return $this->addcontent(new XMLobject($tag, $content, $attrs, $md));
	}
}

function mkXML($tag = '', $content = [], $attrs = [], $md = false){
	return new XMLobject($tag, $content, $attrs, $md);
}

// TODO: mkXMLarray() for XMLobjext objects

?>
