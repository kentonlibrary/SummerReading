<?php
$images = glob('images/*.*');
$image = array_rand($images);
$imageName = $images[$image];
$fp = fopen($imageName, 'rb');
header("Content-Type: image/jpg");
header("Content-Length: " . filesize($imageName));

fpassthru($fp);
exit;
?>
