<?php
include_once(PHPCACHE_ROOT.'/engine/Engine.class.php');
class PHPCacheRedis implements Engine {

	private $_config=array();

	private $_flexihash;

	public function __construct (array $arrConfig = array()) {
		$arrDefaultConfig = array(
			'host' => "127.0.0.1" ,
			'port' => 6379 
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
		$index=$this->_selectServer($appname);
		$connection=$this->_getServer($index);
		$connection->hset($appname,$key,$value);
	}

	public function get($appname,$key){
		$index=$this->_selectServer($appname);
		$connection=$this->_getServer($index);
		if(is_array($key)){
			$value=$connection->hmget($appname,$key);
		}else{
			$value=$connection->hget($appname,$key);
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

	private function _createConnection($config){
		$connection= new Redis();
		$connection->connect($config['host'], $config['port']);
		$connection->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
		return $connection;
	}
}
