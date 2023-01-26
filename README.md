# Simple Random Photo Slideshow

This is a simple slideshow programme that scans a directory for images and displays them in a random order.

The scanned directory can contain images or subdirectories containing images.

The slideshow is configured using variables at the top of the slideshow.php file:

* $interval - this sets the number of seconds a photo is displayed before the next photo is displayed.
* $photoDir - this is the path where photos or directories of photos are stored. This must be relative to the slideshow.php file.
* $photoExt - the file extension that will be search for. Only one extension is supported but it is not case sensitive.
* $rescanAfter - this is the number of minutes after which $photoDir will be rescanned.
* $backgroundColor & $textColor - the background and text colors.


### Acknowledgements
I am not an experienced PHP developer and this programme would not work without a block of code from Stack Overflow that is used to scan directories. The getDirContents() function was written user2226755 and is available at: 

https://stackoverflow.com/questions/24783862/list-all-the-files-and-folders-in-a-directory-with-php-recursive-function


