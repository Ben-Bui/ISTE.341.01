<<<<<<< HEAD
<?php

namespace DB\Tools;

const MYCONST1 = "DB\Tools\MYCONST1";

class Foo {

    const MYCONST2 = "DB\Tools\MYCONST2";

    public function saySomething() {
        echo "Hi Foo!<br />";
    }

=======
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

>>>>>>> f5d8d22b0f0f622c1bee5895957d47540fd9d7c9
}//Foo