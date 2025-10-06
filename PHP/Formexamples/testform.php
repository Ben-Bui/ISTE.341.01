<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Form Processing Example</title>
</head>
<body>
	<?php
		//echo form data to screen
		echo "<ul>";
		foreach ($_POST as $key => $value) { 
			if ($key <> "submit") {
				echo "<li><strong>$key:</strong> $value</li>\n";
			}
		}
		echo "</ul>";	
		
		//use print_r
		print_r($_POST);
		
		echo "<hr />";
		
		//another useful debugging tool
		var_dump($_POST);

	?>
</body>
</html>