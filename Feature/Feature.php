<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\Feature;

/**
 * Description of Feature
 *
 * @author jfb
 */
class Feature
{
    const STATE_ENABLED = 0;
    const STATE_ENABLED_ALWAYS = 1;
    const STATE_DISABLED = 2;
    const STATE_DISABLED_HIDDEN = 4;
    const STATE_DISABLED_ALWAYS = 4;
    
    protected $name;
    protected $state;
    protected $variations;
    protected $description;
    
    /*
     * @param string $name
     * @param int $state
     * @param array $variations
     */
    public function __construct($name, $state, $variations, $description = null)
    {
        $this->name = $name;
        $this->state = $state;
        $this->variations = $variations;
        $this->description = $description;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }
    
    public function getState()
    {
        return $this->name;
    }
    
    public function getVariations()
    {
        return $this->variations;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * It it enabled ?
     * 
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->state == self::STATE_ENABLED || $this->state == self::STATE_ENABLED_ALWAYS;
        
    }

    /**
     * Is it possible to change the state of a feature ?
     * 
     * @return boolean
     */
    public function canChange()
    {
        return $this->state == self::STATE_DISABLED || $this->state == self::STATE_DISABLED_HIDDEN || $this->state == self::STATE_ENABLED;
    }
    
    /**
     * Has the general population access to the feature ?
     * 
     * @return boolean
     */
    public function mayBeVisible()
    {
        return $this->state == self::STATE_ENABLED || $this->state == self::STATE_ENABLED_ALWAYS || $this->state == self::STATE_DISABLED;
    }
}
