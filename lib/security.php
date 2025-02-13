<?php

print PHP_EOL . '<!--  BEGIN include security -->' . PHP_EOL;

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// performs a simple security check to see if our page has submitted the form to itself
function securityCheck($myFormURL = "") {
    $debugThis = false;  // you have to specifically want to test this

    $status = false; // start off thinking everything is good until a test fails
    // when it is a form page check to make sure it submitted to itself
    if ($myFormURL != "") {
        $fromPage = htmlentities($_SERVER['HTTP_REFERER'], ENT_QUOTES, 'UTF-8');

        //remove http or https
        $fromPage = preg_replace('#^https?:#', '', $fromPage);

        if ($debugThis)
            print '<p>From: ' . $fromPage . ' should match your Url: ' . $myFormURL;

        if ($fromPage == $myFormURL) {
            $status = true;
        }
    }
    
    return $status; // first try returning false to see it fail
}

print PHP_EOL . '<!--  END include security -->' . PHP_EOL;
?>