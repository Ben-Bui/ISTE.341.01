<?php

require 'Foo.php';

// use DB\Tools\Foo as SomeFooClass;

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