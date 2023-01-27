<?php

// Interval is the number of seconds between each photo
$interval = '10';

// photoDir is the directory containing photos and/or subdirectories of photos. The path is relative to the directory containing this php file
$photoDir = '../photos';

// photoExt is the file extension of the photos. Do not include '.'. Not case-sensitive.
$photoExt = 'jpg';

// Background and text colors
$backgroundColor = 'black';
$textColor = 'white';

// Number of minutes after which the photo directory will be rescanned. The rescan is triggered by recreating the session to force a file rescan
$recanAfter = 30;

?>
<!DOCTYPE html>
<html>
<head>
 <title>Simple Random Photo Slideshow</title>
 <meta http-equiv="refresh" content="<?=$interval?>" >
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta name="apple-mobile-web-app-capable" content="yes">
 <style>
 * {
  background-color: <?=$backgroundColor?>;
 }
 img {
  object-fit: contain;
  width: 95vw;
  height: 95vh;
  margin: auto;
  display: block;
 }
 pre {
  color: <?=$textColor?>;
  text-align: center;
 }
 h2 {
  color: <?=$textColor?>;
  background: <?=$backgroundColor?>;
  /* font: bold 16px/16px Helvetica, Sans-Serif; */
  font: bold 1em Helvetica, Sans-Serif;
  position: absolute;
  bottom: 0px;
  text-align: center;
  width: 100%;
 }
 </style>
</head>
<body>
<?php
// Session variables are used to prevent rescaning photo folders every time the page is refreshed.
session_start();

// Function getDirContents was written by stackoverflow user user2226755
// URL: https://stackoverflow.com/questions/24783862/list-all-the-files-and-folders-in-a-directory-with-php-recursive-function
function getDirContents($dir, $filter = '', &$results = array()) {
    $files = scandir($dir);
    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path)) {
            if(empty($filter) || preg_match($filter, $path)) $results[] = $path;
        } elseif($value != "." && $value != "..") {
            getDirContents($path, $filter, $results);
        }
    }
    // Store the current date and time in the session cookie
    $_SESSION['LastFileScan'] = time();
    //$_SESSION['LastFileScan'] = date('Y-m-d H:i:s');
    return $results;
}

// If the list of photos is empty get a list of photos
if(empty($_SESSION['photos'])) {
 $_SESSION['photos'] = getDirContents($photoDir, '/\.' . $photoExt . '$/i');
}

// Check the age of the array containing file names and destroy the session if the age exceeds $rescanAfter
// This forces a rescan of $photoDir on the next page refresh
$arrayAge = time() - $_SESSION['LastFileScan'];
if($arrayAge > ($recanAfter * 60)){
 session_destroy();
}

// Get a random array index
$random = array_rand($_SESSION['photos'],1);

// Get the path and filename of the random photo
// Remove the filesystem directory name from scandir() results
$photo = str_replace($_SERVER['DOCUMENT_ROOT'],"",$_SESSION['photos'][$random]);

// Get the DateTimeOriginal field from the photo EXIF data
$photoExif = exif_read_data($_SESSION['photos'][$random],'IFD0');
$photoYear = date("Y",strtotime($photoExif['DateTimeOriginal']));

// Display the filename of the photo and DateTimeOriginal
echo("<h2>Year: $photoYear File:" . basename($photo) . "</h2>");

?>
<img src="<?=$photo?>"/>
</body>
</html>
