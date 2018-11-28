function be_attachment_id_on_images( $attr, $attachment ) {
    $value = $attr["src"];
    $value = str_replace(' ', '', $value);

    $info = getimagesize($value);
    $mime = $info['mime'];
    switch ($mime) {
        case 'image/jpeg':
            $image_create_func = 'imagecreatefromjpeg';
            break;
        case 'image/png':
            $image_create_func = 'imagecreatefrompng';
            break;
        case 'image/gif':
            $image_create_func = 'imagecreatefromgif';
            break;
    }
    $avg = $image_create_func($value);
    list($width, $height) = getimagesize($value);
    $tmp = imagecreatetruecolor(1, 1);
    imagecopyresampled($tmp, $avg, 0, 0, 0, 0, 1, 1, $width, $height);
    $rgb = imagecolorat($tmp, 0, 0);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;

    $r = round(($r / 255) * 255);
    $g = round(($g / 255) * 255);
    $b = round(($b / 255) * 255);

    $attr["data-color"] = $r.','.$g.','.$b;

    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'be_attachment_id_on_images', 10, 2 );