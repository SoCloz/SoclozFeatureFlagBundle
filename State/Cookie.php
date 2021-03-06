<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\State;

use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

/**
 * Fetches feature states in the user session
 * @author jfb
 */
class Cookie implements StateInterface
{
    /**
     * @var HttpFoundationRequest
     */
    protected $request;

    /**
     * @param HttpFoundationRequest $request
     */
    public function __construct(HttpFoundationRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Returns the session key
     *
     * @param string $type
     *
     * @return string
     */
    private function getSessionKey($type)
    {
        return strtr("socloz_feature_flag_$type", " .[]", "____");
    }

    /**
     * {@inheritDoc}
     */
    public function setFeatureEnabled($feature, $enabled)
    {
        $key = $this->getSessionKey("${feature}_enabled");
        $this->request->cookies->get($key, $enabled);
    }

    /**
     * {@inheritDoc}
     */
    public function getFeatureEnabled($feature)
    {
        $key = $this->getSessionKey("${feature}_enabled");
        return $this->request->cookies->has($key) ? $this->request->cookies->get($key) : null;
    }

    /**
     * {@inheritDoc}
     */
    public function setFeatureVariation($feature, $variation, $suffix = null)
    {
        $key = $this->createKey($feature, $suffix);
        $this->request->cookies->get($key, $variation);
    }

    /**
     * {@inheritDoc}
     */
    public function getFeatureVariation($feature, $suffix = null)
    {
        $key = $this->createKey($feature, $suffix);
        return $this->request->cookies->has($key) ? $this->request->cookies->get($key) : null;
    }

    /**
     * @param string $feature
     * @param string $suffix
     *
     * @return string
     */
    private function createKey($feature, $suffix = null)
    {
        $key = $this->getSessionKey("${feature}_variation");

        if ($suffix) {
            $key .= '_' . $suffix;
        }
        return $key;
    }
}
