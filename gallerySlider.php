
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

    //Read the author row from the data.
    $authors[] = fgetcsv($file);
    
    //Read the title row from the data.
    $headers[] = fgetcsv($file);
    
    //Read the story row from the data.
    $storyDetails[] = fgetcsv($file);
    
    //Read the color row from the data.
    $colors[] = fgetcsv($file);
}
    fclose($file);
    include('functions.php');
    
    /******* Create Slides *******/
    $slideCount = 10;

    //Print randomized stories
    print "<ul class='slides'>";
    for ($j = 0; $j < $slideCount; $j++){
        print "<li class='slide'>";
            foreach ($headers as $header) {

                print'<h3 class="story-header">' . $header[$j] . '</h3>';

                print PHP_EOL;
            }
            
            foreach ($authors as $author) {

                print'<h4 class="story-author">by ' . $author[$j] . '</h4>';

                print PHP_EOL;
            }
            
            print('<img alt="" src="images/storyslide' . $j . '.jpg">');

            
            print('<section class="story-main">');
                print('<h5>h</h5>');
                /* This loop prints the story that corresponds with the title */
                foreach ($storyDetails as $storyDetail){
                    print'<figure class="foreground-gradient"></figure>';
                    print'<p class="flex-caption story-content-min" style="background-color:' . $colors[0][$j] . ';">' . $storyDetail[$j] . '</p>';

                    print PHP_EOL;
                }
            print('</section>');
        print "</li>";
    }
    print "</ul>";

    ?>


