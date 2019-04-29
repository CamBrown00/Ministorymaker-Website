<?php
include 'top.php';
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

print PHP_EOL . '<!-- SECTION: 1 Initialize variables -->' . PHP_EOL;
// These variables are used in both sections 2 and 3, otherwise we would
// declare them in the section we needed them

print PHP_EOL . '<!-- SECTION: 1a. debugging setup -->' . PHP_EOL;
// If debug is on: When you first come to the form, it is empty. 
// When you submit the form, it displays the contents of the post array.
if ($debug) {
    print '<p>Post Array:</p><pre>';
    print_r($_POST);
    print '</pre>';
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
print PHP_EOL . '<!-- SECTION: 1b0. story variables -->' . PHP_EOL;
// Initialize sentences variables to create random mini story

$sentence0 = "";
$sentence1 = "";
$sentence2 = "";

// open, read, and close story sentences
include ('read-sentences-data.php');

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

print PHP_EOL . '<!-- SECTION: 1b. form variables -->' . PHP_EOL;
// Initialize variables one for each form element
// in the order they appear on the form

$yourSentence = "";
$name = "";
$email = "";

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

print PHP_EOL . '<!-- SECTION: 1c. form error flags -->' . PHP_EOL;

// Initialize Error Flags, one for each form element we validate
// in the order they appear on the form

$yourSentenceERROR = false;
$nameERROR = false;
$emailERROR = false;

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

print PHP_EOL . '<!-- SECTION: 1d misc variables -->' . PHP_EOL;

// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();

// have we mailed the information to the user? flag variable
$mailed = false;

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

print PHP_EOL . '<!-- SECTION: 2 Process for when the form is submitted -->' . PHP_EOL;

if (isset($_POST["btnSubmit"])) {

    print PHP_EOL . '<!-- SECTION: 2a Security -->' . PHP_EOL;
    
    // the url for this form 
    $thisURL = $domain . $phpSelf;
    
    if (!securityCheck($thisURL)) {
        $msg = '<p>Sorry, you cannot access this page.</p>';
        $msg.= '<p>Security breach detected and reported.</p>';
        die($msg);
    } 
    
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    
    print PHP_EOL . '<!-- SECTION: 2b Sanitize (clean) data -->' . PHP_EOL;
    // remove any potential JavaScript or html code from users input on the
    // form. Note it is best to follow the same order they appear on the form.
    
    $yourSentence = htmlentities($_POST["txtYourSentence"], ENT_QUOTES, "UTF-8");
    $name = htmlentities($_POST["txtName"], ENT_QUOTES, "UTF-8");
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    print PHP_EOL . '<!-- SECTION: 2c Validation -->' . PHP_EOL;

    // Validation section. Check each value for possible errors, empty or
    // not what we expect. You will need an IF block for each element you will
    // check (see above section 1c and 1d). The if blocks should also be in the
    // order that the elements appear on your form so that the error messages
    // will be in the order they appear. errorMsg will be displayed on the form
    // see section 3b. The error flag ($emailERROR) will be used in section 3c.
    
    /***Function*** to check for valid sentences that removes digits and 
     * underscores; allows multiple words; allows characters: [',-,.,!,?,,,"]; 
     * and supports extended ASCII characters.
     * ($pattern inspiration from: https://andrewwoods.net/blog/2018/name-validation-regex/)
     */
    
    function verifySentence($sentence) { 
        $pattern = '/^[A-Za-z\x{00C0}-\x{00FF}][A-Za-z\x{00C0}-\x{00FF}\'\-\.\!\?\,\"]+([\ A-Za-z\x{00C0}-\x{00FF}][A-Za-z\x{00C0}-\x{00FF}\'\-\.\!\?\,\"]+)*/u'; 
        if (preg_match($pattern, $sentence)) {
            return true; 
        } 
        return false; 
    }
    
    /***Function*** to check for valid names that can include alphanumeric characters,
     * apostrophes, hyphens, periods, and extended ASCII characters.
     */
    function verifyName($name) { 
        $pattern = '/^[A-Za-z\x{00C0}-\x{00FF}][A-Za-z\x{00C0}-\x{00FF}\'\-\.]+/u'; 
        if (preg_match($pattern, $name)) {
            return true; 
        } 
        return false; 
    }
    
    if ($yourSentence == "") { // first check if empty
        $errorMsg[] = "Finish the story with your own sentence!";
        $yourSentence = true;
    } elseif (!verifySentence($yourSentence)) { // then check that it only contains accepted characters
        $errorMsg[] = "Only use letters and the following punctuation in your sentence: [' - . ! ? , \"]";
        $yourSentenceERROR = true;
    }
    
    if (strlen($name) == 1) { // check that names are longer than one character
        $errorMsg[] = "Let's make your name longer than one character.";
        $nameERROR = true;
    } elseif (!empty($name) && !verifyName($name)) {  // only verify non-empty names, let empty names through
        $errorMsg[] = "Your name appears to have extra characters.";
        $nameERROR = true;
    }
    
    if ($email == "") {
        $errorMsg[] = 'Please enter your email address.';
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = 'Your email address appears to be incorrect.';  
        $emailERROR = true;
    }
    
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    
    print PHP_EOL . '<!-- SECTION: 2d Process Form - Passed Validation -->' . PHP_EOL;
    
    // Process for when the form passes validation (the errorMsg array is empty)
    if (!$errorMsg) {
        if ($debug)
            print '<p>Form is valid</p>';
    
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        print PHP_EOL . '<!-- SECTION: 2e Save Data -->' . PHP_EOL;
       
        // This block saves the data to a CSV file. 
        // *Note: on Mac, if saving a csv from Exscel to web: 
        // use MS-DOS Comma Separated (.csv) as file type*
        // array used to hold form values that will be saved to a CSV file
        $dataRecord = array();
        
        // assign values to the dataRecord array
        $story = $sentence0 . ' ' . $sentence1 . ' ' . $sentence2 . ' '
                . $yourSentence;
        $dataRecord[] = $story;
        $dataRecord[] = $name;
        
        // set up csv file
        $myFolder = 'data/';
        $myFileName = 'randomStories';
        $fileExt = '.csv';
        $filename = $myFolder . $myFileName . $fileExt;
        
        if ($debug) print PHP_EOL . '<p>filename is ' . $filename;
        
        // now we just open the file for append
        $file = fopen($filename, 'a');
        
        // write the forms informations
        fputcsv($file, $dataRecord);
        
        // close the file
        fclose($file);
        
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        
        print PHP_EOL . '<!-- SECTION: 2f Create message -->' . PHP_EOL;
       
        // build a message to display on the screen in section 3a and to mail
        // to the person filling out the form (section 2g).
        $message = '<h2 class="">Your random mini story is complete!</h2>'
                . '<h4>Here is the story you made: </h4>';
        
        // append story sentences to message
        $message .= '<div class="storyContainer">';
        $message .= '<p>' . $sentence0 . '</p>'
                . '<p>' . $sentence1 . '</p>'
                . '<p>' . $sentence2 . '</p>';
        
        // append your sentence to message
        $message .= '<p>' . htmlentities($yourSentence, ENT_QUOTES, "UTF-8") . '</p>';
        $message .= '</div>';
        
        // append author as name or Anonymous
        if (!empty($name)){
            $message .= '<p>By ' . htmlentities($name, ENT_QUOTES, "UTF-8") . '</p>';
        } else {
            $message .= '<p>By Anonymous</p>';
        }
        
        // append email for user reference
        $message .= '<p>Sent to: ' . htmlentities($email, ENT_QUOTES, "UTF-8") . '</p>';
      
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        print PHP_EOL . '<!-- SECTION: 2g Mail to user -->' . PHP_EOL;

        // Process for mailing a message which contains the forms data
        // the message was built in section 2f.
        $to = $email; // the person who filled out the form
        $cc = '';
        $bcc = '';
        
        $from = 'Mini Story Maker <abrown72@uvm.edu>';
        
        // subject of mail should make sense to your form
        $subject =  "Your Mini Story";
        
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    
    } // end form is valid
        
  } // ends if form was submitted.             
           
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
print PHP_EOL . '<!-- SECTION: 3 Display Form -->' . PHP_EOL;
//
?>
<main>
    <article>
<?php

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    print PHP_EOL . '<!-- SECTION: 3a -->' . PHP_EOL;
    
    // Display form initially or if submitted unsucessfully 

    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit 
       print '<h2>What a random, mini story!</h2>';

       print '<p>Your completed story has ';

       if (!$mailed) {
           print '<span id="notMailed">not </span>';
       } 
       print 'been sent to:</p>';
       print '<p>' . $email . '</p>';

       print '<article id="message" class="">';
       print $message;
       print '</article>';
    } 
    else {
        print '<h1 class="">Make a story!</h1>';
        print '<p class="">Press the "Make a Story" button, then finish the story with your own sentence. If you want to save your story: enter your email, and press "Save Your Story" to receive a copy.</p>';

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        
        print PHP_EOL . '<!-- SECTION: 3b Error Messages -->' . PHP_EOL;
        
        // display any error messages before we print out the form

        if ($errorMsg) {
            print '<div id="errors">' . PHP_EOL;
            print '<h2>Oops! Can\'t save yet. Please re-enter the following: </h2>' . PHP_EOL;
            print '<ol>' . PHP_EOL;

            foreach ($errorMsg as $err) {
                print '<li>' . $err . '</li>' . PHP_EOL;
            }

            print '</ol>' . PHP_EOL;
            print '</div>' . PHP_EOL;
        }

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        print PHP_EOL . '<!-- SECTION: 3c html Form -->' . PHP_EOL;

        /* Display the HTML form. Note that the action is to this same page. 
         * $phpSelf is defined in top.php
         * 
         * NOTE the line: value="<?php print $email; ?>
         * This makes the form sticky by displaying either the initial default value (line ??)
         * or the value they typed in (line ??)
         * 
         * NOTE this line: <?php if ($emailERROR) print 'class="mistake"'; ?>
         * This prints out a css class so that we can highlight the background etc. to
         * make it stand out that a mistake happened here.
         */
        ?>
    
    <form action = "<?php print $phpSelf; ?> "
       id = "frmStory"
       method = "post"
       class="">
        
        <fieldset class="textarea sentence">
            <legend>Finish the story:</legend>
            <p>
                <label class="required" for="txtYourSentence"></label>
                <textarea <?php if ($yourSentenceERROR) print 'class="mistake"'; ?>
                    id="txtYourSentence"
                    name="txtYourSentence"
                    placeholder = "Type your sentence here."
                    onfocus="this.select()"
                    tabindex="300"><?php print $yourSentence; ?></textarea>
                <!-- NOTE: no blank spaces inside the text area, be sure to close
                            the text area directly -->
            </p>
        </fieldset> <!-- end sentence textarea -->
        
                <fieldset class = "contact">
                <legend>Your name and email:</legend>
                <p>
                    <label class="" for="txtName">Name</label>
                        <input autofocus
                           <?php if ($nameERROR) print 'class="mistake"'; ?>
                           id="txtName"
                           maxlength="45"
                           name="txtName"
                           onfocus="this.select()"
                           placeholder="Enter your name or leave blank to be Anonymous"
                           tabindex="100"
                           type="text"
                           value="<?php print $name; ?>"
                    >   
                </p>

                <p>
                    <label class="required" for="txtEmail">Email:</label>
                        <input
                            <?php if ($emailERROR) print 'class="mistake"'; ?>
                            id = "txtEmail"
                            maxlength = "45"
                            name = "txtEmail"
                            onfocus = "this.select()"
                            placeholder = "Enter your email address"
                            tabindex = "120"
                            type = "text"
                            value = "<?php print $email; ?>"
                       >
                </p>
        </fieldset> <!-- ends email/name -->
        
        
        <fieldset class="buttons">
                <legend>Save your story</legend>
                <input class="button" id="btnSubmit" name="btnSubmit" tabindex="900" type="submit" value="Save Your Story">
        </fieldset> <!-- end buttons -->

    </form>
<?php
   } // ends body submit
?>
    </article>
</main>

<?php
include 'footer.php';
?>

</body>
</html>