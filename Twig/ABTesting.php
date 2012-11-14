<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\Twig;


/**
 * Twig functions for A/B testing
 *
 * @author jfb
 */
class ABTesting extends \Twig_Extension
{

    protected $abTesting;
    protected $analytics;
    
    /**
     * @param \Socloz\FeatureFlagBundle\ABTesting\ABTestingService $abTesting
     * @param \Socloz\FeatureFlagBundle\Analytics\AnalyticsInterface $analytics
     */
    public function __construct($abTesting, $analytics)
    {
        $this->abTesting = $abTesting;
        $this->analytics = $analytics;
    }
    
    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'ab_tracking' => new \Twig_Function_Method($this, 'getTrackingCode'),
            'ab_variation' => new \Twig_Function_Method($this, 'getFeatureVariation'),
        );
    }

    /**
     * Returns the tracking code for A/B tests
     * 
     * @param string $feature
     */
    public function getTrackingCode($feature)
    {
        if ($this->analytics) {
            return $this->analytics->getTrackingCode($feature);
        }
    }
    
    /**
     * Returns the feature variation active for the current user
     * 
     * @param string $feature
     */
    public function getFeatureVariation($feature)
    {
        if ($this->abTesting) {
            return $this->abTesting->getFeatureVariation($feature);
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'ab_testing';
    }
}
