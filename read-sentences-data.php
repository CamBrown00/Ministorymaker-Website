<?php
// ****************** Open sentences data ******************
$debug = false;
if (isset($_GET["debug"])) {
    $debug = true;
}

$numSentences = 3;

$myFolder = 'storiesParsing';

$myFileName = 'story-sentences';

$fileExt = '.csv';

$filename = $myFolder . $myFileName . $fileExt;

if ($debug) {
    print '<p>filename is ' . $filename;
}

$file = fopen($filename, "r");

if ($debug) {
    if ($file) {
        print '<p>File opened successfully.</p>';
    } else {
        print '<p>File opening failed.</p>';
    }
}
// ****************** End open sentences data  *************

// ****************** Read sentences data ******************
if ($file) {
    if ($debug)
        print '<p>Begin reading data into an array.</p>';
 
    // Read the header row, and copy into header array for debugging
    $headers[] = fgetcsv($file);

    if ($debug) {
        print '<p>Finished reading headers.</p>';
        print '<p>My header array</p><pre>';
        print_r($headers);
        print '</pre>';
    }
    
    // Count number of lines in file
    $linecount = 0;
    while (fgets($this->file) !== false) $linecount++;
    
    // Select random lines from file and store in array
    $sentenceLines = [];
    $spl = new SplFileObject($filename); // SplFileObject let's us seek out and 
                                         // read a specific line
                                         // without storing entire csv file in memory  
    for ($s = 1; $s <= $numSentences; $s++) {
           $lineNumber = random_int(1,$linecount-1);
           $spl->seek($lineNumber); // seek line at specific row
           $sentenceLines[] = $spl->current(); // append line data to array
    }
    
    if ($debug) {
        print '<p>Finished reading sentences data. File closed.</p>';
        print '<p>My data array:<p><pre> ';
        print_r($sentenceLines);
        print '</pre></p>';
    }
} // ends if file was opened 
// ****************** End read image data **************
// close file
fclose($file);
?>