<?php namespace Sys\Library;

class Session
{
    public static function set($data, $value = false)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $_SESSION['userdata'][$key] = $value;
            }
        } else {
            $_SESSION['userdata'][$data] = $value;
        }
        return true;
    }

    public static function unset($data, $value = false)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($_SESSION['userdata'][$key]);
            }
        } else {
            unset($_SESSION['userdata'][$data]);
        }
        return true;
    }

    public static function get($data = false)
    {

        if ($data) {

            if (isset($_SESSION) && isset($_SESSION['userdata'][$data])) {
                return $_SESSION['userdata'][$data];
            } else {
                return false;
            }

        } else {
            return $_SESSION['userdata'];
        }

    }

    public static function set_flashdata($data, $value = false)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $_SESSION['flashdata'][$key] = $value;
            }
        } else {
            $_SESSION['flashdata'][$data] = $value;
        }
        return true;
    }

    public static function has_flashdata($name)
    {
        return isset($_SESSION['flashdata'][$data]) ? true : false;
    }

    public static function flashdata($name = false)
    {
        if ($name) {
            if (isset($_SESSION) && isset($_SESSION['flashdata'][$name])) {
                return $_SESSION['flashdata'][$name];
            } else {
                return false;
            }
        } else {
            return $_SESSION['flashdata'];
        }
        unset($_SESSION['flashdata']);
    }

    public static function ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_ip'])) {
            $ip = $_SERVER['HTTP_CLIENT_ip'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}