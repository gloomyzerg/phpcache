<?php
interface Engine{
	public function get($appname,$key);
	public function set($appname,$key,$value);
}