<?php

include ("validations.php");

function showForm($errorMsg=false, $errorTxt="") {
	if ($errorMsg) {
		echo '<div id="error">'.$errorTxt.'</div>';
	}
	echo '<form id="sample" action="PHPFormExample.php" method="post">' .
		 '	<fieldset> '.
			'<legend>Customer:</legend>'.
			'<label for=\"name\" class=\"required\">Name: </label>'.
			'<input class="required" name="name" id="name" type="text" size="30" value="Enter a name" />'.
			'<label for="email" class="required">Email Address: </label>'.
			'<input class="required" name="email" id="email" type="text" size="50" value="Enter a valid email address" />'.
			'<label for="addr">Address: </label>'.
			'<input name="addr" id="addr" type="text" size="50" value="Enter an address" />'.
			'<label for="city">City: </label>'.
			'<input name="city" id="city" type="text" size="30" value="Enter a city" />'.
			'<label for="state">State: </label>'.
			'<select id="state" name="state" size="1">'.
				'<option label="Connecticut" value="CT">CT</option>'.
				'<option label="New York" value="NY" selected="selected">NY</option>'.
				'<option label="Vermont" value="VT">VT</option>'.
			'</select>'.
			'<label for="zip">Zip: </label>'.
			'<input name="zip" id="zip" type="text" size="10" value="#####-####" />'.
	    '</fieldset>'.
		'<fieldset>'.
			'<legend>Would you like the newsletter?</legend>'.
			'<label for="newslettery" class="inline">'.
			'<input name="newsletter" id="newslettery" class="inline" type="radio" value="y" checked="checked" />'.
			'Yes</label>'.
			'<label for="newslettern" class="inline">'.
	    	'<input name="newsletter" id="newslettern" class="inline" type="radio" value="n" />'.
	    	'No</label>'.
	    '</fieldset>'.
	    '<label for="comments">Comments:</label>'.
	    '<textarea id="comments" name="comments" rows="5" cols="60" wrap="soft">Please put any comments here....</textarea>'.
	    '<input name="submit" id="submit" class="submit" type="submit" value="save" />'.
	'</form>';
} //showForm

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
<?php

if (!isset($_POST['submit'])) {
	showForm();
} else {
	//Init error variables
	$errorMsg = false;
	$errorText = "<strong>ERRORS:</strong><br />";
 
	$name = isset($_POST['name']) ? trim($_POST['name']) : '';
	$email = isset($_POST['email']) ? trim($_POST['email']) : '';
	$addr = isset($_POST['addr']) ? trim($_POST['addr']) : '';
	$city = isset($_POST['city']) ? trim($_POST['city']) : '';
	$state = isset($_POST['state']) ? trim($_POST['state']) : '';
	$zip = isset($_POST['zip']) ? trim($_POST['zip']) : '';
	$comments = isset($_POST['comments']) ? trim($_POST['comments']) : '';
	
  	if($name == "" || !alphabeticSpace($name) || strlen($name) > 30 || $name == "Enter a name") {
    	$errorText = $errorText.'You must enter a valid name.<br />';
    	$errorMsg = true;
  	}

  	if($email == "" || !emailCheck($email)) {
    	$errorText = $errorText.'You must enter a valid email.<br />';
    	$errorMsg = true;
    }

  	if($addr !="" && (!alphaNumericSpace($addr) || strlen($addr) > 50 || $addr == "Enter an address")) {
    	$errorText = $errorText.'You entered an invalid address.<br />';
    	$errorMsg = true;
  	}

  	if($city !="" && (!alphabeticSpace($city) || strlen($city) > 30 || $city == "Enter a city")) {
    	$errorText = $errorText.'You entered an invalid city.<br />';
    	$errorMsg = true;
  	}

  	if($zip !="" && !zipCode($zip)) {
    	$errorText = $errorText.'You entered an invalid zipcode.<br />';
    	$errorMsg = true;
  	}
 
  	if ($comments !="" && (sqlMetaChars($comments) || sqlInjection($comments) || sqlInjectionUnion($comments) ||
  		sqlInjectionSelect($comments) || sqlInjectionInsert($comments) || sqlInjectionDelete($comments) ||
  		 sqlInjectionUpdate($comments) || sqlInjectionDrop($comments) || crossSiteScripting($comments) ||
  		 crossSiteScriptingImg($comments))) {
    		$errorText = $errorText.'You entered invalid comments.<br />';
    		$errorMsg = true;  		 
  	}

	// Display the form again as there was an error
	if ($errorMsg) {

		showForm($errorMsg,$errorText);
	} else {

		//echo form data to screen
		echo "<ul>";
		foreach ($_POST as $key => $value) {
			if ($key <> "submit") {
				echo "<li><strong>$key:</strong> $value</li>\n";
			}
		}
		echo "</ul>";	
			
	} //form was a success!
} //else check form
?>
</body>
</html>
