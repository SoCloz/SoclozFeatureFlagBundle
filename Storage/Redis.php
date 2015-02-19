<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\Storage;

/**
 * Description of Redis
 * @author jfb
 */
class Redis implements StorageInterface
{
    /**
     * @var \RedisArray
     */
    protected $redis;

    /**
     * @var array
     */
    protected $hosts;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var array
     */
    protected $state = array();

    /**
     * @var bool
     */
    protected $loaded = false;

    /**
     * @param string $host
     * @param string $prefix
     */
    public function __construct($host, $prefix)
    {
        $this->hosts = explode(",", $host);
        $this->prefix = $prefix;
    }

    /**
     * @return \RedisArray
     */
    protected function getRedis()
    {
        if (!$this->redis) {
            $this->redis = new \RedisArray($this->hosts);
        }
        return $this->redis;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return sprintf("%s.features", $this->prefix);
    }

    public function setFeatureEnabled($feature, $state)
    {
        $this->state[$feature] = $state;
        $this->getRedis()->hset($this->getKey(), $feature, $state);
    }

    public function getFeatureEnabled($feature)
    {
        if (!$this->loaded) {
            $ret = $this->getRedis()->hgetall($this->getKey());
            if (is_array($ret)) {
                $this->state = $ret;
            }
            $this->loaded = true;
        }

        return isset($this->state[$feature]) ? $this->state[$feature] : null;
    }
}
