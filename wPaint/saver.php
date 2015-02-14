<?php
$folder_name="generated_images/";
if (!file_exists($folder_name)) {
    mkdir($folder_name, 0777);
}
$randomfilename = $_POST['namee'];
$strng=$_POST['strng'];
$data=str_replace('data:image/png;base64,','',$strng);
$data = base64_decode($data);
$im = imagecreatefromstring($data);
imagepng($im,$randomfilename);
imagedestroy($im);
return 'success';
//}
?>