<?php
define('PHPCACHE_ROOT',dirname(__FILE__));

class Cache {
	private $_cache;
	private $_config;
	private $_appName;
	private $_engine=array('redis','memcache');
	public $opened=false;

	public function __construct($appname=''){
		$this->_appName=$appname;
		include(PHPCACHE_ROOT.'/config.inc.php');
		$_config=$config;
		if(!isset($_config['engine']) || !in_array($_config['engine'], $this->_engine)){
			throw new Exception("select engine is unsupport");
		}
		if(isset($_config[$_config['engine']]) && is_array($_config[$_config['engine']]) && !empty($_config[$_config['engine']])){
			include_once(PHPCACHE_ROOT.'/engine/'.ucfirst($_config['engine']).'.class.php');
			$classname='PHPCache'.ucfirst($_config['engine']);
			$this->_cache= new $classname($_config[$_config['engine']]);
		}else{
			throw new Exception("configure error");
		}
	}

	public function set($key,$value){
		try{
			$this->_cache->set($this->_appName,$key,$value);
		}catch(Exception $e){
			return false;
		}
	}

	public function get($key){
		try{
			$value=$this->_cache->get($this->_appName,$key);
		}catch(Exception $e){
			return false;
		}
		return $value;
	}
}