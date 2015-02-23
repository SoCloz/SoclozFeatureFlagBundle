<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\ABTesting\Splitter;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Chooses a random variation, shares the result with all features having the same variations
 * @author jfb
 */
class SharedRandom implements SplitterInterface
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @param Session $session
     */
    public function __construct($session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritDoc}
     */
    public function chooseVariation($variations, $featureName = null)
    {
        $key = sprintf("socloz_feature_flag_splitter_%s", join("_", $variations));
        if ($this->session->has($key)) {
            return $this->session->get($key);
        }
        $variation = $variations[mt_rand(0, count($variations) - 1)];
        $this->session->set($key, $variation);
        return $variation;
    }
}
