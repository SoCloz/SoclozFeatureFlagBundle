<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\Storage;

/**
 * Description of Redis
 *
 * @author jfb
 */
class Redis implements StorageInterface
{
    protected $redis;
    protected $prefix;
    protected $state = array();
    protected $loaded = false;
    
    public function __construct($host, $port, $prefix)
    {
        $this->redis = new \Redis();
        $this->redis->pconnect($host, $port);
        $this->prefix = $prefix;
    }

    public function getKey()
    {
        return sprintf("%s.features", $this->prefix);
    }
    
    public function setFeatureEnabled($feature, $state)
    {
        $this->state[$feature] = $state;
        $this->redis->hset($this->getKey(), $feature, $state);
    }

    public function getFeatureEnabled($feature)
    {
        if (!$this->loaded) {
            $ret = $this->redis->hgetall($this->getKey());
            if (is_array($ret)) {
                $this->state = $ret;
            }
            $this->loaded = true;
        }
        
        return isset($this->state[$feature]) ? $this->state[$feature] : null;
    }
}
