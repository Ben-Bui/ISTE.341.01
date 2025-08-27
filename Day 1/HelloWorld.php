<html>
    <body>
<?php
    $title = "My first PHP Program";
    //one line comment
    /*
        multiline
        comment
    */
?>
    <h1><?php echo "<p>Hi World! -$title</p>"; ?></h1>
    <?php
        echo "<br />Name is ".$_GET['name']."<br />";

        //phpinfor();

        $version = phpversion();
        echo "<h2> The version is: $version</h2>";

        var_dump($_REQUEST);
    ?>
    </body>
</html>