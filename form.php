<?php
include 'top.php';

print PHP_EOL . '<!--SECTION: 1 Initialize variables -->' . PHP_EOL;

print PHP_EOL . '<!--SECTION: 1b Form variables -->' . PHP_EOL;

// Initialize variables for each form element

$firstName = "";
$lastName = "";
$email = "ccbrown@uvm.edu";
$comments = '';
$meat = 'Beef';
$grilling = true;
$roasting = false;
$smoking = false;
$frequency = "Monthly";


print PHP_EOL . '<!--SECTION: 1c Form error flags -->' . PHP_EOL;


// Initialize an error flag for each form element we validate
$firstNameERROR = false;
$lastNameERROR = false;
$emailERROR = false;
$commentsERROR = false;
$meatERROR = false;
$cookingMethodERROR = false;
$totalChecked = 0;
$frequencyERROR = false;


print PHP_EOL . '<!--SECTION: 1d Misc variables -->' . PHP_EOL;


// Create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();
$mailed = false;


print PHP_EOL . '<!--SECTION: 2 Process for when the form is submitted -->' . PHP_EOL;


if (isset($_POST["btnSubmit"])) {
    
    print PHP_EOL . '<!-- SECTION: 2a Security -->' . PHP_EOL;
    
    // Form URL
    $thisURL = $domain . $phpSelf;
    
    if (!securityCheck($thisURL)) {
        $msg = '<p>Sorry you cannot access this page.</p>';
        $msg.= '<p>Security breach detected and reported.</p>';
        die($msg);
    }
    
    print PHP_EOL . '<!-- SECTION: 2b Sanitize (clean) data  -->' . PHP_EOL;
    // remove any potential JavaScript or html code from user's input.
        
    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $comments = htmlentities($_POST["txtComments"], ENT_QUOTES, "UTF-8");
    $meat = htmlentities($_POST["radMeat"], ENT_QUOTES, "UTF-8");   
    if (isset($_POST["chkGrilling"])){
        $grilling = true;
        $totalChecked++;
    } else {
        $grilling = false;
    }
    
    if (isset($_POST["chkRoasting"])){
        $roasting = true;
        $totalChecked++;
    } else {
        $roasting = false;
    }
    
    if (isset($_POST["chkSmoking"])){
        $smoking = true;
        $totalChecked++;
    } else {
        $smoking = false;
    }
    $frequency = htmlentities($_POST["1stFrequency"], ENT_QUOTES, "UTF-8");
    
    
    print PHP_EOL . '<!-- SECTION: 2c Validation -->' . PHP_EOL;

    
    // Check each value for invalid input
    if ($firstName == ""){
        $errorMsg[] = "Please enter your first name";
        $firstNameERROR = true;
    } elseif (!verifyAlphaNum($firstName)){
        $errorMsg[] = "Your first name appears to have extra characters.";
        $firstNameERROR = true;
    }
    
    if ($lastName == ""){
        $errorMsg[] = "Please enter your last name";
        $lastNameERROR = true;
    } elseif (!verifyAlphaNum($lastName)){
        $errorMsg[] = "Your last name appears to have extra characters.";
        $lastNameERROR = true;
    }
    
    if ($email == ""){
        $errorMsg[] = 'Please enter your email address';
        $emailERROR = true;
    }elseif (!verifyEmail($email)) {
        $errorMsg[] = 'Your email address appears to be incorrect.';
        $emailERROR = true;
    }
        
    if ($comments != ""){
        if (!verifyAlphaNum($comments)){
            $errorMsg[] = "Your comments appear to have extra characters that are not allowed.";
            $commentsERROR = true;
        }
    }
    
    if ($meat != "Beef" AND $meat != "Pork" AND $meat != "Other"){
        $errorMsg[] = "Please specify your favorite meat";
        $meatERROR = true;
    }
    
    if ($totalChecked < 1){
        $errorMsg[] = "Please choose at least one cooking method you are interested in";
        $cookingMethodERROR = true;
    }
    
    if ($frequency == " "){
        $errorMsg[] = "Please choose how frequently you would like to be emailed";
        $frequencyERROR = true;
    }
    

    print PHP_EOL . '<!-- SECTION: 2d Process Form - Passed Validation -->' . PHP_EOL;

    
    // Process for when the form passes validation (the errorMSG array is empty)
    if (!$errorMsg){
        if ($debug) print '<p>Form is valid</p>';

        
        print PHP_EOL . '<!-- SECTION: 2e Save Data -->' . PHP_EOL;
        // This block saves the data to a CSV file.
        
        // Arrray used to hold form values that will be saved to a CSV file
        $dataRecord = array();

        $dataRecord[] = $firstName;
        $dataRecord[] = $lastName;
        $dataRecord[] = $email;
        $dataRecord[] = $comments;
        $dataRecord[] = $meat;
        $dataRecord[] = $grilling;
        $dataRecord[] = $roasting;
        $dataRecord[] = $smoking;
        $dataRecord[] = $frequency;
        
        // Organize CSV file
        $myFolder = 'data/';
        $myFileName = 'registration';
        $fileExt = '.csv';
        $filename = $myFolder . $myFileName . $fileExt;
        
        if ($debug) print PHP_EOL . '<p>filename is ' . $filename;
        
        //Write to and close file
        $file = fopen($filename, 'a');
        fputcsv($file, $dataRecord);
        fclose($file);
        
        
        print PHP_EOL . '<!-- SECTION: 2f Create Message -->' . PHP_EOL;
        
        // Build message to be mailed and displayed.
        //Inline styling is required for changing the look of the email message.
        
        $message = '<h2>Your information:</h2>' . "\n";
        $message .= '<p>Thanks for using this form</p>' . "\n" .
                    "<p>Here's the information that we received from your form: </p>" . "\n";
        $message .= "<table rules='all' style='border-collapse: collapse; width: 100%;'>" . "\n";
        $message .= "<th style='border: 1px solid #1a0d0d; padding: 8px;'>Categories</th><th style='border: 1px solid #1a0d0d; padding: 8px;'>Your Info</th>";

        $cookingCheck = false;

        foreach($_POST as $htmlName => $value){
            
            // breaks up the form names into words. For example
            // txtFirstName becomes First Name
            $camelCase = preg_split('/(?=[A-Z])/', substr($htmlName, 3));
            if (($htmlName == "chkGrilling" or $htmlName == "chkRoasting" or $htmlName == "chkSmoking") && ($cookingCheck == false)){
                $cookingCheck = true;
                $message .= "<tr style='border: 1px solid #1a0d0d;'><td style='border: 1px solid #1a0d0d; padding: 8px;'>";
                $message .= " Cooking Methods ";
                $message .= "</td><td style='border: 1px solid #1a0d0d; padding: 8px;'>";
            }
            else if (! ($htmlName == "chkGrilling" or $htmlName == "chkRoasting" or $htmlName == "chkSmoking") && ! ($htmlName == 'Frequency')){
                $message .= "<tr style='border: 1px solid #1a0d0d;'><td style='border: 1px solid #1a0d0d; padding: 8px;'>";
                foreach($camelCase as $oneWord){
                    $message .= $oneWord . ' ';
                }
                $message .= "</td><td style='border: 1px solid #1a0d0d; padding: 8px;'>";
            }
            else if ($htmlName == 'Frequency'){
                $message .= "</td><tr style='border: 1px solid #1a0d0d;'><td style='border: 1px solid #1a0d0d; padding: 8px;'>";
                foreach($camelCase as $oneWord){
                    $message .= $oneWord . ' ';
                }
                $message .= "</td><td style='border: 1px solid #1a0d0d; padding: 8px;'>";
            }
            if (htmlentities($value, ENT_QUOTES, "UTF-8") == "Grilling" or htmlentities($value, ENT_QUOTES, "UTF-8") == "Roasting" or htmlentities($value, ENT_QUOTES, "UTF-8") == "Smoking"){
                $message .= htmlentities($value, ENT_QUOTES, "UTF-8") . ' ';
            }
            else{
                $message .= htmlentities($value, ENT_QUOTES, "UTF-8") . "</td></tr>";
            }

            
        }

        $message .= '</table>';
        

        print PHP_EOL . '<!-- SECTION: 2g Mail to user -->' . PHP_EOL;

        $to = $email; // the person who filled out the form
        $cc = '';
        $bcc = '';
        
        $from = "Manuel's Meats Newsletter <ccbrown@uvm.edu>";
        
        // subject of mail should make sense to your form
        $subject = 'Your Registration Form';
        
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);      
    }
}


print PHP_EOL . '<!-- SECTION 3 Display Form -->' . PHP_EOL;


?>

<main>
    <article id='main'>

<?php

    print PHP_EOL . '<!-- SECTION 3a -->' . PHP_EOL;
    
    //Display form initially or if submitted unsucessfully 
        
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)){
        print '<h2>Thank you for providing your information.</h2>';
        
        print '<p>For your records a copy of this data has ';
        if(!$mailed){
            print "not ";
        }
        
        print 'been sent:</p>';
        print '<p>To: ' . $email . '</p>';
        
        print $message;
    }
    else{
        print "<h2>Write Something Here</h2>";
        print "<p class='form-heading'>Form Tagline</p>";


            print PHP_EOL . '<!-- SECTION 3b Error Messages -->' . PHP_EOL;

            // display any error messages before we print out the form

            if ($errorMsg){
                print '<div id = "errors">' . PHP_EOL;
                print '<h2>Your submission seems to have a few mistakes</h2>' . PHP_EOL;
                print '<ol>' . PHP_EOL;

                foreach ($errorMsg as $err){
                    print '<li>' . $err . '</li>' . PHP_EOL;
                }

                print '</ol>' . PHP_EOL;
                print '</div>' . PHP_EOL;
            }


            print PHP_EOL . '<!-- SECTION 3c html Form -->' . PHP_EOL;
            
            // Display the HTML form (sticky)
?>



<form action ='<?php print $phpSelf; ?>'
    id = 'frmRegister'
    method = 'post'>
    
        <fieldset class = 'contact'>
            <legend>Contact Information</legend>
            <p>
                <label class="required" for="txtFirstName">First Name</label>
                <input autofocus
                       <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                       id="txtFirstName"
                       maxlength="45"
                       name="txtFirstName"
                       onfocus="this.select()"
                       placeholder="Enter your first name"
                       tabindex="100"
                       type="text"
                       value="<?php print $firstName; ?>"
                >
            </p>
            
            <p>
                <label class="required" for="txtLastName">Last Name</label>
                <input
                       <?php if ($lastNameERROR) print 'class="mistake"'; ?>
                       id="txtLastName"
                       maxlength="45"
                       name="txtLastName"
                       onfocus="this.select()"
                       placeholder="Enter your last name"
                       tabindex="100"
                       type="text"
                       value="<?php print $lastName; ?>"
                >
            </p>
            
            <p> 
                <label class = 'required' for = 'txtEmail'>Email</label>
                    <input
                        <?php if ($emailERROR) print 'class="mistake"'; ?>
                        id ='txtEmail'
                        maxlength ='45'
                        name ='txtEmail'
                        onfocus ='this.select()'
                        placeholder ='Enter your email adress'
                        tabindex ='120'
                        type ='text'
                        value ='<?php print $email; ?>'
                    >
            </p>
        </fieldset> <!-- ends contact -->
        <fieldset class="textarea">
            <p>
                <label class="required" for="txtComments">Comments</label>
                <textarea <?php if ($commentsERROR) print 'class="mistake"'; ?>
                    id="txtComments"
                    maxlength="2000"
                    name="txtComments"
                    onfocus="this.select()"
                    tabindex="200"><?php print $comments; ?></textarea>
            </p>
        </fieldset>
        <fieldset class="radio <?php if ($meatERROR) print ' mistake'; ?>">
            <legend>Make a choice</legend>
            <p>    
                <label class="radio-field"><input type="radio" id="radMeatBeef" name="radMeat" value="Beef" tabindex="572" 
        <?php if ($meat == "Beef") echo ' checked="checked" '; ?>>
                    Beef</label>
            </p>
            
            <p>
                <label class="radio-field"><input type="radio" id="radMeatPork" name="radMeat" value="Pork" tabindex="574" 
        <?php if ($meat == "Pork") echo ' checked="checked" '; ?>>
                    Pork</label>
            </p>
            
             <p>
                <label class="radio-field"><input type="radio" id="radMeatOther" name="radMeat" value="Other" tabindex="574" 
        <?php if ($meat == "Senior") echo ' checked="checked" '; ?>>
                    Other</label>
            </p>
        </fieldset>
        <fieldset class="checkbox <?php if ($cookingMethodERROR) print ' mistake'; ?>">
            <legend>Please select atleast one cooking methods you are interested in:</legend>
             <p>
                <label class="check-field">
                    <input <?php if ($grilling) print " checked "; ?>
                        id="chkGrilling"
                        name="chkGrilling"
                        tabindex="420"
                        type="checkbox"
                        value="Grilling"> Grilling Meats</label>
            </p>
             <p>
                <label class="check-field">
                    <input <?php if ($roasting) print " checked "; ?>
                        id="chkRoasting" 
                        name="chkRoasting" 
                        tabindex="430"
                        type="checkbox"
                        value="Roasting"> Roasting Meats</label>
            </p>
            <p>
                <label class="check-field">
                    <input <?php if ($smoking) print " checked "; ?>
                        id="chkSmoking" 
                        name="chkSmoking" 
                        tabindex="430"
                        type="checkbox"
                        value="Smoking"> Smoking Meats</label>
            </p>
        </fieldset>
        <fieldset  class="listbox <?php if ($frequencyERROR) print ' mistake'; ?>">
            <legend>How frequently would you like to receive emails from us?</legend>
            <p>
                <select id="1stFrequency" 
                        name="1stFrequency" 
                        tabindex="520" >
                    <option <?php if ($frequency == "Monthly") print " selected "; ?>
                        value="Monthly">Monthly</option>
                    <option <?php if ($frequency == "Biannually") print " selected "; ?>
                        value="Biannually">Biannually</option>
                    <option <?php if ($frequency == "Annually") print " selected "; ?>
                        value="Annually">Annually</option>
                </select>
            </p>
        </fieldset>

        
        <fieldset class='buttons'>
            <legend></legend>
            <input class='button' id='btnSubmit' name='btnSubmit' tabindex='900' type='submit' value='Register'>
        </fieldset> <!-- ends buttons -->
</form>
<?php
    }
?>
    </article>
</main>

<?php include 'footer.php';?>

</html>
