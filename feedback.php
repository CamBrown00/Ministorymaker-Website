
<?php
include 'top.php';
?>

<h2 class="form-heading">Give Us Feedback!</h2>
<h3 class="form-heading">(Please use kind language!)</h3>

<?php
print PHP_EOL . '<!--SECTION: 1 Initialize variables -->' . PHP_EOL;

print PHP_EOL . '<!--SECTION: 1b Form variables -->' . PHP_EOL;

// Initialize variables for each form element

$firstName = "";
$lastName = "";
$email = "";
$comments = '';


print PHP_EOL . '<!--SECTION: 1c Form error flags -->' . PHP_EOL;


// Initialize an error flag for each form element we validate
$firstNameERROR = false;
$lastNameERROR = false;
$emailERROR = false;
$commentsERROR = false;


print PHP_EOL . '<!--SECTION: 1d Misc variables -->' . PHP_EOL;


// Create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();
$mailed = false;


print PHP_EOL . '<!--SECTION: 2 Process for when the form is submitted -->' . PHP_EOL;


if (isset($_POST["btnSubmit"])) {
    
    print PHP_EOL . '<!-- SECTION: 2a Security -->' . PHP_EOL;
    
    // Form URL
    $thisURL = $domain . $phpSelf;
    
    if (false) {
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
        else{
            $commentsERROR = false;
        }
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
        
        $message .= '<p>Thanks for submitting feedback!</p>' . "\n" .
                    "<p>Here's the comment that we received from your submission: </p>" . "\n";
        $message .= "<p class='comments'>" . $comments . "</p>";

        $cookingCheck = false;
        

        print PHP_EOL . '<!-- SECTION: 2g Mail to user -->' . PHP_EOL;

        $to = $email; // the person who filled out the form
        $cc = '';
        $bcc = '';
        
        $from = "Mini Story Maker <ccbrown@uvm.edu>";
        
        // subject of mail should make sense to your form
        $subject = 'Your Submitted Comment';
        
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);      
    }
}


print PHP_EOL . '<!-- SECTION 3 Display Form -->' . PHP_EOL;


?>

<?php

    print PHP_EOL . '<!-- SECTION 3a -->' . PHP_EOL;
    
    //Display form initially or if submitted unsucessfully 
        
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)){
        
        print "<section class='form-output'>";
        if ($mailed){
            print "<p style='font-size: 1.15em;'> We've sent a copy of your submission to:   ";
            print $email . '</p>';
        }
        
        print $message;
        print "</section>";
    }
    else{

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
                <label class="required" id="comments" for="txtComments">Comments</label>
                <textarea <?php if ($commentsERROR) print 'class="mistake"'; ?>
                    id="txtComments"
                    maxlength="2000"
                    name="txtComments"
                    onfocus="this.select()"
                    tabindex="200"><?php print $comments; ?></textarea>
            </p>
        </fieldset>     
        <fieldset class='buttons'>
            <input class='button' id='btnSubmit' name='btnSubmit' tabindex='900' type='submit' value='Submit'>
        </fieldset> <!-- ends buttons -->
</form>
<?php
    }
?>

<?php include 'footer.php';?>

</html>
