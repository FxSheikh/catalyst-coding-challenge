<?php

// array to hold all the values
$results = [];

for ($x = 1; $x <= 100; $x++) {
  
  if($x%3===0 and $x%5===0){
    // echo('foobar');
    array_push($results, 'foobar'); 
  } 
  else if ($x%5===0) {
    // echo('bar');
    array_push($results, 'bar');     
  } 
  else if ($x%3===0) {
    // echo('foo');
    array_push($results, 'foo'); 
  } 
  else {
    // echo($x);
    array_push($results,$x); 
  }
}

// using implode function to join the array elements into a string
echo(implode(", ", $results));

?>