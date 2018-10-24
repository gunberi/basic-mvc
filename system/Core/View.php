<?php

namespace Sys\Core;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Function;
use Intervention\Image\ImageManagerStatic as Image;

class View {

    function __construct()
    {
        
    }

    static function to($path, $data = [], $type = null) {
        
        $loader = new Twig_Loader_Filesystem(APPDIR . 'View');
        $twig = new Twig_Environment($loader, array(
            'cache' => ROOT .'storage/cache',
            'autoescape' => false,
            'debug' => true
        ));

        // image manuplation
        $function = new Twig_Function('image', function($image_path, $w = null, $h = null, $q = 80, $type = 'fill') {
            return image($image_path, $w, $h, $q = 80, $type = 'fill');
        });
        $twig->addFunction($function);

        // price to string
        $function = new Twig_Function('makePrice', function($price, $tax_class_id = 0) {
            return make_price($price, $tax_class_id);
        });
        $twig->addFunction($function);

        // price calculate
        $function = new Twig_Function('calculatePrice', function($price, $tax_class_id = 0, $decimal = 4) {
            return calculate_price($price, $tax_class_id, $decimals);
        });
        $twig->addFunction($function);

        // is fast shipping
        $function = new Twig_Function('isFastShipping', function($product_id) {
            return isFastShipping($product_id);
        });
        $twig->addFunction($function);

        // time
        $function = new Twig_Function('time', function() {
            return time();
        });
        $twig->addFunction($function);

        // strtotime
        $function = new Twig_Function('strtotime', function($date) {
            return strtotime($date);
        });
        $twig->addFunction($function);

        // request
        $function = new Twig_Function('request', function() {
            $request = new \Sys\Core\Request();
            return $request;
        });
        $twig->addFunction($function);
        $twig->addGlobal('request', new \Sys\Core\Request);

        if($block) {
            $template = $twig->load($path . '.twig');
            return $template->renderBlock($block, $data);
        } else {
            return $twig->render($path . '.twig', $data);
        }
        
    }

}