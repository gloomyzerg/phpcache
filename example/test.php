<?php
include ('../Cache.class.php');
//Application 1
$cache=new Cache('app1');
$cache->set('test1','this is a string');
$cache->set('test2',array('is','an','array'));

//Application 2
$cache2=new Cache('app2');
$cache2->set('test1','this is a string');
$cache2->set('test2',array('is','an','array'));

print_r($cache->get(array('test1')));
print_r($cache->get(array('test2')));
//return multi value 
print_r($cache->get(array('test1','test2')));


print_r($cache2->get(array('test1')));
print_r($cache2->get(array('test2')));
//return multi value 
print_r($cache2->get(array('test1','test2')));