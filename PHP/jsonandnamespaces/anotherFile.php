<?php

require 'Foo.php';

// use DB\Tools\Foo as SomeFooClass;

spl_autoload_register(function($class){
    var_dump($class);
    $class = substr($class,strrpos($class,'\\')+1).".php";
    require_once($class);
});

// $foo = new Foo();
$foo = new DB\Tools\Foo();
// $foo = new SomeFooClass();

echo DB\Tools\MYCONST1."<br />";
$foo->saySomething();
echo $foo::MYCONST2."<br />";
echo DB\Tools\Foo::MYCONST2."<br />";

//use global DateTime function
$dt = new DateTime();
echo $dt->getTimestamp()."<br />";