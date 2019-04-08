<?php
print PHP_EOL . '<!--  BEGIN include validation-functions -->' . PHP_EOL;

/* Functions used to validate data throughout the site */

// Handle any special characters
function verifyAlphaNum($testString){
    return (preg_match ("/^([[:alnum:]]|-|\.| |\'|&|;|#)+$/", $testString));
}

// Handle email adresses 
function verifyEmail($testString){
    return filter_var($testString, FILTER_VALIDATE_EMAIL);
}
// Check for numbers and periods.
function verifyNumeric($testString){ 
    return (is_numeric($testString));
}

// Handle phone numbers
function verifyPhone($testString){
    $regex = '/^(?:1(?:[. -])?)?(?:\((?=\d{3}\)))?([2-9]\d{2})(?:(?<=\(\d{3})\))? ?(?:(?<=\d{3})[.-])?([2-9]\d{2})[. -]?(\d{4})(?: (?i:ext)\.? ?(\d{1,5}))?$/';
    return (preg_match($regex, $testString));
}
print PHP_EOL . '<!--  END include validation-functions -->' . PHP_EOL;
?>

