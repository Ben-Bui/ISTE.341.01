<?php
	//sanitize input
	function sanitizeString($var){
		$var = trim($var);
		$var = stripslashes($var);
		$var = htmlentities($var);
		$var = strip_tags($var);
		return $var;
	}
	
	$message = ""; //used for displaying messages on form
	
	//is the form being submitted or displayed for the first time
	if (isset($_POST['submit']))
	{
		$message = "<p>Hidden info: {$_POST['somesessioninfo']}</p>";
		//make sure cat exists and other validation
		if (!isset($_POST['name']) || !isset($_POST['comments']) || 
			strlen($_POST['name']) == 0 || strlen($_POST['comments']) == 0)
		{
			//could re-direct if want to: make sure NO output above even blank lines in php file before first <?php tag
			//header("Location: index.php");
			//create error message
			$message .= "<p>You need to enter and name and a comment!</p>";
		}
		else
		{
var_dump($_POST['comments']);
echo("<br />");
var_dump(htmlspecialchars($_POST['comments']));
echo("<br />");
var_dump(addslashes($_POST['comments']));
echo("<br />");
var_dump(htmlentities($_POST['comments']));
echo("<br />");
var_dump(strip_tags($_POST['comments']));
echo("<br />");
//add slashes, strip_tags, etc before saving, posting, etc.
//or do all at same time:
/*
	foreach ($_POST as $key=>$value) {
		$_POST[$key] = sanitizeString($value);
	}
*/
			$name = sanitizeString($_POST['name']);
			$comments = sanitizeString($_POST['comments']);
			$message .= "<p>Thank you $name for your comments: <blockquote>$comments</blockquote></p>";
		}
	}
	
	//dummy dynamic links
	$links = array('Google'=>'http://www.google.com','RIT'=>'http://www.rit.edu');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Enter a Comment</title>
	<style type="text/css">
		.msg {
			color: red;
			font-weight: bold;
		}
	</style>
</head>
<body>
<h1>Enter a comment:</h1>

<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='POST'>
<input type='hidden' name='somesessioninfo' value='been here before' />
<?php if ($message != "")
		echo "<h2 class='msg'>$message</h2>";
?>
<ul>
<?php
	//dynamic links
	foreach ($links as $key=>$value)
	{
		echo "<li><a href='$value'> $key</a></li>";
	}
?>
</ul>
<p>
Name: <input type="text" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" />
</p>
<p>
Comment: <textarea name="comments" row="5" cols="30"><?php if (isset($_POST['comments'])) echo stripslashes($_POST['comments']); else echo ""; ?> </textarea>
</p>
<input type="reset" value="Reset Form" />
<input type="submit" name="submit" value="Submit Form" />
</form>
</body>
</html>