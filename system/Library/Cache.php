<?php

namespace Sys\Library;

class Cache {

    private $memcache;

    function __construct()
    {
        $this->memcache = new \Memcached();
        $this->memcache->addServer('localhost', 11211);
    }

    public function put($cacheName, $data, $expiresAt = 604800) {
        $cache_key = md5($cacheName);
		$cache_expiration = 10;
        $this->memcache->set($cache_key, serialize($data), time() + $expiresAt);

    }

    public function has($cacheName) {
        $cache_key = md5($cacheName);
        return $this->memcache->get($cache_key);
    }

    public function get($cacheName) {
        $cache_key = md5($cacheName);
        return unserialize($this->memcache->get($cache_key));
    }

    public function clear() {
        $this->memcache->flush();
    }

}