<?php
$uploadDir = 'uploads/';
if (is_dir($uploadDir) && is_writable($uploadDir)) {
    echo "The uploads folder exists and is writable.";
} else {
    echo "The uploads folder is either missing or not writable.";
}
?>