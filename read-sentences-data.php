<?php
// ****************** Open sentences data ******************
$debug = true;
if (isset($_GET["debug"])) {
    $debug = true;
}

$numSentences = 3;

$myFolder = 'storiesParsing/';

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
    if ($debug) {print '<p>Initialized linecount variable.</p>';}
    
    while (fgets($file) !== false) {
        $linecount++;
    }
    
    if ($debug) {print '<p>Finished counting ' . $linecount . ' lines.</p>';}

    // Select random lines from file and store in array
    $sentenceLines = [];
    $spl = new SplFileObject($filename); // SplFileObject let's us seek out and 
                                         // read a specific line
                                         // without storing entire csv file in memory  
    
    if ($debug) {print '<p>Spl file object created.</p>';}
    
    $lineNumber = 0;
    
    for ($s = 1; $s <= $numSentences; $s++) {
        $lineNumber = mt_rand(1, $linecount-1);
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