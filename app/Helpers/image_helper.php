<?php

use Intervention\Image\ImageManagerStatic as Image;

if (!function_exists('image')) {
    function image($image_path, $w = null, $h = null, $q = 80, $type = 'fill')
    {
        if (!file_exists(IMG_PATH . str_ireplace('image/', '', $image_path)) || !$image_path) {
            $image_path = 'placeholder.png';
        }

        $imageInfo = pathinfo($image_path);

        $cache = 'image/cache/' . str_ireplace('image/', '', $imageInfo['dirname']) . '/' . $imageInfo['filename'] . '-' . $w . 'x' . ($h??0) . '.' . $imageInfo['extension'];

        if (!file_exists(ROOT . $cache)) {
            Image::configure(array('driver' => 'imagick'));

            if (!is_dir(IMG_PATH . 'cache/' . str_ireplace('image/', '', $imageInfo['dirname']))) {
                createPath(IMG_PATH . 'cache/' . str_ireplace('image/', '', $imageInfo['dirname']));
            }
            
            if($h && $w) {
                $image = Image::canvas($w, $h, '#ffffff');
            }
            
            $image = Image::make(IMG_PATH . str_ireplace('image/', '', $image_path));
            if($h || $w) {
                $image->resize($w, $h, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            if($h && $w) {
                $image->resizeCanvas($w, $h);
            }
            $image->save(ROOT . $cache, $q);
        }
        return $cache;
    }
}

function createPath($path)
{
    if (is_dir($path)) return true;
    $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1);
    $return = createPath($prev_path);
    return ($return && is_writable($prev_path)) ? mkdir($path) : false;
}
