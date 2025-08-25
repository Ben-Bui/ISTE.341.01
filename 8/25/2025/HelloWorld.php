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
    ?>
    </body>
</html>