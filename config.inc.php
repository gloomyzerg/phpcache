<?php
$config=array(
	//engine('memcache','redis')
	'engine'=>'memcache',

	//memcache config array
	'memcache'=>array(
		array('host'=>'127.0.0.1','11211'),
		//array('host'=>'127.0.0.1','11211') //more server
	),

	//redis config array
	'redis'=>array(
		array('host'=>'127.0.0.1','6379'),
		//array('host'=>'127.0.0.1','6379') //more server
	)
);