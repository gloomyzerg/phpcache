<?php
include_once(PHPCACHE_ROOT.'/engine/Engine.class.php');
class PHPCacheMemcache implements Engine {

	private $_config=array();

	private $_flexihash;

	public function __construct (array $arrConfig = array()) {
		$arrDefaultConfig = array(
			'host' => "127.0.0.1" ,
			'port' => 11211 ,
			'timeout' => 1,
			'persistent' => 0
			);
		
		foreach($arrConfig as $k=>$v){
			$arr[$k] = array_merge($arrDefaultConfig, $v);
		}
		$this->_config=$arr;		
		include_once(PHPCACHE_ROOT.'/lib/Flexihash.class.php');
		$this->_flexihash=new Flexihash();

		foreach($this->_config as $k=>$v){
			$key=$v['host'];
			$this->_flexihash->addTarget($key);
		}
	}

	public function set($appname,$key,$value){
		$key=$this->_getKey($appname,$key);
		$index=$this->_selectServer($key);
		$connection=$this->_getServer($index);
		$connection->set($key,$value);
	}

	public function get($appname,$key){
		if(is_array($key)){
			$value=array();
			foreach($key as $v){
				$k=$this->_getKey($appname,$v);
				$index=$this->_selectServer($k);
				$connection=$this->_getServer($index);
				$value[$v]=$connection->get($k);
			}
		}else{
			$key=$this->_getKey($appname,$key);
			$index=$this->_selectServer($key);
			$connection=$this->_getServer($index);
			$value=$connection->get($key);
		}
		return $value;
	}

	private function _getServer($index){
		foreach($this->_config as $k=>$v){
			if(array_search($index, $v)!==false){
				$connection=$this->_createConnection($v);
			}
		}
		return $connection;
	}

	private function _selectServer($key){
		$index=$this->_flexihash->lookupList($key,1);
		return $index[0];
	}

	private function _getKey($appname,$key){
		return $appname.'_'.$key;
	}

	private function _createConnection($config){
		$connection= new Memcache();
		if ($config['persistent']){
			$connection->pconnect($config['host'], $config['port'], $config['timeout']);
		} else {
			$connection->connect($config['host'], $config['port'], $config['timeout']);
		}
		return $connection;
	}
}
