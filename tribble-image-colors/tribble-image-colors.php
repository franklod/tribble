<?
include 'ImageProcessing.php';

$imageFileName     = $_GET['image'];
$relativeImagePath = 'images/' . $imageFileName;

$test = ImageProcessing::GetImageInfo($relativeImagePath);

?>

<html>
<head></head>
<body>
<img src="<?=$relativeImagePath?>">
<p style="font-family: sans-serif;"><?=$test->path?>, <?=$test->format?>, <?=$test->width?>x<?=$test->height?></p>
<ul style="list-style:none; padding:0; margin:0;">
<?foreach($test->relevantColors as $rgb): ?>

<li style="overflow:hidden; line-height:30px; margin-bottom:5px; font-family: sans-serif;">
<div style="float:left; width:30px; height:30px; background: rgb(<?=$rgb?>); margin-right:10px;"></div> rgb(<?=$rgb?>)
</li>
<?endforeach;?>
</ul>
</body>
</html>