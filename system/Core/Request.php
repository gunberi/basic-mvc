<?php
namespace Sys\Core;

class Request
{
    public $url;
    public function __construct()
    {
        $this->url = ltrim($_SERVER["REQUEST_URI"] , '/');
    }

    public function get($key = false, $filter = true)
    {
        if (!$key) {
            if (isset($_GET)) {
                return $_GET;
            } else {
                return false;
            }
        } else {
            if (isset($_GET)) {
                if (isset($_GET[$key])) {
                    if ($filter) {
                        return preg_replace('/[^A-ZİÜÖĞŞÇa-zıüöğşç0-9\-@.]/', '', $_GET[$key]);
                    } else {
                        return $_GET[$key];
                    }
                } else {
                    return null;
                }    
            } else {
                return false;
            }
        }
    }

    public function post($key = false, $filter = true)
    {
        if (!$key) {
    
            if (isset($_POST)) {
                return $_POST;
            } else {
                return false;
            }
        } else {
            if (isset($_POST)) {
                if (isset($_POST[$key])) {
                    if ($filter) {
                        return preg_replace('/[^^A-ZİÜÖĞŞÇa-zıüöğşç0-9\-#@:_(),.!@"\/\' ]/', '', $_POST[$key]);
                    } else {
                        return $_POST[$key];
                    }
                } else {
                    return null;
                }    
            } else {
                return false;
            }    
        }
    }
}