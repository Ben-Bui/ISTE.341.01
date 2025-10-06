<?php
	//if form was submitted
	if (isset($_POST['submit'])) 
	{
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
		
		echo "<hr />";
		
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" rev="stylesheet" href="form.css" type="text/css" />
	<title>Form Example</title>
</head>
<body>
	<form id="sample" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<fieldset>
			<legend>Customer:</legend>
			<label for="name" class="required">Name: </label>
			<input class="required" name="name" id="name" type="text" size="30" value="<?php if (isset($_POST[	'name'])) echo $_POST['name']; else echo "Enter a name"; ?>" />
			<label for="email" class="required">Email Address: </label>
			<input class="required" name="email" id="email" type="text" size="50" value="Enter a valid email address" />
			<label for="addr">Address: </label>
			<input name="addr" id="addr" type="text" size="50" value="Enter an address" />
			<label for="city">City: </label>
			<input name="city" id="city" type="text" size="30" value="Enter a city" />
			<label for="state">State: </label>
			<select id="state" name="state" size="1">
				<option label="Connecticut" value="CT">CT</option>
				<option label="New York" value="NY" selected="selected">NY</option>
				<option label="Vermont" value="VT">VT</option>
			</select>
			<label for="zip">Zip: </label>
			<input name="zip" id="zip" type="text" size="10" value="#####-####" />
	    	<label for="datein">Date: </label>
	    	<input name="datein" id="datein" type="text" size="10" value="mm/dd/yyyy" />
	    </fieldset>
		<fieldset>
			<legend>Would you like the newsletter?</legend>
			<label for="newslettery" class="inline">
			<input name="newsletter" id="newslettery" class="inline" type="radio" value="y" checked="checked" />
			Yes</label>
			<label for="newslettern" class="inline">
	    	<input name="newsletter" id="newslettern" class="inline" type="radio" value="n" />
	    	No</label>
	    </fieldset>
	    <label for="comments">Comments:</label>
	    <textarea id="comments" name="comments" rows="5" cols="60" wrap="soft">Please put any comments here....</textarea>
	    <input name="submit" id="submit" class="submit" type="submit" value="save" />
	</form>
</body>
</html>
