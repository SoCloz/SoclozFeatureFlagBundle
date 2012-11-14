<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\Feature;

/**
 * Description of FeatureService
 *
 * @author jfb
 */
class FeatureService
{

    protected $storage;
    protected $state;
    protected $features = array();
    
    /**
     * @param \Socloz\FeatureFlagBundle\Storage\StorageInterface $storage
     * @param \Socloz\FeatureFlagBundle\State\StateInterface $state
     * @param array $features
     */
    public function __construct($storage, $state, $features)
    {
        $this->storage = $storage;
        $this->state = $state;
        foreach ($features as $name => $featureConfig) {
            $this->features[$name] = new Feature($name, $featureConfig['state'], $featureConfig['variations'], $featureConfig['description']);
        }        
    }
    
    /**
     * List features
     * 
     * @return array
     */
    public function getFeatures()
    {
        return $this->features;
    }
    
    /**
     * Returns a feature
     * 
     * @param string $feature
     * @return Feature
     */
    public function getFeature($feature)
    {
        return isset($this->features[$feature]) ? $this->features[$feature] : null;
    }
    
    /**
     * Is a feature enabled for the current user ?
     * 
     * @param string $feature
     * @return boolean
     */
    public function isEnabled($feature)
    {
        $f = $this->getFeature($feature);
        if ($f == null) return false;
        
        if ($this->state && $f->canChange()) {
            $enabled = $this->state->getFeatureEnabled($feature);
            if ($enabled !== null) return $enabled;
        }
        
        return $this->isEnabledByDefault($feature);
    }

    /**
     * Is a feature enabled by default ?
     * 
     * @param string $feature
     * @return boolean
     */
    public function isEnabledByDefault($feature)
    {
        $f = $this->getFeature($feature);
        if ($f == null) return false;
        
        if ($this->storage) {
            $enabled = $this->storage->getFeatureEnabled($feature);
            if ($enabled !== null) return $enabled;
        }
        
        return $f->isEnabled();
    }
    
    /**
     * Has the general population access to the feature ?
     * 
     * @param string $feature
     * @return boolean
     */
    public function mayBeVisible($feature)
    {
        $f = $this->getFeature($feature);
        if ($f == null) return false;
        return $f->mayBeVisible();
    }
    
    /**
     * Enables a feature for the current user
     * 
     * @param string $feature
     */
    public function enableForUser($feature)
    {
        $f = $this->getFeature($feature);
        if ($f == null) return;
        
        if ($this->state && $f->canChange()) {
            $this->state->setFeatureEnabled($feature, true);
        }
    }
    
    /**
     * Disables a feature for the current user
     * 
     * @param string $feature
     */
    public function disableForUser($feature)
    {
        $f = $this->getFeature($feature);
        if ($f == null) return;

        if ($this->state && $f->canChange()) {
            $this->state->setFeatureEnabled($feature, false);
        }
    }
    
    /**
     * Enables a feature globally
     * 
     * @param string $feature
     */
    public function enable($feature)
    {
        if (!$this->storage) {
            throw new \RuntimeException('FeatureService : no storage defined');
        }
        $this->storage->setFeatureEnabled($feature, true);
        
    }
    
    /**
     * Disables a feature globally
     * 
     * @param string $feature
     */
    public function disable($feature)
    {
        if (!$this->storage) {
            throw new \RuntimeException('FeatureService : no storage defined');
        }
        $this->storage->setFeatureEnabled($feature, false);
    }
}
