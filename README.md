phpcache
=======
phpcache是一个通用缓存组件,封装了memcache和redis使其统一调用接口.

组件本身依赖[php-memcache](http://pecl.php.net/package/memcache)和[php-redis](http://pecl.php.net/package/redis),在使用本组件前,先下载安装以上php扩展.

###使用
1.修改配置文件```config.inc.php```
```php
<?php
$config=array(
  //存储引擎,可选(memcache,redis)
  'engine'=>'memcache',

  //可以配置多台memcache服务器
  'memcache'=>array(
    array('host'=>'127.0.0.1','11211'),
    //array('host'=>'127.0.0.1','11211') //更多服务器
  ),

  //可以配置多台redis服务器
  'redis'=>array(
    array('host'=>'127.0.0.1','6379'),
    //array('host'=>'127.0.0.1','6379') //更多服务器
  )
);
```   

2.接口调用
```php
<?php
include ('phpcache/Cache.class.php');
//实例化时指定应用名,如app1
$cache=new Cache('app1');
$cache->set('test1','this is a string');
$cache->set('test2',array('is','an','array'));

//app2
$cache2=new Cache('app2');
$cache2->set('test1','this is a string');
$cache2->set('test2',array('is','an','array'));

print_r($cache->get(array('test1')));
print_r($cache->get(array('test2')));
//返回多值
print_r($cache->get(array('test1','test2')));


print_r($cache2->get(array('test1')));
print_r($cache2->get(array('test2')));
//返回多值
print_r($cache2->get(array('test1','test2')));
```