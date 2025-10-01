<?php
    require_once("validate.php");//load validate function

    $FirstName = $LastName = $Date = $Comments = $Mood = "";//make variable to score data
    $Message = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {//check if from was submit using POST

        //Grab form info, sanitize it with function
        $FirstName = sanitizeString($_POST['FirstName'] ?? "");//empty string instead if value not there
        $LastName = sanitizeString($_POST['LastName'] ?? "");
        $Date = sanitizeString($_POST['Date'] ?? "");
        $Comments = sanitizeString($_POST['Comments'] ?? "");
        $Mood = sanitizeString($_POST['Mood'] ?? "");

        $errors = [];//empty array to collect error

        //check field for validate
        if (!validateString($FirstName)) $errors[] = "First name is required";
        if (!validateString($LastName)) $errors[] = "Last name is required";
        if (!validateDateInput($Date)) $errors[] = "Date is required";
        if (!validateString($Comments, 250)) $errors[] = "Comments too long";
        if (empty($Mood)) $errors[] = "Please select a mood";

        //if no error, process form
        if (count($errors) === 0) {
            //response fit the mood
            switch ($Mood) {
                case "Happy":
                    $MoodMsg = "I'm glad you're happy today.";
                    break;
                case "Mad":
                    $MoodMsg = "I'm sorry you're mad today.";
                    break;
                case "Indifferent":
                    $MoodMsg = "I see you're feeling indifferent today.";
                    break;
                default:
                    $MoodMsg = "I hope you have a good day.";
            }

            //display message
            $Message = "<h3>Today is $Date</h3>
                       <p>Hello $FirstName $LastName. $MoodMsg</p>
                       <p><strong>Your comments:</strong> $Comments</p>";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Feelings Form</title>
</head>
<body>

    <h2>Feelings Form</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <p><strong>First Name:</strong><br>
            <input type="text" name="FirstName" value="<?php echo $FirstName; ?>">
        </p>
        
        <p><strong>Last Name:</strong><br>
            <input type="text" name="LastName" value="<?php echo $LastName; ?>">
        </p>
        
        <p><strong>Date:</strong><br>
            <input type="text" name="Date" value="<?php echo $Date; ?>">
        </p>
        
        <p><strong>Comments:</strong><br>
            <textarea name="Comments" rows="4" cols="50"><?php echo $Comments; ?></textarea>
        </p>
        
        <p><strong>Mood:</strong><br>
            <input type="radio" name="Mood" value="Happy" <?php if ($Mood=="Happy") echo "checked"; ?>> Happy<br>
            <input type="radio" name="Mood" value="Mad" <?php if ($Mood=="Mad") echo "checked"; ?>> Mad<br>
            <input type="radio" name="Mood" value="Indifferent" <?php if ($Mood=="Indifferent") echo "checked"; ?>> Indifferent
        </p>
        
        <input type="reset" value="Reset Form">
        <input type="submit" value="Submit Form">
    </form>

    <div>
        <?php echo $Message; ?>
    </div>

</body>
</html>