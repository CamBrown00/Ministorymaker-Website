
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

if ($debug) print'<p>filename is' . $filename;

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
    /*
    while (!feof($file)){
        $storyDetails[] = fgetcsv($file);
    }
    */
}
fclose($file);
?>

    <article id="content">
        <h2 class='heading'>Some of the Classics!</h2>
        
        <?php
        include('functions.php');
        
        $storyLimit = 3;
        $randomIntervalSize = sizeof($storyDetails, 1) - 1;
        $randomIndexes = createRandomArray($randomIntervalSize);
        
        //Print randomized stories
        for ($j = 0; $j < $storyLimit; $j++){
            print   "<section class='storySet'>";
                /* This loop prints a random item in the header row */
                foreach ($headers as $header) {
                
                    print'<h2 class="story-header">' . $header[$randomIndexes[$j]] . '</h2>';

                    print PHP_EOL;
                }
                
                foreach ($authors as $author) {
                
                    print'<h3 class="story-author">' . $author[$randomIndexes[$j]] . '</h3>';

                    print PHP_EOL;
                }

                /* This loop prints the story that corresponds with the title */
                foreach ($storyDetails as $storyDetail){
                    print'<p class="story">' . $storyDetail[$randomIndexes[$j]] . '</p>';

                    print PHP_EOL;
                }
                
            print "</section>";
        }
        
        ?>
    </article>


