<?php
print PHP_EOL . '<!--  BEGIN include security -->' . PHP_EOL;

// Determines if page has submitted form to itself
function securityCheck($myFormURL = "") {
    $debugThis = false;
    
    //Verify token
    $token = '949bd15cb3bbfc5fb5bbc52d21fe6d8b165d8729';
    if($token == 'replace with token from lecture'){
        print "<p>Invalid token. Please get token from Instructor.";
        die();
    }
    $status = false;
    
    if ($myFormURL != "") {
        $fromPage = htmlentities($_SERVER['HTTP_REFERER'], ENT_QUOTES, 'UTF-8');
        
        $fromPage = preg_replace('#^https?:#', '', $fromPage);
        
        if ($debugThis)
            print '<p>From: ' . $fromPage . ' should match your URL: ' . $myFormURL;
        
        if ($fromPage == $myFormURL) {
            $status = true;
        }
    }
    return $status;
}
print PHP_EOL . '<!--  END include security ' . $token . ' -->' . PHP_EOL;
?>

