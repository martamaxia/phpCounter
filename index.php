<?php
// Allow requests from any origin (or restrict to specific domain if needed)
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/plain');

// File to store the count
$file = 'counter.txt';

// Check if the file exists, if not, create it
if (!file_exists($file)) {
    file_put_contents($file, '0'); // Initialize the file with 0
}

// Read the current counter value from the file
$count = (int) file_get_contents($file);

// Increment the counter by 1
$count++;

// Write the updated count back to the file
file_put_contents($file, $count);

// Return the updated counter value
echo $count;
?>