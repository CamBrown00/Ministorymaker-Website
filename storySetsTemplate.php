
<!-- This reads and displays data from a csv file -->
<?php
$storyTitle = '';
if (isset($_GET['storyTitle'])) {
    $storyTitle = htmlentities($_GET['storyTitle'], ENT_QUOTES, "UTF-8");
}
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
    if ($debug) print'<p>Begin reading this data into an array.</p>';

    //Read the header row from the data, copy the line for each header row.
    $headers[] = fgetcsv($file);

    if ($debug){
        print'<p>Finished reading headers.</p>';
        print'<p>Header array: </p><pre>';
        print_r($headers);
        print'</pre>';
    }

    while (!feof($file)){
        $storyDetails[] = fgetcsv($file);
    }

    if ($debug){
        print'<p>Finished reading data. File closed.</p>';
        print'<p>My data array: <p><pre>';
        print_r($storyDetails);
        print'</pre></p>';
    }
}
fclose($file);
?>

    <article id="content">
        <h2 class='heading'>Some of the Classics!</h2>
        
        <table class='stories'>
            <?php
            /* This loop prints each item in the header row */
            foreach ($headers as $header) {
                print'<tr class="story-headers">';
                print'<th>' . $header[0] . '</th>';
                print'<th>' . $header[1] . '</th>';
                print'<th>' . $header[2] . '</th>';

                print '</tr>' . PHP_EOL;
            }

            /* Variable to increment inside foreach loop for every table row */
            $storyCount = 0;

            /* This loop prints each data column of the header row for each
              row in the array */
            foreach ($storyDetails as $storyDetail){
                $storyCount++;
                print'<tr class="story-cells">';
                print'<td>' . $storyDetail[0] . '</td>';
                print'<td>' . $storyDetail[1] . '</td>';
                print'<td>' . $storyDetail[2] . '</td>';

                print '</tr>' . PHP_EOL;
            }
            ?>
        </table>
    </article>


