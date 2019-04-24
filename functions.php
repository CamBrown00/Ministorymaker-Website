<?php

//Function creates array of non-duplicated random ints (used as indexes later)
function createRandomArray($arrayLength){
    $randomIndexes = array();
    for ($i = 0; $i < $arrayLength; $i++){
        $randomIndex = mt_rand(0, $arrayLength - 1);
        while (in_array($randomIndex, $randomIndexes)){
            $randomIndex = mt_rand(0, $arrayLength - 1);
        }
        $randomIndexes[$i] = $randomIndex;
    }
    return $randomIndexes;
}

?>

