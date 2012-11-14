<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\Twig;

/**
 * Twig functions for feature flags
 *
 * @author jfb
 */
class FeatureFlag extends \Twig_Extension
{

    protected $features;

    /**
     * @param \Socloz\FeatureFlagBundle\Feature\FeatureService $features
     */
    public function __construct($features)
    {
        $this->features = $features;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'feature_is_enabled' => new \Twig_Function_Method($this, 'isFeatureEnabled'),
        );
    }
    
    /**
     * Returns if a feature is enabled for the current user
     * 
     * @param string $feature
     * @return boolean
     */
    public function isFeatureEnabled($feature)
    {
        if ($this->features) {
            return $this->features->isEnabled($feature);
        }
        return true;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'feature_flag';
    }
}
