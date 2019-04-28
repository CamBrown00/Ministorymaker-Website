
<!-- This reads and displays data from a csv file -->
<?php
//=======Open announcement data csv file=======
/* This php opens the csv file containing the stories to be read */

$debug = false;
if (isset($_GET['debug'])) {
    $debug = true;
}

$myFolder = '';
$myFileName = 'stories';
$fileExt = '.csv';
$filename = $myFolder . $myFileName . $fileExt;

$file = fopen($filename, 'r');

if ($debug) {
    if ($file){
        print'<p>File Open Succeeded.</p>';
    } 
    else{
        print'<p>File Open Failed</p>';
    }
}

//========Read announcement data========
/* This reads the csv file containing the stories */
if ($file) {

    //Read the author row from the data, copy the line for each author row.
    $authors[] = fgetcsv($file);
    
    //Read the title row from the data, copy the line for each header row.
    $headers[] = fgetcsv($file);
    
    //Read the story row from the data, copy the line for each story row.
    $storyDetails[] = fgetcsv($file);
}
    fclose($file);
    include('functions.php');
    
    /******* Create Slides *******/
    $slideCount = 2;

    //Print randomized stories
    print "<ul class='slides'>";
    for ($j = 0; $j < $slideCount; $j++){
        print "<li class='slide'>";
            foreach ($headers as $header) {

                print'<h3 class="story-header">' . $header[$j] . '</h3>';

                print PHP_EOL;
            }
            print('<img src="images/storyslide' . $j . '.jpg"/>');

            foreach ($authors as $author) {

                print'<p class="story-author">by ' . $author[$j] . '</p>';

                print PHP_EOL;
            }

            /* This loop prints the story that corresponds with the title */
            foreach ($storyDetails as $storyDetail){
                print'<p class="flex-caption story-content">' . $storyDetail[$j] . '</p>';

                print PHP_EOL;
            }

        print "</li>";
    }
    print "</ul>";

    ?>


