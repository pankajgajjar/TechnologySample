<?php
/**
 * How to extend more than one class and use it ..
 * Language : PHP
 * @author Pankaj
 *
 */
abstract class ExtensionBridge{
	private $_exts = array();
	public $_this;
	
	function __construct()
	{
		$_this = $this;
	}
	public function addExt($object){
		$this->_exts[] = $object;
	}
	public function __get($varname){
		foreach ($this->_exts as $ext){
			if(property_exists($ext, $varname)){
				return $ext->$varname;
			}			
		}	
	}
	public function __call($method,$args){
		foreach ($this->_exts as $ext){
			if(method_exists($ext, $method)){
				return call_user_method_array($method, $ext, $args);
			}
		}
		throw new Exception("this method {$method} doesn't exists");
	}
}

class Ext1{
	private $name="";
	private $id="";
	public function setID($id){$this->id = $id;}
	public function setName($name){$this->name = $name;}
	public function getID(){return $this->id;}
	public function getName(){return $this->name;}
}
class Ext2{
	private $address="";
	private $country="";
	public function setAddress($address){$this->address = $address;}
	public function setCountry($country){$this->country = $country;}
	public function getAddress(){return $this->address;}
	public function getCountry(){return $this->country;}
}

class Extender extends ExtensionBridge{
	function __construct(){
		parent::addExt(new Ext1());
		parent::addExt(new Ext2());		
	}
	public function __toString(){
		return $this->getName().', from '.$this->getCountry();
	}
}

$o = new Extender();
$o->setName("Pankaj");
$o->setCountry("India");
echo $o;