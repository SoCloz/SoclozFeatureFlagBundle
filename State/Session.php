<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\State;

/**
 * Stores/fetches feature states in the user session
 *
 * @author jfb
 */
class Session implements StateInterface
{

    protected $session;
    
    /**
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     */
    public function __construct($session)
    {
        $this->session = $session;
    }
    
    /**
     * Returns the session key
     * 
     * @param string $type
     */
    private function getSessionKey($type)
    {
        return "socloz_feature_flag.$type";
    }
    
    /**
     * {@inheritDoc}
     */
    public function setFeatureEnabled($feature, $enabled)
    {
        $key = $this->getSessionKey("$feature.enabled");
        $this->session->set($key, $enabled);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFeatureEnabled($feature)
    {
        $key = $this->getSessionKey("$feature.enabled");
        return $this->session->has($key) ? $this->session->get($key) : null;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setFeatureVariation($feature, $variation)
    {
        $key = $this->getSessionKey("$feature.variation");
        $this->session->set($key, $variation);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFeatureVariation($feature)
    {
        $key = $this->getSessionKey("$feature.variation");
        return $this->session->has($key) ? $this->session->get($key) : null;
    }

}
