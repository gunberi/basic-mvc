<?php
namespace Sys\Core;

class Request
{
    public $url;
    public function __construct()
    {
        $this->url = ltrim($_SERVER["REQUEST_URI"] , '/');
    }

    public function get() {

    }

    public function post() {
        
    }
}