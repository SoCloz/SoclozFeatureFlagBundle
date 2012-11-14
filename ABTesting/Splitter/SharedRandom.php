<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\ABTesting\Splitter;

/**
 * Chooses a random variation, shares the result with all features having the same variations
 *
 * @author jfb
 */
class SharedRandom implements SplitterInterface
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
     * {@inheritDoc}
     */
    public function chooseVariation($variations)
    {
        $key = sprintf("socloz_feature_flag_splitter_%s", join("_", $variations));
        if ($this->session->has($key)) {
            return $this->session->get($key);
        }
        $variation = $variations[mt_rand(0, count($variations)-1)];
        $this->session->set($key, $variation);
        return $variation;
    }
}
