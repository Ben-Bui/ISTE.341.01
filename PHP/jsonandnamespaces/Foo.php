<?php

namespace DB\Tools;

use DateTime;

const MYCONST1 = "DB\Tools\MYCONST1";

class Foo {

    const MYCONST2 = "DB\Tools\MYCONST2";

    public function saySomething() {
        echo "Hi Foo!<br />";
        $dt = new DateTime();
        echo $dt->getTimestamp()."<br />";
    }

}//Foo