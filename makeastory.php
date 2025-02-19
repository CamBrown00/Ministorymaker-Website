<?php
include 'top.php';

$debug = false;
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

// Open, read, and close story sentences to select random sentences
include ('read-sentences-data.php');

// Initialize sentences variables to create random mini story
$line0 = str_getcsv($sentenceLines[0]);
$line1 = str_getcsv($sentenceLines[1]);
$line2 = str_getcsv($sentenceLines[2]);

$sentence0 = $line0[4];//NOTE: later make each of these arrays that hold each author/title/s details
$sentence1 = $line1[4];
$sentence2 = $line2[4];

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

print PHP_EOL . '<!-- SECTION: 1b. form variables -->' . PHP_EOL;
// Initialize variables one for each form element
// in the order they appear on the form

$yourSentence = "";
$name = "";
$email = "";
$rating = "";

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

print PHP_EOL . '<!-- SECTION: 1c. form error flags -->' . PHP_EOL;

// Initialize Error Flags, one for each form element we validate
// in the order they appear on the form

$yourSentenceERROR = false;
$nameERROR = false;
$emailERROR = false;
$ratingERROR = false;

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

print PHP_EOL . '<!-- SECTION: 1d misc variables -->' . PHP_EOL;

// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();

// have we mailed the information to the user? flag variable
$mailed = false;

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

print PHP_EOL . '<!-- SECTION: 2 Process for when the form is submitted -->' . PHP_EOL;

if (isset($_POST["btnSaveStory"])) {

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
    
    $sentence0 = htmlentities($_POST["hidSentence0"], ENT_QUOTES, "UTF-8");
    $sentence1 = htmlentities($_POST["hidSentence1"], ENT_QUOTES, "UTF-8");
    $sentence2 = htmlentities($_POST["hidSentence2"], ENT_QUOTES, "UTF-8");
            
    $yourSentence = htmlentities($_POST["txtYourSentence"], ENT_QUOTES, "UTF-8");
    $name = htmlentities($_POST["txtName"], ENT_QUOTES, "UTF-8");
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $rating = htmlentities($_POST["radRating"], ENT_QUOTES, "UTF-8");
    
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
        $yourSentenceERROR = true;
    } elseif (strlen($yourSentence) == 1) { // check that sentences are longer than one character
        $errorMsg[] = "Finish the story with more than one character.";
        $yourSentenceERROR = true; 
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
    
    // check radio buttons
    if ($rating != "1" AND $rating != "2" AND $rating != "3" AND $rating != "4" AND $rating != "5") {
        $errorMsg[] = "Remember to pick a rating.";
        $ratingERROR = true;
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
        $dataRecord[] = $rating;
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
        $messageHeading = '<h2 class="">Your random mini story is complete!</h2>'
                . '<h4>Here is the story you made: </h4>';
        
        // append story sentences to message
        $message = '<article class="storyContainer">';
        $message .= '<p>' . $sentence0 . ' ' . $sentence1 . ' ' . $sentence2 . ' ';
        
        // append your sentence to message
        $message .= htmlentities($yourSentence, ENT_QUOTES, "UTF-8") . '</p>';
        $message .= '</article>';
        
        // append star rating to message
        $message .= '<p>Rating = ' . htmlentities($rating, ENT_QUOTES, "UTF-8") . ' stars </p>';
        
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
        
        $from = 'Mini Story Maker <ministorymaker1@gmail.com>';
        
        // subject of mail should make sense to your form
        $subject =  "Your Mini Story";
        
        $fullMessage = $messageHeading . $message;
        
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $fullMessage);
    
    } // end form is valid
        
  } // ends if form was submitted.             
           
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
print PHP_EOL . '<!-- SECTION: 3 Display Form -->' . PHP_EOL;
//
?>
<main>
    <article>
        <section id="howToUse">
<?php

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    print PHP_EOL . '<!-- SECTION: 3a -->' . PHP_EOL;
    
    // Display form initially or if submitted unsucessfully 

    if (isset($_POST["btnSaveStory"]) AND empty($errorMsg)) { // closing of if marked with: end body submit 
       print '<h1>What a random, mini story!</h1>';

       print '<p>Your completed story has ';

       if (!$mailed) {
           print '<span id="notMailed">not </span>';
       } 
       print 'been sent to:</p>';
       print '<p>' . $email . '</p>';

       print '<article id="message" class="">';
       print $message;
       print '</section></article>';
    } 
    else {
        print '<h1 class=""><a href="makeastory.php">Make a story!</a></h1>';
        print '<p class="">A story has been randomly generated for you! '
                . 'Press the arrow <span class="nextStoryContainer"><a href="makeastory.php" class="nextStory">></a></span> '
                . 'to generate a new story. Finish the random story with your own ending. '
                . 'To save your story: enter your name and email. Then press <span class="saveStory">"Save and Send Story"</span> to receive a copy.</p>';
        //print '<p class="">Finish the random story with your own ending.</p>';
        //print '<p class="">To save your story: enter your name and email, and press "Send Story" to receive a copy.</p>';
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        
        print PHP_EOL . '<!-- SECTION: 3b Error Messages -->' . PHP_EOL;
        
        // display any error messages before we print out the form

        if ($errorMsg) {
            print '<div id="errors">' . PHP_EOL;
            print '<h2>Oops! Can\'t save yet. Let\'s fix these mistakes first: </h2>' . PHP_EOL;
            print '<ol>' . PHP_EOL;

            foreach ($errorMsg as $err) {
                print '<li>' . $err . '</li>' . PHP_EOL;
            }

            print '</ol>' . PHP_EOL;
            print '</div>' . PHP_EOL;
        }

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        print '</section>';
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
   <section id="makeTheStory"> 
   <section id="randomStory" class="">
            <p><?php print $sentence0 ?></p>
            <p><?php print $sentence1 ?></p>
            <p><?php print $sentence2 ?></p>
    </section>     
        
    <form action = "<?php print $phpSelf; ?> "
       id = "frmStory"
       method = "post"
       class="">
        
        <input class="hiddenSentence"
            id="hidSentence0"
            name="hidSentence0"
            type="hidden"
            value="<?php print $sentence0; ?>"
        >
        <input class="hiddenSentence"
            id="hidSentence1"
            name="hidSentence1"
            type="hidden"
            value="<?php print $sentence1; ?>"
        > 
        <input class="hiddenSentence"
            id="hidSentence2"
            name="hidSentence2"
            type="hidden"
            value="<?php print $sentence2; ?>"
        >   <!-- end hidden sentences -->
        
        <fieldset class="textarea sentence">
            <legend>Finish the story:</legend>
            <p>
                <label class="required" for="txtYourSentence"></label>
                <textarea <?php if ($yourSentenceERROR) print 'class="mistake"'; ?>
                    id="txtYourSentence"
                    name="txtYourSentence"
                    placeholder = "Type here to finish the story."
                    onfocus="this.select()"
                    tabindex="300"><?php print $yourSentence; ?></textarea>
                <!-- NOTE: no blank spaces inside the text area, be sure to close
                            the text area directly -->
            </p>
        </fieldset> <!-- end sentence textarea -->
        
        <fieldset class="radio rating <?php if ($ratingERROR) print ' mistake'; ?>">
            <legend>Rate your story: </legend>
            <aside class="radioWrapper">
                <input type="radio" class="hide" id="radRating5" name="radRating" value="5" tabindex="540"
                    <?php if ($rating == "5") echo ' checked="checked" '; ?>>
                <label class="radio-field" for="radRating5">&#9733;</label>

                <input type="radio" class="hide" id="radRating4" name="radRating" value="4" tabindex="530"
                    <?php if ($rating == "4") echo ' checked="checked" '; ?>>
                <label class="radio-field" for="radRating4">&#9733;</label>  

                <input type="radio" class="hide" id="radRating3" name="radRating" value="3" tabindex="520"
                    <?php if ($rating == "3") echo ' checked="checked" '; ?>>
                <label class="radio-field" for="radRating3">&#9733;</label>

                <input type="radio" class="hide" id="radRating2" name="radRating" value="2" tabindex="510"
                    <?php if ($rating == "2") echo ' checked="checked" '; ?>>
                <label class="radio-field" for="radRating2">&#9733;</label>

                <input type="radio" class="hide" id="radRating1" name="radRating" value="1" tabindex="500"
                    <?php if ($rating == "1") echo ' checked="checked" '; ?>>
                <label class="radio-field" for="radRating1">&#9733;</label>
            </aside>   
        </fieldset> <!-- ends radio rating-->
        
        <fieldset class = "contact">
            <legend>Your name and email:</legend>
            <p>
                <label class="" for="txtName">Name:</label>
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
                <legend>Save and send story</legend>
                <input class="button" id="btnSaveStory" name="btnSaveStory" tabindex="900" type="submit" value="Save and Send Story">
        </fieldset> <!-- end buttons -->

    </form> 
       
    <p id="nextStory">
           <a href="makeastory.php">></a>
    </p>
<?php
   } // ends body submit
?>
    </section>
       
       
       
    
    </article>
</main>

<?php
include 'footer.php';
?>

</body>
</html>