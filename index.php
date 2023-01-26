<?php

// Interval is the number of seconds between each photo
$interval = '10';

// Photodir is the directory containing photos and/or subdirectories of photos
// The path is relative to the directory containing this php file
$photodir = '../photos';

// Photoext is the file extension of the photos. Do not include '.', not case-sensitive.
$photoext = 'jpg';

// Optional text to remove from the path or filename of the photo.
$findtext = '/config/www';
$replacetext = '..';

// Background and text colors
$backgroundcolor = 'black';
$textcolor = 'cyan';

?>

<!DOCTYPE html>
<html>
<head>
 <title>Simple Random Photo Slideshow</title>
 <meta http-equiv="refresh" content="<?=$interval?>" >
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <style>
 img {
   object-fit: contain;
   width: 100vw;
   height: 100vh;
 }
 * {
  background-color: <?=$backgroundcolor?>;
  color: <?=$textcolor?>;
  text-align: center;
 }
 </style>
</head>

<body>

<?php

// Uncomment for debugging
//ini_set('display_startup_errors',1);
//ini_set('display_errors',1);
//error_reporting(-1);
//echo date("h:i:sa");

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
    return $results;
}

// If the list of photos is empty get a list of photos
if(empty($_SESSION['photos'])) {
 echo("<pre>Session variable photos is empty, getting file list.</pre>");
 // $_SESSION['photos'] = getDirContents('../photos', '/\.' . $photoext . '$/i');
 $_SESSION['photos'] = getDirContents($photodir, '/\.' . $photoext . '$/i');
 //$photos = getDirContents('../photos/');
}
//else {
// echo("<pre>Session variable photos not empty, not searching for files.</pre>");
//}

// Select a random array entry
$random = array_rand($_SESSION['photos'],1);

// Get the full path and filename of the random photo and strip text from the path and filename
//$photo = str_replace("/config/www","..",$_SESSION['photos'][$random]);
$photo = str_replace("$findtext","$replacetext",$_SESSION['photos'][$random]);

// Display the filename of the photo
echo("<pre>" . basename($photo) . "</pre>");

?>

<img src="<?=$photo?>"/>

</body>
</html>
