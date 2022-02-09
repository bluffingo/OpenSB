<?php
$imagesDir = 'images/';

$images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

$randomImage = $images[array_rand($images)]; // See comments
?>

<img src="<?php echo $randomImage ?>" style="height:100%;margin-left: auto;margin-right: auto;display:block;">
<center><h1>cheeseRox Mead by Gamerappa</h1>
</center>
