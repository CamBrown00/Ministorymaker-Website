<?php
$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");

// break the url up into array, extract filename
$path_parts = pathinfo($phpSelf);

//Display Errors for debugging (Temporary addition for convenience)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

?>	
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Mini Story Maker</title>
        
        <meta charset="utf-8">
        <meta name="author" content="Sydney Bertrand, AriaRay Brown, Cameron Brown">
        <meta name="description" content="This site is dedicated to presenting classic children's stories in fun and interesting ways!">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="css/flexslider.css" type="text/css">
        <link rel="stylesheet" href="css/outline.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/custom.css" type="text/css" media="screen">
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="jquery.flexslider.js"></script>
        <script>
          $(window).load(function() {
            $('.flexslider').flexslider({
                animation: "slide",
                slideshow: "false"
            });
          });
        </script>
<?php

$debug = false;
// This if statement allows us in the classroom to see what our variables are
// This is NEVER done on a live site
if (isset($_GET["debug"])) {
    $debug = true;
}
        
//Setup Path

$domain = '//';

$server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8');

$domain .= $server;

if ($debug){
    print '<p>php Self: ' . $phpSelf;
    print '<pdomain: ' . $domain;
    print '<p>Path Parts<pre>';
    print_r($path_parts);
    print '</pre></p>';
}
       
//Includes libraries

print  PHP_EOL . '<!-- include libraries -->' . PHP_EOL;        

include_once 'lib/validation-functions.php';

print  PHP_EOL . '<!-- finished including libraries -->' . PHP_EOL;        
?>     
</head>
    
<!--          Body          -->

<?php

// Include the header and nav, give each page's body a unique id
print '<body id="' . $path_parts['filename'] . '">';
    
    include('header.php');
    print PHP_EOL;
    
    include('nav.php');
    print PHP_EOL;
    
    if ($debug){
        print '<p>DEBUG MODE ACTIVATED</p>';
    }
    
    print "<!-- End of top.php -->";
?>


